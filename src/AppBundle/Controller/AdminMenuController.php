<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Repository\UsersRepository;


class AdminMenuController extends Controller{

    /**
     * @Route("Index/Admin/Consultar", name="ConsultarAdmin")
     */
    function consultarAction(){
        $filtros= $_POST['filtros'] ?? [];
        
        $consultarservice=$this->get('consultardatos');
        $datos=$consultarservice->ConsultarDataAdmin($filtros);

        $session = $this->get('session');
        $emailAdminLogueado = $session->get('user_email');
        $sumarops = $this->get('aumentarnops');
        $sumarops->sumarnops($emailAdminLogueado);

        return new JsonResponse(array("datos"=>$datos));
    }

    /**
     * @Route("Index/Admin/Actualizar", name="ActualizarAdmin")
     */
    function actualizarAdminAction(){
        $data = $_POST['datos']; 

        $emailnew = $data['nuevoEmail'];
        $nuevoNombre = $data['nuevoNombre'];
        $nuevoRol = (int)$data['nuevoRol'];
        $emailold = $data['emailActual']; // El email actual del usuario que estamos editando

        $session = $this->get('session');
        $emailAdminLogueado = $session->get('user_email');
        
        /** @var UsersRepository $repo */
        $repo = $this->getDoctrine()->getRepository('AppBundle:Usuario');

        //Si el email cambia, comprobamos que el nuevo no esté pillado por otro
        if ($emailnew !== $emailold) {
            $existe = $repo->comprobarEmail($emailnew);
            if($existe){
                return new JsonResponse(array('error' => 'El nuevo email ya está registrado por otro usuario.'));
            }
        }

        if(!$emailold){
            return new JsonResponse(['error' => 'No se recibió el identificador del usuario'], 400);            
        }

        $datos_update = [$emailnew, $nuevoNombre, $nuevoRol, $emailold];

        //Validación (Usamos datos ficticios para pass y fecha como en tu función anterior)
        $arrayvalidable = [$emailnew, $nuevoNombre, "123456", "2009-01-01"];
        $validarservice = $this->get('validaruser');
        $error = $validarservice->validarusuario($arrayvalidable);

        if($error){
            return new JsonResponse(array('error' => $error));
        }

        // Ejecutamos la actualización (Usamos el rol 2 para que el servicio use ActualizarUser)
        $actualizarservice = $this->get('actualizardatos');
        $actualizarservice->ActualizarData($datos_update);

        $logout = false;

        //Aumentamos operaciones del ADMIN (no del usuario editado)
        $sumarops = $this->get('aumentarnops');
        $sumarops->sumarnops($emailAdminLogueado);

        // Solo actualizamos la sesion si el admin se está editando a sí mismo
        if ($emailold === $emailAdminLogueado) {
            $session->set('user_email', $emailnew);
            if ($nuevoRol !== 1) {
                $session->clear(); // Borramos la sesión para echarlo
                $logout = true;
                return new JsonResponse(array("logout"=>"Has cambiado tu propio rol. Ya no tienes permisos de Administrador. Redirigiendo al inicio..."));
            }
        }

        return new JsonResponse(array(
            "exito" => "Usuario actualizado correctamente. La tabla se ha refrescado."
        ));
    }

    
    /**
     * @Route("Index/Admin/Eliminar", name="EliminarAdmin")
     */
    function eliminarAdminAction() {
        // Obtenemos el email (ahora mejor por POST si es AJAX, pero mantenemos GET si prefieres)
        $email = $_GET['email'] ?? $_POST['email'] ?? null;
        $session = $this->get('session');
        $emailSesion = $session->get('user_email');

        if ($email) {
            /** @var UsersRepository $repo */
            $repo = $this->getDoctrine()->getRepository('AppBundle:Usuario');

            $existe = $repo->comprobarEmail($email);
        
            if (!$existe) {
                return new JsonResponse([
                    "error" => "El usuario con email '$email' no existe o ya está inactivo."
                ], 404);
            }
            
            $repo->EliminarUser($email);

            // Operación para el admin que ejecuta
            $this->get('aumentarnops')->sumarnops($emailSesion);

            // Si el admin se borra a sí mismo
            if ($email === $emailSesion) {
                $session->clear(); 
                return new JsonResponse([
                    "logout" => true, 
                    "mensaje" => "Te has eliminado a ti mismo. Redirigiendo..."
                ]);
            }

            return new JsonResponse([
                "exito" => "Usuario eliminado correctamente."
            ]);
        }

        return new JsonResponse(["error" => "No se proporcionó un email"], 400);
    }

}
?>
<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Repository\UsersRepository;


class ClientMenuController extends Controller{

    /**
     * @Route("Index/Cliente/Consultar", name="Consultar")
     */
    function consultarAction(){
        $email = $_POST['email'] ?? null;


        if(!$email){
            return new JsonResponse(['error' => 'No se recibió el email'], 400);            
        }

        
        $consultarservice=$this->get('consultardatos');
        $datos=$consultarservice->ConsultarData($email);

        //Aumentamos el numero de operaciones con nuestro servicio 
        $sumarops=$this->get('aumentarnops');
        $sumarops->sumarnops($email);

        return new JsonResponse(array("datos"=>$datos));
    }

    /**
     * @Route("Index/Cliente/Actualizar", name="Actualizar")
     */
    function actualizarAction(){
        $data = $_POST['datos']; 

        $emailnew = $data['nuevoEmail'];
        $nuevoNombre = $data['nuevoNombre'];
        $nuevoRol = $data['nuevoRol'];
        $emailold = $data['emailActual'];
        
        /** @var UsersRepository $repo */
        $repo=$this->getDoctrine()->getRepository('AppBundle:Usuario');

        if ($emailnew !== $emailold) { //Si el email es distinto al actual, comprobamos que sea tambien distinto de los demas emails
            $existe = $repo->comprobarEmail($emailnew);
            if($existe){
                return new JsonResponse(array('error'=>'El email ya existe...'));
            }
        }


        if(!$emailold){
            return new JsonResponse(['error' => 'No se recibió el email'], 400);            
        }

        $datos=[
            $emailnew, $nuevoNombre, $nuevoRol, $emailold
        ];

        $rol=2;

        $arrayvalidable=[
            $emailnew, $nuevoNombre, "123456", "2009-01-01"
        ]; //Validamos el email y el nombre, porque la contraseña y la fecha de nacimiento no los vamos a cambiar asi que introducimos unos para que no salte el validador

        $validarservice=$this->container->get('validaruser');

        $error=$validarservice->validarusuario($arrayvalidable);

        if($error){
            return new JsonResponse(array('error'=>$error));
        }

        $actualizarservice=$this->get('actualizardatos');
        $datos=$actualizarservice->ActualizarData($datos, $rol);

        //Aumentamos el numero de operaciones con nuestro servicio 
        $sumarops=$this->get('aumentarnops');
        $sumarops->sumarnops($emailold);

        $this->get('session')->set('user_email', $emailnew);

        return new JsonResponse(array("exito"=>"Datos actualizados correctamente. Se ha llamado a la funcion de consultar datos para que compruebes el cambio."));
    }

    /**
     * @Route("Index/Cliente/Eliminar", name="Eliminar")
     */
    function eliminarAction() {
        // 1. Pillamos el email de la URL (Query String)
        $email = $_GET['email'] ?? null;

        if ($email) {
            /** @var UsersRepository $repo */
            $repo=$this->getDoctrine()->getRepository('AppBundle:Usuario');
            
            // 2. Llamamos a la función del repositorio (que crearemos ahora)
            $repo->EliminarUser($email);

            // 3. Limpiamos la sesión para que el usuario deje de estar logueado
            $this->get('session')->clear();
        }

        // 4. Redirigimos al Index
        return $this->redirectToRoute('index');
    }
}



?>
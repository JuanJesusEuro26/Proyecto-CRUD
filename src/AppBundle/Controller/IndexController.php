<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class IndexController extends Controller{
    /**
     * @Route("/index", name="index")
     */
    function indexAction(){
        return $this->render("proyectfiles/index.html.twig");
    }

    /**
     * @Route("/Index/Registro", name="Registro")
     */
    function cargarRegistroAction(){
        return $this->render("proyectfiles/registro.html.twig");
    }

    /**
     * @Route("/Index/Identificacion", name="Identificacion")
     */
    function identificarUserAction(Request $request){
        $email=$request->request->get('email');
        $password=$request->request->get('pswd');

         /** @var UsersRepository $repo */
         $repo=$this->getDoctrine()->getRepository('AppBundle:Usuario');
       
        $existeemail=$repo->comprobarEmail($email);
        if($existeemail){ //Comprobamos email
            //Comprobamos si el usuario esta activo (el usuario no ha borrado la cuenta)
            $activo=$repo->isuseractive($email);
            if($activo==1){
                $contraseñacorrecta=$repo->comprobarContraseña($email, $password);
                if($contraseñacorrecta){
                    $rolusuario=$repo->comprobarRol($email);

                    // GUARDAR EMAIL EN SESIÓN 
                    $session = new Session();
                    $session->set('user_email', $email);

                    if($rolusuario==1){ //Si es admin
                        return new JsonResponse(array("redirect" => $this->generateUrl('admin_home')));
                    } else if($rolusuario==2){ //Si es cliente
                        return new JsonResponse(array("redirect" => $this->generateUrl('client_home')));
                    } else{ //Si no tiene rol
                        return new JsonResponse(array("error"=>"Este usuario no tiene rol asignado. Contacte con el soporte."));
                    }
                } else{
                    return new JsonResponse(array("error"=>"Este email esta registrado pero la contraseña introducida es incorrecta."));
                }
            } else if ($activo==0){
                return new JsonResponse(array("error"=>"Este usuario ya ha sido registrado pero la cuenta ha sido borrada.")); //EL PROCEDIMIENTO PARA CUANDO PASA ESTO TENGO QUE PREGUNTARLO
            }
        } else{
            //El email no esta registrado por lo tanto da respuesta json
            return new JsonResponse(array("error"=>"Este email no se ha encontrado en nuestra base de datos."));
        }
    } 


    /**
     * @Route("/Index/Admin", name="admin_home")
     */
    public function adminHomeAction() {
        return $this->render('proyectfiles/adminmenu.html.twig');
    }

    /**
     * @Route("/Index/Cliente", name="client_home")
     */
    public function clientHomeAction() {
        $session = new Session();
        $email = $session->get('user_email');

        // Si no hay email en sesión, lo echamos al login
        if (!$email) {
            return $this->redirectToRoute('index');
        }


        return $this->render('proyectfiles/clientmenu.html.twig', [
            'emailUsuario' => $email
        ]);
    }

    /**
     * @Route("/Logout", name="logout")
     */
    public function logoutAction() {
        $session = new Session();
        $session->clear(); // Vacía la sesión
        return $this->redirectToRoute('index');
    }
}
?>
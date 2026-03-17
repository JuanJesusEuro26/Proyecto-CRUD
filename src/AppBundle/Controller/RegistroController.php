<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Repository\UsersRepository;
use Symfony\Component\Validator\Constraints as Assert;




class RegistroController extends Controller{

    /**
     * @Route("/index/Registro/Registrar", name="registraruser")
     */
    function registraruserAction(){
        $email= $_POST['email'];
        
        /** @var UsersRepository $repo */
        $repo=$this->getDoctrine()->getRepository('AppBundle:Usuario');

        $existe=$repo->comprobarEmail($email);

        if($existe){
            return new JsonResponse(array('error'=>'El email introducido ya ha sido registrado previamente. Introduzca uno nuevo para registrarse.'));
        }
        else{
            $nombre= $_POST['nombre'];
            $passwd= $_POST['passwd'];
            $date= $_POST['date'];
            $rol= $_POST['rol'];

            $id=$repo->generateID();
            $num_operaciones=0;
            $active=true;

            $array=[$email, $nombre, $passwd, $date, $rol, $id, $num_operaciones, $active];

            $validarservice=$this->container->get('validaruser');

            $error=$validarservice->validarusuario($array);

            if($error){
                return new JsonResponse(array('error'=>$error));
            }

            $registrarservice=$this->container->get('registrarusuario'); //Registramos el servicio

            $resultado=$registrarservice->registraruser($array); //llamamos a la funcion registraruser del servicio y le pasamos el array con los datos

            if($resultado==true){
                return new JsonResponse(array('status'=>'Success'));
            }
        }
    }
}
?>
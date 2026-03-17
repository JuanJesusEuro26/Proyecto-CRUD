<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Repository\UsersRepository;
use AppBundle\Entity\Usuario;



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
            return new JsonResponse(array('error'=>'El email introducido ya ha sido registrado previamente.'));
        }
        else{
            $nombre= $_POST['nombre'];
            $passwd= $_POST['passwd'];
            $date= $_POST['date'];
            $rol= $_POST['rol'];

            $id=$repo->generateID();
            $num_operaciones=0;
            $active=true;

            $usuario= new Usuario;
            $usuario->setID($id);
            $usuario->setNombre($nombre);
            $usuario->setEmail($email);
            $usuario->setContraseña($passwd);
            $usuario->setFechaNacim(new \DateTime($date));
            $usuario->setNum_Operaciones($num_operaciones);
            $usuario->setActive($active);
            $usuario->setRol($rol);

            $repo->crearUsers($usuario);
            return new JsonResponse(array('status'=>'Success'));
        }
    }
}
?>
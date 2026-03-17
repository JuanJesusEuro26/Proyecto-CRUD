<?php



namespace AppBundle\Service;

use AppBundle\Entity\Usuario;
use AppBundle\Repository\UsersRepository;
use Doctrine\ORM\EntityManager; // Importante importar esto



class RegistroService{

    private $em;

    public function __construct(EntityManager $em){
        $this->em=$em;
    }
    
    public function registraruser(array $datos){

        $usuario= new Usuario;
        $usuario->setEmail($datos[0]);
        $usuario->setNombre($datos[1]);
        $usuario->setContraseña($datos[2]);
        $usuario->setFechaNacim(new \DateTime($datos[3]));

        $rol = $this->em->getRepository('AppBundle:Rol')->findOneBy(['nombrerol' => $datos[4]]);
        $usuario->setRolrelacion($rol);

        $usuario->setID($datos[5]);
        $usuario->setNum_Operaciones($datos[6]);
        $usuario->setActive($datos[7]);

        /** @var UsersRepository $repo */
        $repo=$this->em->getRepository('AppBundle:Usuario');

        $repo->crearUsers($usuario);

        return true;
    }

}
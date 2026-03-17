<?php



namespace AppBundle\Service;

use AppBundle\Entity\Usuario;
use AppBundle\Repository\UsersRepository;
use Doctrine\ORM\EntityManager; // Importante importar esto



class RegistroService{

    private $em;
    private $encoder;

    public function __construct(EntityManager $em, $encoder){
        $this->em=$em;
        $this->encoder=$encoder; //Para poder codificar la contraseña
    }
    
    public function registraruser(array $datos){

        $usuario= new Usuario;
        $usuario->setEmail($datos[0]);
        $usuario->setNombre($datos[1]);

        $passPlana=$datos[2];
        $passHasheada=$this->encoder->encodePassword($usuario,$passPlana);
        $usuario->setContraseña($passHasheada);


        $usuario->setFechaNacim(new \DateTime($datos[3]));

        $rol = $this->em->getRepository('AppBundle:Rol')->findOneBy(['nombrerol' => $datos[4]]);
        if (!$rol) {
            throw new \Exception("Error: No existe el rol '$datos[4]' en la tabla roles. Revisa mayúsculas y prefijos.");
        }

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
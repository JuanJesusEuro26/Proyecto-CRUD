<?php

namespace AppBundle\Service;

use AppBundle\Entity\Usuario;
use AppBundle\Repository\UsersRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;

class ConsultarService{

    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em=$em;
    }

    public function ConsultarData(string $email, int $rol){ //Esta funcion devuelve la informacion de un usuario o la de todos en funcion del rol del usuario

         /** @var UsersRepository $repo */
         $repo=$this->em->getRepository('AppBundle:Usuario');

        if($rol==1){ //Si es admin mostramos los datos de todos los usuarios

        } else if($rol==2){ //Si es cliente mostramos solo sus datos
            return $repo->infoUser($email);
        } else{ //Esto no se deberia poder
            return new JsonResponse(array('error'=>'Error al buscar los datos.'));
        }

    }

}

?>
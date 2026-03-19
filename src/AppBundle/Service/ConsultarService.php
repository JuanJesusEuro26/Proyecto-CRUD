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

    public function ConsultarData(string $email){ 
    //Esta funcion devuelve la informacion de un usuario cliente 
         /** @var UsersRepository $repo */
         $repo=$this->em->getRepository('AppBundle:Usuario');
        
         return $repo->infoUser($email);
    }

    public function ConsultarDataAdmin(array $filtros){


        /** @var UsersRepository $repo */
        $repo=$this->em->getRepository('AppBundle:Usuario');

        return $repo->ConsultarUsuarios($filtros);
    }

}

?>
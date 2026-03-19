<?php

namespace AppBundle\Service;

use AppBundle\Entity\Usuario;
use AppBundle\Repository\UsersRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;

class ActualizarService{

    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em=$em;
    }

    public function ActualizarData(array $datos){ 

         /** @var UsersRepository $repo */
         $repo=$this->em->getRepository('AppBundle:Usuario');

        return $repo->ActualizarUser($datos);        
    }

}

?>
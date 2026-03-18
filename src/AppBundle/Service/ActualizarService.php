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

    public function ActualizarData(array $datos, int $rol){ 

         /** @var UsersRepository $repo */
         $repo=$this->em->getRepository('AppBundle:Usuario');

         if($rol==1){ //Si es admin mostramos los datos de todos los usuarios

         } else if($rol==2){ //Si es cliente mostramos solo sus datos
             return $repo->ActualizarUser($datos);
         } else{ //Esto no se deberia poder
             return new JsonResponse(array('error'=>'Error al buscar los datos.'));
         }
        
    }

}

?>
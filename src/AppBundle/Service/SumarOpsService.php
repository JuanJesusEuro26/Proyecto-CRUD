<?php

namespace AppBundle\Service;

use AppBundle\Entity\Usuario;
use AppBundle\Repository\UsersRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;

class SumarOpsService{

    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em=$em;
    }

    public function sumarnops(string $email){ 

        /** @var UsersRepository $repo */
        $repo=$this->em->getRepository('AppBundle:Usuario');

        $repo->aumentarnOps($email);
    }

}

?>
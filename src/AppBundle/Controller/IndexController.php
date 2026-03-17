<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


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
}





?>
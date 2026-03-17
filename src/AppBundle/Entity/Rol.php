<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="roles")
 * @ORM\Entity
 */
class Rol{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10, unique=true)
     */
    private $nombrerol; //roles: admin o client 

    /**
     * @ORM\OneToMany(targetEntity="Usuario", mappedBy="rol_relacion")
     */
    private $usuarios;

    public function __construc(){
        $this->usuarios=new ArrayCollection();
    }

    //Getters y setter
    public function getId(){
        return $this->id;
    }

    public function getNombre(){
        return $this->nombrerol;
    }

    public function setNombre(string $nombrerol){
        $this->nombrerol=$nombrerol;
    }

    //PARA GENERAR LA TABLA USUARIOS EN LA BASE DE DATOS HEMOS CONFIGURADO ESTE ARCHIVO PHP Y SU RELACION CON LA ENTIDAD USUARIOS Y HEMOS USADO LOS SIGUIENTES COMANDOS:
    /*Validar mapping y BBDD
    php app/console doctrine:schema:validate


    Comprobar qué cambios se can a hacer:
    php app/console doctrine:schema:update --dump-sql


    Aplicar cambios:
    php app/console doctrine:schema:update --force*/
}

?>
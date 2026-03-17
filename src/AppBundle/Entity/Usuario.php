<?php
//Aqui irá la definicion de nuestra entidad usuarios, con los atributos registrados en la tabla 
namespace AppBundle\Entity;

//Esta entidad mapeara los atributos de la clase usuarios de los usuarios de la tabla RegistrarUsers
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="registrar_users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UsersRepository")
 */
class Usuario{
   /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     */
    private $id;

    /** @ORM\Column(type="string", name="nombre") */
    private $nombre;

    /** @ORM\Column(type="string", name="email") */
    private $email;

    /** @ORM\Column(type="string", name="password") */
    private $contraseña; // El nombre de la variable puede seguir siendo este

    /** @ORM\Column(type="date", name="fecha_nacimiento") */
    private $Fecha_Nacim;

    /** @ORM\Column(type="integer", name="num_operaciones") */
    private $Num_Operaciones;

    /** @ORM\Column(type="integer", name="is_active") */
    private $Active;

    /** @ORM\Column(type="string", name="rol") */
    private $Rol;

    public function getID(){ return $this->id;}
    public function getnombre(){ return $this->nombre;}
    public function getemail(){ return $this->email;}
    public function getcontraseña(){ return $this->contraseña;}
    public function getFecha_Nacim(){ return $this->Fecha_Nacim;}
    public function getNum_Operaciones(){ return $this->Num_Operaciones;}
    public function getActive(){ return $this->Active;}
    public function getRol(){ return $this->Rol;}


    public function setID($i){ $this->id = $i; return $this; }
    public function setNombre($n){ $this->nombre = $n; return $this; }
    public function setEmail($e){ $this->email = $e; return $this; }
    public function setContraseña($c){ $this->contraseña = $c; return $this; }
    public function setFechaNacim($f){ $this->Fecha_Nacim = $f; return $this; }
    public function setNum_Operaciones($no){ $this->Num_Operaciones = $no; return $this; }
    public function setActive($a){ $this->Active = $a; return $this; }
    public function setRol($r){ $this->Rol = $r; return $this; }


}


?>
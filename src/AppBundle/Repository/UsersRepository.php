<?php
//Aqui iran las consultas sql que actuarán sobre nuestra tabla
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Usuario;

class UsersRepository extends EntityRepository{

    public function crearUsers(Usuario $usuario){
        $conn= $this->getEntityManager()->getConnection();

        $sql = "INSERT INTO registrar_users (id, is_active, password, email, fecha_nacimiento, nombre, num_operaciones, rol) 
                VALUES (:id, :active, :pass, :email, :fecha, :nombre, :ops, :rol)";

        $stmnt=$conn->prepare($sql);
        $stmnt->bindValue('id', $usuario->getID());
        $stmnt->bindValue('active', $usuario->getActive());
        $stmnt->bindValue('pass', $usuario->getcontraseña());
        $stmnt->bindValue('email', $usuario->getemail());
        $stmnt->bindValue('fecha', $usuario->getFecha_Nacim()->format('Y-m-d'));
        $stmnt->bindValue('nombre', $usuario->getnombre());
        $stmnt->bindValue('ops', $usuario->getNum_Operaciones());
        $stmnt->bindValue('rol', $usuario->getRol());

        $stmnt->execute();

    }

    public function comprobarEmail(string $email){
        $conn= $this->getEntityManager()->getConnection();

        $sql = "SELECT email FROM registrar_users WHERE email = :correo";

        $stmnt=$conn->prepare($sql);
        $stmnt->bindValue('correo', $email);
        $stmnt->execute();

        return $stmnt->fetch(); //Devolvemos el resultado. Si no hay nada devuelve false
    }

    public function generateID(){
        $conn= $this->getEntityManager()->getConnection();
        $nuevoID=0;
        $existe=true;

        while($existe){
            $nuevoID= mt_rand(1000,9999);
            $sql = "SELECT id FROM registrar_users WHERE id = :id_buscado";
            $stmnt=$conn->prepare($sql);
            $stmnt->bindValue('id_buscado', $nuevoID);
            $stmnt->execute();

            if(!$stmnt->fetch()){
                $existe=false;
            }
        }

        return $nuevoID;
    }
}

?>
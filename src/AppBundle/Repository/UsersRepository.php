<?php
//Aqui iran las consultas sql que actuarán sobre nuestra tabla
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Usuario;

class UsersRepository extends EntityRepository{

    public function crearUsers(Usuario $usuario){

        $sql = "INSERT INTO registrar_users (id, is_active, password, email, fecha_nacimiento, nombre, num_operaciones, rol_id) 
                VALUES (:id, :active, :pass, :email, :fecha, :nombre, :ops, :rol_id)";

        $stmnt=$this->prepareconn()->prepare($sql);
        $stmnt->bindValue('id', $usuario->getID());
        $stmnt->bindValue('active', $usuario->getActive());
        $stmnt->bindValue('pass', $usuario->getcontraseña());
        $stmnt->bindValue('email', $usuario->getemail());
        $stmnt->bindValue('fecha', $usuario->getFecha_Nacim()->format('Y-m-d'));
        $stmnt->bindValue('nombre', $usuario->getnombre());
        $stmnt->bindValue('ops', $usuario->getNum_Operaciones());
        $stmnt->bindValue('rol_id', $usuario->getRolrelacion()->getId());

        $stmnt->execute();

    }

    public function comprobarEmail(string $email){

        $sql = "SELECT email FROM registrar_users WHERE email = :correo";

        $stmnt=$this->prepareconn()->prepare($sql);
        $stmnt->bindValue('correo', $email);
        $stmnt->execute();

        return $stmnt->fetch(); //Devolvemos el resultado. Si este email no esta registrado devuelve false
    }

    public function generateID(){
        $nuevoID=0;
        $existe=true;

        while($existe){
            $nuevoID= mt_rand(1000,9999);
            $sql = "SELECT id FROM registrar_users WHERE id = :id_buscado";
            $stmnt=$this->prepareconn()->prepare($sql);
            $stmnt->bindValue('id_buscado', $nuevoID);
            $stmnt->execute();

            if(!$stmnt->fetch()){
                $existe=false;
            }
        }

        return $nuevoID;
    }

    public function comprobarRol(string $email){
        $sql="SELECT rol_id FROM registrar_users WHERE email= :correo";
        $stmnt=$this->prepareconn()->prepare($sql);
        $stmnt->bindValue('correo', $email);
        $stmnt->execute();

        $rolfila=$stmnt->fetch();
        $rol=$rolfila['rol_id'];
        if($rol==false){
            return false; //El usuario no tiene rol (flujo de trabajo inusual, todos los usuarios se crean con rol)
        } else {
            return $rol;
        }
    }

    public function comprobarContraseña(string $email, string $contraseña){
        $sql="SELECT password FROM registrar_users WHERE email= :correo";
        $stmnt=$this->prepareconn()->prepare($sql);
        $stmnt->bindValue('correo', $email);
        $stmnt->execute();

        $fila=$stmnt->fetch(); //Guardamos en fila la informacion de la fila extraida (fetch no saca un solo valor, saca un array con formato fila=["password"=>"1234"])
        if(!$fila){
            return false; //El usuario no tiene contraseña (flujo de trabajo inusual, todos los usuarios se crean con contraseña)
        }

        $hashGuardado=$fila['password']; //Accedemos a la contraseña de la fila hash

        // password_verify compara el texto plano con el hash
        return password_verify($contraseña, $hashGuardado);
    }

    private function prepareconn(){
        $conn= $this->getEntityManager()->getConnection();
        return $conn;
    }

    public function isuseractive(string $email){
        $sql="SELECT is_active FROM registrar_users WHERE email= :correo";
        $stmnt=$this->prepareconn()->prepare($sql);
        $stmnt->bindValue('correo', $email);
        $stmnt->execute();

        $fila=$stmnt->fetch();
        if(!$fila){
            return false; 
        }
        $activo=$fila['is_active'];
        return $activo;
    }
}

?>
<?php

namespace AppBundle\Service;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidarUserService {

    private $validator;

    // Inyectamos el servicio de validación por el constructor
    public function __construct(ValidatorInterface $validator) {
        $this->validator = $validator;
    }       

    public function validarusuario(array $datos){
        $email=$datos[0];
        $nombre=$datos[1];
        $passwd=$datos[2];
        $date=$datos[3];
        
            //Reglas
            $reglasName=array(
                new Assert\NotBlank(array('message' => 'El nombre es obligatorio.')),
                new Assert\Length(array(
                    'min' => 3, 
                    'max' => 20,
                    'minMessage' => 'El nombre es demasiado corto.',
                    'maxMessage' => 'El nombre no debe tener más de 20 caracteres.'
                ))
            );

            $reglasPasswd=array(
                new Assert\NotBlank(array('message'=>'La contraseña es obligatoria.')),
                new Assert\Length(array(
                    'min'=>6,
                    'minMessage'=>'La contraseña debe tener al menos 6 caracteres.'))
            );

            $reglasEmail = array(
                new Assert\NotBlank(array('message' => 'El email es obligatorio.')),
                new Assert\Email(array('message' => 'El email "{{ value }}" no es válido.'))
            );
            
            $reglasFecha = array(
                new Assert\NotBlank(array('message' => 'La fecha es obligatoria.')),
                new Assert\Date(array('message' => 'El formato de fecha no es válido.')),
                new Assert\LessThan(array(
                    'value'=>'2010-01-01',
                    'message'=>'Introduce una fecha previa al 2010'))
            );

            $errores = array();
            $listaErrores = $this->validator->validate($nombre, $reglasName);
            if (count($listaErrores) > 0) { $errores[] = $listaErrores[0]->getMessage(); }

            $listaErrores = $this->validator->validate($email, $reglasEmail);
            if (count($listaErrores) > 0) { $errores[] = $listaErrores[0]->getMessage(); }

            $listaErrores = $this->validator->validate($passwd, $reglasPasswd);
            if (count($listaErrores) > 0) { $errores[] = $listaErrores[0]->getMessage(); }

            $listaErrores = $this->validator->validate($date, $reglasFecha);
            if (count($listaErrores) > 0) { $errores[] = $listaErrores[0]->getMessage(); }

            return (count($errores) > 0) ? $errores[0] : null;
    }
}




?>
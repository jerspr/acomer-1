<?php
namespace Usuario\Form;

use Zend\Form\Form;

class UsuarioForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('usuario');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'in_id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'va_nombre',
            'type' => 'Text',
          
            'options' => array(
                'label' => 'Nombre',          
            ),
            'attributes' => array(               
                'class' => 'span10',
                'id'   => 'va_nombre',
                'placeholder'=>'Ingrese su nombre'
            ),
        ));
        $this->add(array(
            'name' => 'va_apellidos',
            'type' => 'Text',
              'attributes' => array(               
                'class' => 'span10',
                'id'   => 'va_nombre',
                'placeholder'=>'Ingrese su Apellido'
            ),
            'options' => array(
                'label' => 'Apellidos',
            ),
        ));
        $this->add(array(
            'name' => 'va_email',
            'type' => 'Email',
            'attributes' => array(               
                'class' => 'span10',
                'id'   => 'va_email',
                'placeholder'=>'Ingrese su Correo'
            ),
            'options' => array(
                'label' => 'Correo',
            ),
        ));
        $this->add(array(
            'name' => 'Ta_rol_in_id',
            'type' => 'Select',
             'attributes' => array(               
                'class' => 'span10',
                'id'   => 'Ta_rol_in_id'
            ),
           'options' => array(
                     'label' => 'Rol',
                     'value_options' => array(
                             '1' => 'Administrador',
                             '2' => 'Editor',                   
                     ),
             )
        ));
        
        
  
     
        $this->add(array(
            'name' => 'va_contrasenia',
            'type' => 'password',
             'attributes' => array(               
                'class' => 'span10',
                'id'   => 'va_contrasenia',
                 'placeholder'=>'Ingrese su Password'
            ),
            'options' => array(
                'label' => 'Password',
            ),
        ));
        $this->add(array(
            'name' => 'va_contrasenia2',
            'type' => 'password',
             'attributes' => array(               
                'class' => 'span10',
                'id'   => 'va_contrasenia',
                 'placeholder'=>'Repita su Password'
            ),
            'options' => array(
                'label' => 'Repita su Password',
            ),
        ));
        

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'class' => 'btn btn-success',
                'id' => 'submitbutton',
            ),
        ));
    }
}
<?php

namespace Usuario\Form;

use Zend\Form\Form;
use Usuario\Controller\IndexController;


class ComentariosForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('comentarios');
        
        $this->setAttribute('method', 'post');
        
        
        $this->add(array(
            'name' => 'in_id',
            'type' => 'Hidden',
        ));
        
        $this->add(array(
            'name' => 'Ta_puntaje_in_id',
            'type' => 'Hidden',
            'attributes' => array( 
                'id'   => 'Ta_puntaje_in_id',)
        ));
        
        $this->add(array(
            'name' => 'Ta_plato_in_id',
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
            'validators' => array( 
                array( 
                    'name' => 'EmailAddress', 
                    'options' => array( 
                        'messages' => array( 
                            \Zend\Validator\EmailAddress::INVALID_FORMAT => 'Email address format is invalid' 
                        ) 
                    ) 
                ) 
            )         
        ));
                          $this->add(array(
            'name' => 'tx_descripcion',
            'type' => 'Textarea',
            'attributes' => array(               
                'class' => 'span11',
                'id'   => 'tx_descripcion',
                'placeholder'=>'Ingrese descripcion',
                'colls'=>40,
                'rows'=>4
            ),
            'options' => array(
                'label' => 'Descripcion',
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

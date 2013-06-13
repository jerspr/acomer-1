<?php

namespace Usuario\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\Request;
use Zend\View\Model\JsonModel;
use Zend\Json\Json;
use Usuario\Model\Usuario;          // <-- Add this import
use Usuario\Form\UsuarioForm;       // <-- Add this import

class IndexController extends AbstractActionController
{
  protected $usuarioTable;
    public function indexAction()
    {

        //return array();
        //retorna la vista nueva forma oo
        //$this->view->data='hola mundo';
       
       // var_dump($var);exit;
        return new ViewModel(array(
            'usuarios' => $this->getUsuarioTable()->fetchAll(),
            //'data'=>'Hola'    
        ));
    }
    public function agregarusuarioAction()
    {
        $form = new UsuarioForm();
        $form->get('submit')->setValue('Add');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $usuario = new Usuario();
            $form->setInputFilter($usuario->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $usuario->exchangeArray($form->getData());
                $this->getUsuarioTable()->guardarUsuario($usuario);
                return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/usuario/index/rese');      
            }
        }
        return array('form' => $form);
    }
    
    
     public function editarusuarioAction()
     
    {
        $id = (int) $this->params()->fromRoute('in_id', 0);
        //var_dump($id);exit;
        if (!$id) {
           return $this->redirect()->toUrl($this->
            getRequest()->getBaseUrl().'/usuario/index/agregarusuario');  
        }
        try {
            $usuario = $this->getUsuarioTable()->getUsuario($id);
            //var_dump($usuario);exit;
        }
        catch (\Exception $ex) {
            return $this->redirect()->toUrl($this->
            getRequest()->getBaseUrl().'/usuario/index/rese'); 
        }
        $form  = new UsuarioForm();
        $form->bind($usuario);
        $form->get('submit')->setAttribute('value', 'Edit');
        $request = $this->getRequest();      
      if ($request->isPost()) {
            //$usuario = new Usuario();
            $form->setInputFilter($usuario->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $usuario->exchangeArray($form->getData());
                $this->getUsuarioTable()->guardarUsuario($usuario);
                return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/usuario/index/rese');      
            }
        }

        return array(
            'in_id' => $id,
            'form' => $form,
        );
    }
    public function reseAction()
    {
      
        $array = array('hola'=>'LISTADO DE USUARIOS',
                        'yea' => $this->getUsuarioTable()->todosUsuarios(),);
       return new ViewModel($array);
    }

    public function fooAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /module-specific-root/skeleton/foo
        return array();
    }

    public function listarAction(){
        //echo 'holla mundddo';exit;
        $request = $this->getRequest();
         if ($request->isPost()) {
       //$datos=$request->post()->toArray();
       $datos=$this->params()->fromPost('texto');
       $nom=$this->params()->fromPost('listado');
       //$rol=$this->params()->fromPost('rol');
       
       $tipo=$nom;//(isset($nom))?$nom:$rol;
       // var_dump($tipo);exit;

       //$this->redirect()->toUrl('http://zf2.isg.com:81/usuario/index');

      $val=$this->getUsuarioTable()->buscarUsuario($datos,$tipo);

              return new ViewModel(array(
            'lista' => $val  
        ));
      //echo  $this->_request->getParam('texto');exit;
    }
  }
  //retorna json 

      public function jsonlistarAction(){
        
        //$request = $this->getRequest();
        $datos=$this->getUsuarioTable()->listar();
         //var_dump(Json::encode(array('datos'=>$datos)));exit; 
        echo Json::encode($datos);
        exit();
        
        
      //  $result = new JsonModel($datos);
       // echo $result;//var_dump($result);
 //return $result;
         //var_dump($json);exit;
       //  echo $json;
         //return new ViewModel(array('lista'=>$json));
         //var_dump($result);exit;
        // return $result;
  
         /*if ($request->isPost()) {
          //$datos=$this->params()->fromPost('texto');
          $datos=$this->getUsuarioTable()->listar();
         $result = Json::encode(array('datos'=>$datos));
        var_dump($result);exit;
      return $result;

    }*/
  }
  //obitenen el estado de la bd
  public function jsonestadoAction(){
          
        $datos=$this->getUsuarioTable()->estado();
        echo Json::encode($datos);
        exit();
  }

  public function eliminarusuAction(){
      $id=$this->params()->fromQuery('id');
      $this->getUsuarioTable()->deleteUsuario((int)$id);
      $this->redirect()->toUrl('/usuario/index');
  }
  
  public function cambiaestadoAction(){
      $id=$this->params()->fromQuery('id');
      $estado=$this->params()->fromQuery('estado');
      $this->getUsuarioTable()->estadoUsuario((int)$id,$estado);
      $this->redirect()->toUrl('/usuario/index');
  }
    public function listarvariosAction(){
      $datos=$this->getUsuarioTable()->listar2();
      var_dump($datos);exit;
    }
    //imprimer con roles desde sql del zend
    public function moreAction(){

        $datos=$this->getUsuarioTable()->moretablas();
        
    }

    public function obtonerjoinAction(){
      $id=$this->params()->fromQuery('id');
      //var_dump($id);exit;
      $datos=$this->getUsuarioTable()->getAlbum($id);
      var_dump($datos);exit;
      
    }


     public function getUsuarioTable()
    {
        if (!$this->usuarioTable) {
            $sm = $this->getServiceLocator();
            $this->usuarioTable = $sm->get('Usuario\Model\UsuarioTable');
        }
        return $this->usuarioTable;
    }




}

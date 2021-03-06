<?php

namespace Usuario\Controller;


use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail as SendmailTransport;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\Request;
use Zend\View\Model\JsonModel;
use Zend\Json\Json;
use Usuario\Model\Cometarios;          // <-- Add this import
use Usuario\Form\UsuarioForm;       // <-- Add this import
use Usuario\Form\ClienteForm;  
use Usuario\Model\CometariosTable; 
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;


class ComentariosController extends AbstractActionController
{
  protected $comentariosTable;
  public $dbAdapter;
    public function indexAction()
    {
        $filtrar = $this->params()->fromPost('submit'); 
        $datos = $this->params()->fromPost('texto');
        $estado = $this->params()->fromPost('estado');
        $puntaje = $this->params()->fromPost('puntaje');
         if (isset($filtrar)) {
            $comentarios = $this->getComentariosTable()->buscarComentario($datos,$estado,$puntaje);
        }
        else {
            $comentarios = $this->getComentariosTable()->fetchAll();
        }
        return array(
          'comentarios' => $comentarios,
            'puntaje' =>$this-> puntaje()
        );
    }
    
    public function agregarcomentariosAction(){
        $form=new \Usuario\Form\ComentariosForm();
        
             $form->get('submit')->setValue('Agregar');
        $request = $this->getRequest();
        
        if ($request->isPost()) {
//            $album = new Album();
//            $form->setInputFilter($album->getInputFilter());
//            $form->setData($request->getPost());
            $datos=$this->getRequest()->getPost()->toArray();
//            var_dump($datos);exit;
            $form->setData($datos);
            var_dump($form->isValid($datos));
            if ($form->isValid($datos)) {
//                $album->exchangeArray($form->getData());
               
                $this->getComentariosTable()->agregarComentario($datos); 
           
//                return $this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/platos'); 
            }
        }
        
        return array('form'=>$form);
        
    }
    
    public function getComentariosTable() {
        if (!$this->comentariosTable) {
            $s = $this->getServiceLocator();
            $this->comentariosTable = $s->get('Usuario\Model\ComentariosTable');
        }
        return $this->comentariosTable;
    }
    
    public function cambiaestadoAction() {
              $id = $this->params()->fromQuery('id');
              $estado = $this->params()->fromQuery('estado');
              $this->getComentariosTable()->estadoComentario((int) $id, $estado);
              $this->redirect()->toUrl('/usuario/comentarios/index');
    }
    
   public function mensajecomentarioAction()
            
    {
        $va_email = $this->params()->fromRoute('va_nombre_cliente', 0);
        $va_nombre_cliente = $this->params()->fromRoute('va_email',0);
        $bodyHtml='<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml">
                                               <head>
                                               <meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
                                               </head>
                                               <body>
                                                    <div style="color: #7D7D7D"><br />
                                                     Hola <strong style="color:#133088; font-weight: bold;">'.utf8_decode($va_nombre_cliente).'</strong><br />
                                                     <br />Tu cuenta comentario ha sido eliminado por ser inapropiado<br/><br/>
                                                     <br /><br /><hr /><br />Cordialmente,<br /><span style="color:#000; font-size: 18px; margin-top:8px;">El Equipo de Tuplato.com</span><br /><br />
                                                     </div>
                                               </body>
                                               </html>';
        
        $message = new Message();
        $message->addTo($va_email, $va_nombre_cliente)
        ->setFrom('no-reply@listadelsabor.pe)', 'listadelsabor.com')
        ->setSubject('Moderacion de comentario de listadelsabor.com')
        ->setBody($bodyHtml);
        $transport = new SendmailTransport();
        $transport->send($message);
        $this->redirect()->toUrl('/usuario/comentarios/index');

      }
    
     public function eliminarcomentarioAction() {
        $id = $this->params()->fromPost('id');
        $this->getComentariosTable()->deleteComentario((int) $id);
        $this->redirect()->toUrl('/usuario/comentarios/index');
    }

      public function puntaje()
    {   $this->dbAdapter =$this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $adapter = $this->dbAdapter;
        $sql = new Sql($adapter);
        $select = $sql->select()
            ->from('ta_puntaje');
            $selectString = $sql->getSqlStringForSqlObject($select);
            $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
            return $results;
            
     }
        public function comentariosexcelAction(){
          if (empty($_GET["estado"])and empty($_GET["puntaje"])and empty($_GET["texto"]) )
                {
               $comentarios = $this->getComentariosTable()->fetchAll();
                }
          else {
                $datos=$_GET["texto"];
                $estado = $_GET["estado"];
                $puntaje =$_GET["puntaje"];
                $comentarios = $this->getComentariosTable()->buscarComentario($datos,$estado,$puntaje);  
                }
                $view =new ViewModel();
        $view->setTerminal(true);
         $view->setVariables(array(
          'comentarios' => $comentarios,
           'puntaje' =>$this-> puntaje()
        ));
        return $view;
        
       /*return array(
          'comentarios' => $comentarios,
           'puntaje' =>$this-> puntaje()
        );*/
    }
}

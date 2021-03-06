<?php
namespace Local\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;

use Zend\Db\Adapter\Platform\PlatformInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Adapter;

use Zend\Db\Adapter\Platform;

class LocalTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    public function fetchAll(){
        $resultSet = $this->tableGateway->select();

        return $resultSet; 
    }
    
    public function listar($consulta = null){//,$id
//            $rowset = $this->tableGateway->select(function (Select $select) {           
//            $select->join(array('r'=>'ta_restaurante'),'ta_restaurante_in_id=r.in_id')
//                   ->join(array('u'=>'ta_ubigeo'),'ta_ubigeo_in_id=u.in_id',array('ch_pais','ch_departamento','ch_provincia','ch_distrito'))    
//                   ->where('ta_restaurante_in_id=r.in_id');
//             });        
              $select = $this->tableGateway->getSql()->select()
             ->join(array('r'=>'ta_restaurante'),'ta_restaurante_in_id=r.in_id',array('va_nombre'))
             ->join(array('u'=>'ta_ubigeo'),'ta_ubigeo_in_id=u.in_id',array('ch_pais','ch_departamento','ch_provincia','ch_distrito'))
              ->where('(r.in_id LIKE "%'.$consulta.'%") OR (r.va_nombre LIKE "%'.$consulta.'%") OR (u.ch_distrito LIKE "%'.$consulta.'%")');//OR (ta_restaurante_in_id LIKE "%'.$consulta.'%") OR (ta_ubigeo_in_id LIKE "%'.$consulta.'%")
              //->where(array('r.in_id'=>$id));//('ta_restaurante_in_id='.'1');//r.in_id
              $selectString = $this->tableGateway->getSql()->getSqlStringForSqlObject($select);
            $adapter=$this->tableGateway->getAdapter();
            $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
 
//             $array=array();
//             foreach($results as $result){
//                 $array[]=$result;
//             }
//             
//    var_dump($array);exit;
      return $results;
      
    }
    
    public function editarLocal($local,$id){
//        var_dump($local);exit;
        //$adapter=$this->tableGateway->select();
          $pais=$local['pais'];
          $departamento=$local['departamento'];
          $provincia=$local['provincia'];
          $distrito=$local['distrito'];
          
           $adapter=$this->tableGateway->getAdapter();
             $sql = new Sql($adapter);
          $idubigeo=$sql->select()->from('ta_ubigeo')
                  ->columns(array('in_id'))
                  ->where(array('in_idpais'=>$pais,'in_iddep'=>$departamento,'in_idprov'=>$provincia,'in_iddis'=>$distrito));
          $selectString0 = $this->tableGateway->getSql()->getSqlStringForSqlObject($idubigeo);

            $result = $adapter->query($selectString0, $adapter::QUERY_MODE_EXECUTE);
            $convertir=$result->toArray();
            
         $data = array(
           'va_telefono'         => $local['va_telefono'],
           'va_horario'   => $local['va_horario'],
           'de_latitud'            => $local['de_latitud'],
           'de_longitud'         => $local['de_longitud'],
           'va_rango_precio'            => $local['va_rango_precio'],  
           'va_horario_opcional'  => $local['va_horario_opcional'],
            'va_direccion' => $local['va_direccion'],
           //'ta_restaurante_in_id' => $local['ta_restaurante_in_id'],
            'ta_ubigeo_in_id' => $convertir[0]['in_id']   
           
        );
         //$id=(int)$local['in_id'];
//         print_r($data);
//         var_dump($id);Exit;
//        
//        $this->tableGateway->update($data,array('in_id'=> $id));//array('in_id='=>$id)
        
            $idupda=$sql
                  ->update('ta_local')->set($data)
                  ->where(array('in_id'=>$id));
          $selectString3 = $this->tableGateway->getSql()->getSqlStringForSqlObject($idupda);
//          var_dump($selectString3);exit;
            $result3 = $adapter->query($selectString3, $adapter::QUERY_MODE_EXECUTE);
            
    
            
//            foreach($servicio as $key=>$value){
//                
//             $insert = $this->tableGateway->getSql()->insert()->into('ta_local_has_ta_servicio_local')
//                    ->values(array('ta_local_in_id'=>$idlocal,'ta_servicio_local_in_id'=>$value));
//            $selectString2 = $this->tableGateway->getSql()->getSqlStringForSqlObject($insert);
//            $adapter=$this->tableGateway->getAdapter();
//            $result = $adapter->query($selectString2, $adapter::QUERY_MODE_EXECUTE);
//            }
            
    }
    
    public function eliminarLocal($id){
        
        $this->tableGateway->delete(array('in_id' => $id));
 
    }
    
    public function guardarLocal(Local $local, $servicio){
        //var_dump('hola');exit;
//         var_dump($servicio);exit;
          $pais=$local->pais;
          $departamento=$local->departamento;
          $provincia=$local->provincia;
          $distrito=$local->distrito;
          
           $adapter=$this->tableGateway->getAdapter();
             $sql = new Sql($adapter);      
          $idubigeo=$sql->select()->from('ta_ubigeo')
                  ->columns(array('in_id'))
                  ->where(array('in_idpais'=>$pais,'in_iddep'=>$departamento,'in_idprov'=>$provincia,'in_iddis'=>$distrito));
          $selectString0 = $this->tableGateway->getSql()->getSqlStringForSqlObject($idubigeo);

            $result = $adapter->query($selectString0, $adapter::QUERY_MODE_EXECUTE);
            $convertir=$result->toArray();

          $data = array(
           'va_telefono'         => $local->va_telefono,
           'va_horario'   => $local->va_horario,
           'de_latitud'            => $local->de_latitud,
           'de_longitud'         => $local->de_longitud,
           'va_rango_precio'            => $local->va_rango_precio,  
           'va_horario_opcional'  => $local->va_horario_opcional,
            'va_direccion' => $local->va_direccion,
           'ta_restaurante_in_id' => $local->ta_restaurante_in_id,
            'ta_ubigeo_in_id' => $convertir[0]['in_id']//$local->ta_ubigeo_in_id
                    
        );


//          foreach($data as $index=>$valor){
//                if(empty($data[$index])){
//                    $data[$index]=1;
//                }
//            }
            

        $id = (int)$local->in_id;

        if ($id == 0) {
            
            
//            $inservicio = $this->tableGateway->getSql()->insert()->into('ta_servicio_local')
//                    ->values(array('va_nombre'=>$servicio));
//            $selectString = $this->tableGateway->getSql()->getSqlStringForSqlObject($inservicio);
//            $adapter=$this->tableGateway->getAdapter();
//            $resultserv = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
//            
//            $idserv=$this->tableGateway->getLastInsertValue();
           
          //   var_dump($servicio);exit;
            $this->tableGateway->insert($data);
            $idlocal=$this->tableGateway->getLastInsertValue();
             //var_dump($idlocal);exit;
            foreach($servicio as $key=>$value){
                
             $insert = $this->tableGateway->getSql()->insert()->into('ta_local_has_ta_servicio_local')
                    ->values(array('ta_local_in_id'=>$idlocal,'ta_servicio_local_in_id'=>$value));
            $selectString2 = $this->tableGateway->getSql()->getSqlStringForSqlObject($insert);
            $adapter=$this->tableGateway->getAdapter();
            $result = $adapter->query($selectString2, $adapter::QUERY_MODE_EXECUTE);
            }

            
            
            
            
            
        } else {
//            if ($this->getRestaurante($id)) {
                
                $this->tableGateway->update($data, array('in_id' => $id));
                
                
//            } else {
//                throw new \Exception('no existe el usuario');
//            }
        }
    }
    
    
        public function getLocal($id)
    {
        //$id  = (int) $id;
//        $rowset = $this->tableGateway->select(array('in_id' => $id));
//        $row = $rowset->current();
//        if (!$row) {
//            throw new \Exception("Could not find row $id");
//        }
//        return $row;
        
        
        
            $select = $this->tableGateway->getSql()->select()
             ->join(array('u'=>'ta_ubigeo'),'ta_ubigeo_in_id=u.in_id',array('in_idpais','in_iddep','in_idprov','in_iddis'))
//             ->join(array('t'=>'ta_local_has_ta_servicio_local'),'ta_local.in_id=t.ta_local_in_id',array('ta_servicio_local_in_id'))       
              ->where(array('ta_local.in_id'=>$id));//('ta_restaurante_in_id='.'1');//r.in_id
              $selectString = $this->tableGateway->getSql()->getSqlStringForSqlObject($select);
//              var_dump($selectString);exit;
            $adapter=$this->tableGateway->getAdapter();
            $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
            //$results->current();
//           $array=array();
//             foreach($results as $result){
//                 $array[]=$result;
//             }
//            var_dump($array);exit;
            
            return $results->current();
    }
    
          public function getServiciosId($id){
           $select = $this->tableGateway->getSql()->select()
               ->columns(array('in_id'))
            // ->join(array('u'=>'ta_ubigeo'),'ta_ubigeo_in_id=u.in_id',array('in_idpais','in_iddep','in_idprov','in_iddis'))
             ->join(array('t'=>'ta_local_has_ta_servicio_local'),'ta_local.in_id=t.ta_local_in_id',array('ta_servicio_local_in_id'))       
              ->where(array('ta_local.in_id'=>$id));
              $selectString = $this->tableGateway->getSql()->getSqlStringForSqlObject($select);
//              var_dump($selectString);exit;
            $adapter=$this->tableGateway->getAdapter();
            $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
            
//          $array=array();
//             foreach($results as $result){
//                 $array[]=$result;
//             }
////            var_dump($array);exit;
            
            return $results->toArray();
      }
    
}

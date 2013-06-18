<?php
namespace Restaurante\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Platform\PlatformInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Platform;

class RestauranteTable
{
   

     protected $tableGateway;
    

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
      
    }
   

    public function fetchAll()
    {
 
      $adapter = $this->tableGateway->getAdapter();
        $sql = new Sql($adapter);
        $select = $sql->select()
                ->from(array('f' => 'ta_restaurante'))
                ->join(array('b' => 'ta_tipo_comida'), 'f.Ta_tipo_comida_in_id=b.in_id', array('va_nombre_tipo'))//,array('va_nombre_rol'))
                ->where(array('f.Ta_tipo_comida_in_id=b.in_id'));           
        $selectString = $sql->getSqlStringForSqlObject($select);
        $resultSet = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
        return $resultSet;
    }
     public function getRestaurante($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('in_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    public function guardarRestaurante(Restaurante $restaurante)
    {
        $data = array(
           'va_nombre'         => $restaurante->va_nombre,
           'va_razon_social'   => $restaurante->va_razon_social,
           'va_web'            => $restaurante->va_web,
           'va_imagen'         => $restaurante->va_imagen,
           'va_ruc'            => $restaurante->va_ruc,  
       'Ta_tipo_comida_in_id'  => $restaurante->Ta_tipo_comida_in_id  
        );

        $id = (int)$restaurante->in_id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getRestaurante($id)) {
                $this->tableGateway->update($data, array('in_id' => $id));
            } else {
                throw new \Exception('no existe el usuario');
            }
        }
    }

     public function buscarRestaurante($datos,$comida,$estado){
        $adapter=$this->tableGateway->getAdapter();
           $sql = new Sql($adapter);
        
           if($comida=='' and $estado == ''){

             $select = $sql->select()
            ->from(array('f' => 'ta_restaurante')) 
            ->join(array('b' => 'ta_tipo_comida'),'f.Ta_tipo_comida_in_id = b.in_id',array('va_nombre_tipo'))
           ->where(array('f.va_nombre'=>$datos));
           }
           if($datos=='' and $estado == ''){

             $select = $sql->select()
            ->from(array('f' => 'ta_restaurante')) 
            ->join(array('b' => 'ta_tipo_comida'),'f.Ta_tipo_comida_in_id = b.in_id',array('va_nombre_tipo'))
           ->where(array('f.Ta_tipo_comida_in_id'=>$comida));
           }
         if($datos=='' and $comida == ''){

             $select = $sql->select()
            ->from(array('f' => 'ta_restaurante')) 
            ->join(array('b' => 'ta_tipo_comida'),'f.Ta_tipo_comida_in_id = b.in_id',array('va_nombre_tipo'))
           ->where(array('f.en_estado'=>$estado));
           }
            $selectString = $sql->getSqlStringForSqlObject($select);
            $results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
            $rowset = $results;
         if (!$rowset) {
            throw new \Exception("No hay data");
        }
       
      
        return $rowset;
    }

      
       
 
        public function estadoRestaurante($id,$estado){
                        $data = array(
                            'en_estado' => $estado,
                         );
                 $this->tableGateway->update($data, array('in_id' => $id));
            }

}
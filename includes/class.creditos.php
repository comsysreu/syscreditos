<?php
require_once ("config2.php");
require_once ("class.sqlconection.php");

Class creditos extends SQLConection {
      public function __construct() {
            parent::__construct("creditos");
            $this->fields = array (
                 array('private','idcredito',"''"),
                 array('public','idcliente',"Cliente"),
                 array('public','idusuario',"Usuario"),
                 array('public','cobrador',"Cobrador"),
                 array('public','fechainicio',"Fecha Inicio"),
                 array('public','fechafin',"Fecha Fin"),
                 array('public','idplan',"Plan"),
                 array('public','idmonto',"Monto"),
                 array('public','montointeres',"Monto Interes"),
                 array('public','saldocapital',"Saldo Capital"),
                 array('public','saldointeres',"Saldo Interes"),
                 array('public','cuotadiaria',"Cuota Diaria"),
                 array('public','estadoanular',"Anulado"),
                 array('public','estado',"Estado"),
                 array('public','fechaultimopago',"fechaultimopago")
                );           
      }      
} 
?>
<?php
require_once ("config2.php");
require_once ("class.sqlconection.php");

Class detallecreditos extends SQLConection {
      public function __construct() {
            parent::__construct("detallecreditos");
            $this->fields = array (
                 array('public','idcredito',"''"),
                 array('public','fecha',"Fecha"),
                 array('public','fechapago',"Fecha de Pago"),
                 array('public','abonocapital',"Abono Capital"),
                 array('public','abonointeres',"Abono Interes"),
                 array('public','saldocapital',"Saldo Capital"),
                 array('public','saldointeres',"Saldo Interes"),
                 array('public','estado',"Estado"),
                 array('public','estadorenovacion',"Estado")
                );           
      }      
} 
?>
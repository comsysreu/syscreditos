<?php
require_once ("config2.php");
require_once ("class.sqlconection.php");

Class renovaciones extends SQLConection {
      public function __construct() {
            parent::__construct("renovaciones");
            $this->fields = array (
                 array('private','idrenovacion',"''"),
                 array('public','idcredito',"Credito"),
                 array('public','saldoanterior',"Saldo Anterior"),
                 array('public','fecharenovacion',"Fecha Renovacion")
                );           
      }      
} 
?>
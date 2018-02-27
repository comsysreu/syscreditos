<?php
require_once ("config2.php");
require_once ("class.sqlconection.php");

Class montos extends SQLConection {
      public function __construct() {
            parent::__construct("montos");
            $this->fields = array (
                 array('private','idmonto',"''"),
                 array('public','montocapital',"Monto Capital")
                );           
      }      
} 
?>
<?php
require_once ("config2.php");
require_once ("class.sqlconection.php");

Class planes extends SQLConection {
      public function __construct() {
            parent::__construct("planes");
            $this->fields = array (
                 array('private','idplan',"''"),
                 array('public','nombreplan',"Plan"),
                 array('public','dias',"Dias"),
                 array('public','porcentajeinteres',"% Interes"),
                 array('public','estado',"Estado")
                );           
      }      
} 
?>
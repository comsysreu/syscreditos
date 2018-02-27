<?php
require_once ("config2.php");
require_once ("class.sqlconection.php");

Class tipousuarios extends SQLConection {
      public function __construct() {
            parent::__construct("tipousuarios");
            $this->fields = array (
                 array('private','idtipousuario',"''"),
                 array('public','nombre',"nombre")
                );           
      }      
} 
?>
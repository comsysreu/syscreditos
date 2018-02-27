<?php
require_once ("config2.php");
require_once ("class.sqlconection.php");

Class clientes extends SQLConection {
      public function __construct() {
            parent::__construct("clientes");
            $this->fields = array (
                 array('private','idcliente',"''"),
                 array('public','nombrecompleto',"Nombre"),
                 array('public','dpi',"DPI"),
                 array('public','telefono',"Telefono"),
                 array('public','direccion',"Dirección")
                );           
      }      
} 
?>
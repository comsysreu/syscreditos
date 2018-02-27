<?php

require_once ("config2.php");
require_once ("class.sqlconection.php");

Class usuarios extends SQLConection {
      public function __construct() {
            parent::__construct("usuarios");
            $this->fields = array (
                 array('private','idUsuario',"''"),
                 array('public','nombre',"Nombre"),
                 array('public','nombreusuario',"nombreusuario"),
                 array('public','password',"password"),
                 array('public','estado',"estado"),
                 array('public','idtipousuario',"idtipousuario")
                );
            
      }
      
} 
?>
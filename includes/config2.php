<?php
$userdb="root";
$passwdb="";
$serverdb="localhost";
$namedb="bdcreditos";
mysql_connect("$serverdb","$userdb","$passwdb") or die ("ERROR: Imposible Conectarse al servidor");
mysql_query("SET NAMES 'utf8'");
mysql_select_db ($namedb);
date_default_timezone_set('America/Guatemala');
error_reporting(0);
?>
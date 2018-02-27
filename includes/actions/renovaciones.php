<?php
session_start();
include("../class.renovaciones.php");
include("../class.creditos.php");
include("../class.detallecreditos.php");

$a = $_GET['a'];

if($a=="renovaciones"){
	$hoy = date("Y-m-d");
	$saldo = $_REQUEST['saldo'];
	$idcredito = $_REQUEST['idcre'];
	$idcliente = $_REQUEST['idcli'];
	
	$detallecreditos = new detallecreditos();
	$creditos = new creditos();
	$renovaciones = new renovaciones();
	$reg_Detalle = $detallecreditos->sql("UPDATE detallecreditos SET fechapago = '".$hoy."', estado = 0, estadorenovacion = 1  WHERE (idcredito = $idcredito) AND (estado = 1)");
	$reg_Renovacion = $renovaciones->insertRecord(array($idcredito,$saldo,$hoy));
	$reg_creditos = $creditos->sql("UPDATE creditos SET estado = 0 WHERE (idcredito = $idcredito) AND (estado = 1)");
	echo"<script>setTimeout(\"$('#divmodal4').modal('hide'); \",0); $('#divmodal2').load('../includes/actions/nuevocredito.php?a=nuevo&saldo=$saldo&idcli=$idcliente').modal('show'); </script>";

}
?>
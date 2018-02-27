<?php
session_start();
include("../class.clientes.php");
include("../class.usuarios.php");
include("../class.planes.php");
include("../class.creditos.php");
include("../class.detallecreditos.php");

$a = $_GET['a'];
$fechaingresada = $_GET['fecha'];


if($a=="tablaprincipal"){
	$clientes = new clientes;
	$registros = $clientes->sql("SELECT
		cl.idcliente as idcliente,
		cr.idcredito as idcredito,
		cl.nombrecompleto as nombre,
		cr.cuotadiaria as cuotadiaria,
		count(dc.idcredito) as cantidadcuotas
		FROM clientes cl
		INNER JOIN creditos cr ON cr.idcliente = cl.idcliente
		INNER JOIN detallecreditos dc ON dc.idcredito = cr.idcredito
		WHERE dc.fechapago = '$fechaingresada'  
		GROUP BY dc.idcredito");
	echo "
	<table cellpadding='0' cellspacing='0' border='0' class='table table-striped table-bordered' id='tablaUsuarios'>
		<thead>
			<tr>	
		     <th>#</th>
		     <th>Cliente</th>		     
		     <th>Cantidad de abonos</th>
		     <th>Monto abonado</th>
		     <th>Acciones</th>
			</tr>
		</thead>
		<tbody>";
		
		    $lim=0;
		    
		    foreach($registros as $filas => $campo) {
		    	if($campo['idusuario'] != 1){
		    	$lim++;
				echo"<tr >";
				echo"<td>".$lim."</td>";
				echo"<td>".$campo['nombre']."</td>";
				echo"<td><center>Q.".number_format($campo['cuotadiaria'] * $campo['cantidadcuotas'],2,".",",")."</center></td>";
				echo"<td><center>".$campo['cantidadcuotas']."</center></td>";
				echo"<td><center><button class='btn btn-mini btn-danger' style='width:70px; height:20px' title='Crear Cliente' onclick=\" $('#divmodal').load('../includes/actions/historial.php?a=modalConfirmar&idcliente=".$campo['idcliente']."&idcredito=".$campo['idcredito']."&cuotas=".$campo['cantidadcuotas']."&fechaingresada=".$fechaingresada."').modal('show');\" type='button'>Eliminar</button></center></td>";
				echo"</tr>";
				}

		}
     echo"
		</tbody>
	</table>
     
      <script>
      $(document).ready(function() {

        $('a[rel=popover]').popover({placement: 'left'});
        $(\"a[rel='tooltip']\").tooltip(); //tooltips bootstrap
       

          $('#tablaUsuarios').dataTable({            
            \"sPaginationType\": \"bootstrap\",
            \"oLanguage\": {
						\"sLengthMenu\": \"_MENU_ registros por pagina\"   
						}         
          });
      });
      </script>
    ";

}
else
if($a=="deshacerCuotas"){
	$id = $_REQUEST['idcredito'];
	$fechaingresada = $_GET['fechaingresada'];
	$detallecreditos= new detallecreditos();
	$fecha = date('Y-m-d');
	
	if($detallecreditos->sqlTrans("UPDATE detallecreditos SET fechapago='0000-00-00', estado=1 WHERE fechapago = '".$fechaingresada."' and idcredito= $id"))
	{
		$detallecreditos->showMessage("Exito!", "Las cuotas abonadas fueron eliminadas");
		echo"<script>setTimeout(\"$('#divmodal').modal('hide'); location.reload();\",1500); </script>";
	}
	else
	{
		$detallecreditos->showMessage('Fallo','Error al editar el usuario','error');
	}
	
}
else
if($a=="modalConfirmar"){
	$cliente = new Clientes;
	$id=$_REQUEST['idcliente'];
	$idcredito=$_REQUEST['idcredito'];
	$cuotas=$_REQUEST['cuotas'];
	$fechaingresada=$_GET['fechaingresada'];
	$cliente= $cliente->getRecords("idcliente=$id");	
	echo"
	<!-- Formulario de Informacion -->
	<form class='form-horizontal' action='../includes/actions/historial.php?a=deshacerCuotas&idcredito=".$idcredito."&fechaingresada=".$fechaingresada."' id='formNewUsuario' method='post'>
			<div class='modal-header'>
				<a class='close' data-dismiss='modal'>×</a>
				<h3>Editar Usuario</h3>
			</div>
			
			<div class='modal-body'>
				<fieldset>
					<h4>¿Realmente quiere deshacer ".$cuotas." cuota(s) abonadas por el/la cliente ".$cliente[0]['nombrecompleto']."? </h4>
				</fieldset>
			</div>
			<div class='modal-footer'>
				<div class='response'></div>
				<input type='submit' class='btn btn-danger' value='Confirmar' id='btn_guardar' name='btn_guardar'  />
				<button type='button' onClick=\"$('#divmodal').modal('hide');\"  class='btn'>Salir</button>
			</div>
		</div>
	</form>
	<script>
	 	validator = $(\"#formNewUsuario\").validate();
	</script>
	";
}
?>
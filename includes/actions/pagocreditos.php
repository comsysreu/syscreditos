<?php
	session_start();
	include("../class.creditos.php");
	include("../class.usuarios.php");
	include("../class.detallecreditos.php");

	$a = $_GET['a'];
	$b = $_GET['b'];
	$usu=$_SESSION['idUsuario'];
	
	if($a=="tablaprincipalcreditos"){		
		$dato = $_SESSION['id'];

		$detallecreditos= new detallecreditos();
		$creditos = new creditos();
		
		$regcredito= $creditos->sql("SELECT cre.idcredito as idcredito, cre.cuotadiaria as cuota FROM creditos cre INNER JOIN clientes cli ON cli.idcliente = cre.idcliente WHERE ((cli.nombrecompleto='$dato' or cli.dpi='$dato')) and (cre.estado = 1)");
		$id_credito = $regcredito[0]['idcredito'];
		$cuota_diaria = $regcredito[0]['cuota'];
		
		$registros = $detallecreditos->sql("SELECT                                     
            dc.fecha as fecha,
            dc.saldocapital as capital, 
            dc.saldointeres as interes              
            FROM detallecreditos dc
            WHERE (dc.idcredito='$id_credito') and (dc.estado=1)");

		echo"
			<table cellpadding='0' cellspacing='0' border='0' class='table table-striped table-bordered' id='tabladetallecreditos'>
                <thead>
                    <tr class='success'>
                    	<td><strong>#</strong></td>
                        <td><strong>Fecha de Pago</strong></td>
                        <td><strong><center>Saldo Capital</center></strong></td>
                        <td><strong><center>Saldo Intéres</center></strong></td>
                        <td><strong><center>Total</center></strong></td>
                        <td><strong><center>Estado</center></strong></td>                      
                    </tr>
                </thead>
			<tbody>";
			$lim=0;		    
		    foreach($registros as $filas => $campo) {		    
		    	$lim++;
		    	$fecha=date("d-m-Y",strtotime($campo['fecha']));
		    	$capital= number_format($campo['capital'],2,"."," ");
		    	$interes= number_format($campo['interes'],2,"."," ");
		    	$total= number_format($campo['capital'] + $campo['interes'],2,"."," ");				
		    	echo"<tr >";
					echo"<td><center>".$lim."</center></td>";
					echo"<td><center>".$fecha."</center></td>";
					echo"<td><center>Q.".$capital."</center></td>";
					echo"<td><center>Q.".$interes."</center></td>";
					echo"<td><center>Q.".$total."</center></td>";
					echo"<td>";
						echo"<center><a rel='tooltip' title='Click para Registrar ABONO' onclick=\" $('#divmodal').load('../includes/actions/pagocreditos.php?a=Abonar&fecha=".$fecha."&id=".$id_credito."').modal('show'); \" ><span class='label label-important'>Pendiente de Abono</span></a></center>";
					echo"</td>";	
				echo"</tr>";
				echo"<script>$('#divtabladerecha').load('../includes/actions/pagocreditos.php?b=tabladerecha&id2=$id_credito&cd=".$cuota_diaria."');</script>";						
			} 
     		echo"
			</tbody>
		</table>     
      	<script>
      		$(document).ready(function() {
	        	$('a[rel=popover]').popover({placement: 'left'});
	        	$(\"a[rel='tooltip']\").tooltip(); //tooltips bootstrap
	        
	          	$('#tabladetallecreditos').dataTable({            
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
if($b=="tabladerecha"){

		$dato = $_REQUEST['id2'];
		$nombre = $_SESSION['id'];
		$diario = number_format($_REQUEST['cd'],2,"."," ");

		if ($dato != 0){
			echo"<table class='table table-bordered'>";
        	echo"<tr>
        		<center><button type='button' onclick=\" $('#divmodal').load('../includes/actions/pagocreditos.php?a=cuotasVarias&id2=$dato').modal('show');\"  class='btn btn-danger'>Ingresar cantidad de cuotas</button></center><br>
        		<td>
        		<center><strong>Nombre Cliente</strong>
        				<pre><h3 class='text-success' align='center'>".$nombre."</h3></pre>
        				<strong>Cuota Diaria: <br></strong>
        				<pre><h3 class='text-success' align='center'>Q.".$diario."</h3></pre>
        		</center>";
     		echo "<br>";
     		echo"<center><button type='button' onclick=\" $('#divmodal').load('../includes/actions/clientes.php?a=verdetallecreditos&id2=$dato').modal('show');\"  class='btn btn-info'>Ver Detalles de Estado de Cuenta</button></center><br>";
        	echo "</td>";
        	echo "</tr>";
        	echo "</table>";
    	}
	}
else
if($a=="ReportarAbono"){
		
		$id=$_REQUEST['id'];		
		$fechapago = date("Y-m-d");
		$fecha=date("Y-m-d",strtotime($_REQUEST['fechaabonar']));
		$fechaanterior = strtotime (  '-1 day' , strtotime ( $fecha ) ) ;
		$fechaanterior = date ( 'Y-m-d' , $fechaanterior );

				
		$detallecreditos= new detallecreditos();
		$creditos= new creditos();
		$objUsuarios = new usuarios();
		$activo=0;
		
		
			$regestado = $detallecreditos->sql("SELECT dc.idcredito as id, dc.estado as estado FROM detallecreditos dc  WHERE (fecha= \"$fechaanterior\") and (idcredito=$id)");
			$estado1 = $regestado[0]['estado'];

			if ($regestado[0]['estado']==0){
				if($detallecreditos->sqlTrans("UPDATE detallecreditos SET estado='".$activo."', fechapago='".$fechapago."' WHERE (idcredito=$id) and (fecha= \"$fecha\")")){
					$regDetalle = $detallecreditos-> sql("SELECT COUNT(dc.estado) AS cantidadpendientes FROM detallecreditos dc WHERE dc.idcredito = $id AND dc.estado = 1");
					if ($regDetalle[0]['cantidadpendientes']==0){
						$reg_creditos = $creditos->sql("UPDATE creditos SET estado = 0, fechaultimopago = '".$fechapago."' WHERE (idcredito = $id) AND (estado = 1)");
					}
					$detallecreditos->showMessage("Exito!", "Saldo Abonado");
					echo"<script>$('#divtablapagos').load('../includes/actions/pagocreditos.html?a=tablaprincipalcreditos'); setTimeout(\"$('#divmodal').modal('hide'); \",200);</script>";
				}
				else{
					$detallecreditos->showMessage('Fallo','Error al abonar','error');
				}
			}
			else{
				$detallecreditos->showMessage('Fallo','Debe de abonar el Saldo anterior','error');
			}
		
}
else
if($a=="Abonar"){
	$fecha=$_REQUEST['fecha'];
	$idcredito=$_REQUEST['id']; 	
	echo"
	<!-- Formulario de Informacion -->
	<form class='form-horizontal' action='../includes/actions/pagocreditos.php?a=ReportarAbono&fechaabonar=$fecha&id=$idcredito' id='formNewUser' method='post'>
		<div class='modal-header'>
			<a class='close' data-dismiss='modal'>×</a>
			<h3>Abonar a Saldo</h3>
		</div>
		<div class='modal-body'>
			<fieldset>
				<div class='control-group'>					
					<h3>Esta seguro de abonar al saldo...</h3>					
				</div>			
			</fieldset>
		</div>
		<div class='modal-footer'>
			<div class='response'></div>
				<input type='submit' class='btn btn-primary' value='Si' id='btn_guardar' name='btn_guardar'  />
				<button type='button' onClick=\"$('#divmodal').modal('hide');\"  class='btn'>No</button>
			</div>
		</div>
	</form>
	<script>
	 	validator = $(\"#formNewUser\").validate();
	</script>
	";
}
else
if($a=="cuotasVarias"){
	$idCredito = $_REQUEST['id2']; 	
	echo"
	<!-- Formulario de Informacion -->
	<form class='form-horizontal' action='../includes/actions/pagocreditos.php?a=ReportarVariasCuaotas&idcredito=$idCredito' id='formNewUser' method='post'>
		<div class='modal-header'>
			<a class='close' data-dismiss='modal'>×</a>
			<h3>Cantidad de cuotas por ABONAR</h3>
		</div>
		<div class='modal-body'>
			<fieldset>
				<div class='control-group'>
						<label class='control-label' for='cuotas'>Cantidad de cuotas: </label>
						<div class='controls'>
							<input class='input-xlarge required' autofocus min = 0 id='cuotasVarias' name='cuotasVarias'  type='number'>
						</div>
					</div>			
			</fieldset>
		</div>
		<div class='modal-footer'>
			<div class='response'></div>
				<input type='submit' class='btn btn-primary' value='Si' id='btn_guardar' name='btn_guardar'  />
				<button type='button' onClick=\"$('#divmodal').modal('hide');\"  class='btn'>No</button>
			</div>
		</div>
	</form>
	<script>
	 	validator = $(\"#formNewUser\").validate();
	</script>
	";
}
else
if($a=="ReportarVariasCuaotas"){
	$idCredito = $_REQUEST['idcredito'];
	$cantidadCuotas = $_POST['cuotasVarias'];

	$objDetalleCreditos = new detallecreditos();
	$objCreditos = new creditos();
	$regCreditos = $objCreditos->sql("SELECT cuotadiaria as cuotadiaria FROM creditos WHERE idcredito = $idCredito");

	$regDetalle = $objDetalleCreditos->sql("SELECT TRUNCATE(saldocapital,0) as saldocapital FROM detallecreditos WHERE idcredito = $idCredito and estado = 1 ORDER BY saldocapital DESC");
	$cuotasNoPagadas = count($regDetalle);

	$estado = 0;
	$fechapago = date("Y-m-d");
	if ($cuotasNoPagadas>=$cantidadCuotas){
		for($i=0; $i<=$cantidadCuotas-1; $i++){
			$cuota = $regDetalle[$i]['saldocapital'];
			$objDetalleCreditos->sqlTrans("UPDATE detallecreditos SET estado='".$estado."', fechapago='".$fechapago."' WHERE idcredito = $idCredito and TRUNCATE(SALDOCAPITAL,0)=$cuota");			
			if($regCreditos[0]['cuotadiaria']>$cuota) {
				$objCreditos->sqlTrans("UPDATE creditos SET estado=0, fechaultimopago = '".$fechapago."' WHERE idcredito=$idCredito");
			}
		}	
		echo"<script>$('#divtablapagos').load('../includes/actions/pagocreditos.html?a=tablaprincipalcreditos'); setTimeout(\"$('#divmodal').modal('hide'); \",800);</script>";
		$objDetalleCreditos->showMessage('Exito!','Se han abonado '.$cantidadCuotas.' cuotas ');
	}
	else{
		$objDetalleCreditos->showMessage('Fallo','La cantidad ingresada es mayor a las cuotas pendientes de abonar','error');
	}
}
?>

<?php
session_start();
include("../class.creditos.php");
include("../class.detallecreditos.php");
include("../class.clientes.php");
include("../class.planes.php");
include("../class.montos.php");
include("../class.usuarios.php");

$a = $_GET['a'];


if($a=="crearcredito")
{

	$detallecreditos= new detallecreditos();
	$creditos= new creditos();
	$clientes= new clientes();
	$planes= new planes();
	$montos= new montos();

	$saldoanterior = $_REQUEST['saldo'];
	$nombrecliente = $_POST['nombre'];
	$idusuario = $_SESSION['idUsuario'];
	$cobrador = $_POST['cobrador'];
	$fecha1 = $_POST['fecha'];
	$fecha2 = $_POST['fechafin'];
	$plan = $_POST['nombreplan'];
	$montocapital = $_POST['monto'];
	$montointeres = $_POST['interes'];
	$saldocapital = $_POST['monto'];
	$saldointeres = $_POST['interes'];
	$cuotadiaria = $_POST['cuota'];
	$cantidad = $_POST['cantidad'];

	$estadocredito	= 1;
	$estadoanular	= 0;

	$regnombre= $clientes->sql("SELECT idcliente, nombrecompleto  FROM clientes WHERE nombrecompleto = '$nombrecliente' LIMIT 1");
	$regplan= $planes->sql("SELECT idplan, dias, porcentajeinteres FROM planes WHERE nombreplan = '$plan' LIMIT 1");
	$regmonto= $montos->sql("SELECT idmonto FROM montos WHERE montocapital = '$montocapital' LIMIT 1");

	$idcliente = $regnombre[0]['idcliente'];
	$idplan = $regplan[0]['idplan'];
	$dias = $regplan[0]['dias'];
	$porcen = $regplan[0]['porcentajeinteres'];
	$idmonto = $regmonto[0]['idmonto'];

	$cuotainteres = $montointeres/$dias;
	$cuotasaldo = $montocapital/$dias;
	$montototal = $montocapital - $cuotadiaria;
	
	
		if ($regnombre[0]['nombrecompleto']==$nombrecliente){	
			$fechainicio=date("Y-m-d",strtotime($fecha1));
			$fechafinal=date("Y-m-d",strtotime($fecha2));

			if($creditos->insertRecord(array($idcliente,$idusuario,$cobrador,$fechainicio,$fechafinal,$idplan,$idmonto,$montointeres,$saldocapital,$saldointeres,$cuotadiaria,$estadoanular,$estadocredito))){	
			
				//obtener el ultimo id de la tabla creditos
				$regcreditos = mysql_query("SELECT MAX(idcredito) FROM creditos");
				if ($row = mysql_fetch_row($regcreditos)) {
					$idultimo = trim($row[0]);
				}		

				$capital = $saldocapital;
				$interes = $saldointeres;
				$abonocapital = 0;
				$abonointeres = 0;

				//ciclo for para la creación de cuotas
				//for($i=1; $i<=$cantidad+1; $i++ ){
				for($i=0; $i<=$cantidad; $i++ ){
					//$ii = $i-1;
					if ($i > 0){
						$estadocuota = 1;
					}
					else{
						$estadocuota = 0;
					}
					$fechadias = strtotime (  '+ '.$i.' day' , strtotime ( $fechainicio ) ) ;	
					//$fechadias = strtotime (  '+ '.$ii.' day' , strtotime ( $fechainicio ) ) ;
					$fechadias = date ( 'Y-m-j' , $fechadias );

					//insertar registro a tabla Detalle Credito
					//$detallecreditos->insertRecord(array($idultimo,$fechadias,$abonocapital,$abonointeres,$capital,$interes,$estadocuota));	
					if (date('N', strtotime($fechadias)) != 7){
						
						$detallecreditos->sqlTrans("INSERT INTO detallecreditos (idcredito, fecha, abonocapital, abonointeres, saldocapital, saldointeres, estado) VALUES ('$idultimo','$fechadias','$abonocapital','$abonointeres','$capital','$interes','$estadocuota')");
						$capital = $capital - $cuotasaldo; 
						$interes = $interes - $cuotainteres;
					}
					
				}	
				//$numero = date ( 'Y-m-j' , $fechadias );
				//echo"<script>alert('$numero,$i,$fechainicio,$fechafinal'); </script>";
				$creditos->showMessage("Exito!", "Crédito Creado");
				echo"<script>setTimeout(\"$('#divmodal2').modal('hide'); \",500); $('#divmodal').load('../includes/actions/nuevocredito.php?a=vistatotal&mt=$montototal&saldo=$saldoanterior&idcli=$idcliente').modal('show'); </script>";
		
			}
			else{
				$creditos->showMessage('Fallo','Error al crear crédito, verifique si no existe!','error');
			}
		}	
		else{
			$creditos->showMessage('Fallo','Cliente no existe, ingrese el cliente!','error');
		}

	
}
else
	if($a=="nuevo"){
	$montos = new montos();
	$usuarios = new usuarios();
    $clientes =new clientes();

    $idcliente = $_REQUEST['idcli'];
    $saldoanterior = $_REQUEST['saldo'];
    
    $regCliente = $clientes->sql("SELECT nombrecompleto FROM clientes WHERE idcliente = $idcliente");

    $registros = $montos->getRecords();
    $regUsuarios = $usuarios->getRecords();

    $fechaini = date('j-m-Y');

   	echo"
	<!-- Formulario de Informacion -->
	<form class='form-horizontal' action='../includes/actions/nuevocredito.php?a=crearcredito&saldo=$saldoanterior' id='formNewUser' method='post'>
			<div class='modal-header'>
				<a class='close' data-dismiss='modal'>×</a>
				<h3>Nuevo Préstamo</h3>
			</div>
			<div class='modal-body'>
				<fieldset>
					<div class='control-group'>
						<label class='control-label' for='nombre'>Nombre: </label>
						<div class='controls'>
							<input class='input-xlarge required' id='nombre' value='".$regCliente[0]['nombrecompleto']."' name='nombre' type='text' style='text-align:center' readonly>
						</div>
					</div>

					<div class='control-group'>
						<label class='control-label' for='plan'>Plan de Préstamo: </label>
						<div class='controls'>
							<input class='input-xlarge required' onclick=\" $('#divmodal3').load('../includes/actions/nuevocredito.php?a=tablaplanes').modal('show'); $('#divmodal2').modal('hide'); \" style='cursor:pointer' id='nombreplan' name='nombreplan'  type='text' placeholder='Click para seleccionar' readonly>
						</div>
					</div>

					<div class='control-group'>
						<label class='control-label' for='monto'>Monto: </label>
						<div class='controls'>
						<div class='input-append'>
							<select id='monto' class='input-large required' name='monto' onChange='multiplicar();'>
							<option value=''>Seleccione.. </option>"; 	
							foreach ($registros as $key => $campo) {
								echo"<option value='".$campo['montocapital']."'>".$campo['montocapital']."</option>";
							}
							echo"</select>
						<span class='add-on'>quetzales</span>
						</div>
						</div>
					</div>

					<div class='control-group'>
						<label class='control-label' for='interes'>Interés: </label>
						<div class='controls'>
						<div class='input-prepend input-append'>
						<span class='add-on'>Q.</span>
							<input class='input-small required'  id='interes' name='interes'  type='text' style='text-align:center' readonly>
						<span class='add-on'>.00</span>
						</div>
						</div>
					</div>

					<div class='control-group'>
						<label class='control-label' for='cuota'>Cuota Diaria: </label>
						<div class='controls'>
						<div class='input-prepend input-append'>
						<span class='add-on'>Q.</span>
							<input class='input-small required'   id='cuota' name='cuota'  type='text' style='text-align:center' readonly>
						<span class='add-on'>.00</span>
						</div>
						</div>
					</div>

					<div class='control-group'>
						<label class='control-label' for='fechaini'>Fecha Inicio: </label>
						<div class='controls'>
							<input class='datepicker input-medium required' id='fecha' value='".$fechaini."' name='fecha' type='text' placeholder='Click para seleccionar' style='text-align:center' readonly>
						</div>
					</div>

					<div class='control-group'>
						<label class='control-label' for='fechafin'>Fecha Fin: </label>
						<div class='controls'>
							<input class='input-medium required' name='fechafin' type='text' id='fechafin' style='text-align:center' readonly />
						</div>
					</div>
					<div class='control-group'>
						<label class='control-label' for='Cobrador'>Cobrador: </label>
						<div class='controls'>
							<select id='cobrador' class='input-xlarge required' name='cobrador'>
							<option value=''>Seleccione.. </option>"; 	
							foreach ($regUsuarios as $key => $dato) {
								if($dato['idtipousuario']==3){
											echo"<option value='".$dato['nombre']."'>".$dato['nombre']."</option>";
								}
							}
							echo"</select>
							<input type='text' id='dias' name='dias' >
							<input type='text' id='porcentajeinteres' name='porcentajeinteres' style='visibility:hidden'>
							<input type='text' id='cantidad' name='cantidad' >
						</div>
					</div>
					

				</fieldset>
			</div>
			<div class='modal-footer'>
				<div class='response'></div>
				<input type='submit' class='btn btn-primary' value='Guardar' id='btn_guardar' name='btn_guardar'  />
				<button type='button' onClick=\"$('#divmodal2').modal('hide');\"  class='btn'>Salir</button>
			</div>
		</div>
	</form>

	<script language=\"javascript\">
		function multiplicar()
		{
			monto = document.getElementById('monto').value;
			diass = document.getElementById('dias').value;
			porcentaje = document.getElementById('porcentajeinteres').value;

			interes = monto * porcentaje / 100;
			cuotadiaria = (parseInt(monto)+parseInt(interes))/diass;
							
			document.getElementById('interes').value = interes;
			document.getElementById('cuota').value = cuotadiaria;
		}
	</script>

	<script>
	 	validator = $(\"#formNewUser\").validate();
	 	$('.datepicker').datepicker({ dateFormat: \"yy-mm-dd\", dayNamesMin: [\"Do\", \"Lu\", \"Ma\", \"Mi\", \"Ju\", \"Vi\", \"Sa\"],yearRange: '".(date(Y)-100).":".(date(Y))."', 
	 		changeYear: true, monthNames: [\"Enero\",\"Febrero\",\"Marzo\",\"Abril\",\"Mayo\",\"Junio\",\"Julio\",\"Agosto\",\"Septiembre\",\"Octubre\",\"Noviembre\",\"Diciembre\"]});
	</script>

	<script>
	 	validator = $(\"#formNewUser\").validate();
	</script>
	";
}
else
if($a=="tablaplanes")
{
	

	$planes= new planes();
	$registros= $planes->getRecords("estado = 1");
	echo"
			
		

		<div class='modal-header'>
			<a class='close' data-dismiss='modal'>×</a>
			<h3>Planes de Préstamo</h3>
		</div>
		<div class='modal-body'>
			<fieldset>
			<table cellpadding='0' cellspacing='0' border='0' class='table table-striped table-bordered' id='tablaplanes'>
		<thead>
			<tr>	
		     <th>Nombre Plan</th>	
		     <th>Días</th>
		     <th>Porcentaje Interés</th>
		     <th>Acciones</th>
			</tr>
		</thead>
		<tbody>";
		
		    $lim=0;
		    $fecha = date('Y-m-d');
		    $domingo = date('N', strtotime($fecha));
		    foreach($registros as $filas => $campo) {
				$dias = $campo['dias'];
				echo"<tr >";
				echo"<td>".$campo['nombreplan']."</td>";
				echo"<td>".$campo['dias']." días"."</td>";
				echo"<td>".$campo['porcentajeinteres']." %"."</td>";
				$cantidad = 0;
				
				for($i=0; $i<=$dias; $i++)
				{					
					/*if ($domingo == 7){
						$cantidad = $cantidad+2;} 
					else{
						$cantidad++;
					}*/
					$fechafin = strtotime (  '+ '.$i.' day' , strtotime ( $fecha ) ) ;
					$fechafin = date ( 'Y-m-d' , $fechafin ); 
					//$domingo = date('N', strtotime($fechafin));							
					if (date('N', strtotime($fechafin)) == 7){
						$cantidad = $cantidad+2;} 
					else{
						$cantidad++;
					}
				}
				//$cantidad--;
				$fechafinal = strtotime (  '+ '.$cantidad.' day' , strtotime ( $fecha ) ) ;
				$fechafinal = date ( 'd-m-Y' , $fechafinal );

				/*if (date('N', strtotime($fechafinal)) == 7){
					$cantidad++;
					$fechafinal = strtotime (  '+ '.$cantidad.' day' , strtotime ( $fecha ) ) ;
					$fechafinal = date ( 'd-m-Y' , $fechafinal );
				}*/
				

				if (date('N', strtotime($fechafin)) == 7){
					$cantidad++;
					$fechafinal = strtotime (  '+ '.$cantidad.' day' , strtotime ( $fecha ) ) ;
					$fechafinal = date ( 'd-m-Y' , $fechafinal );
				}			
				echo"<td>";
					echo"<a rel='tooltip' title='Seleccionar' onclick=\" $('#nombreplan').val('".$campo['nombreplan']."'); $('#idplan').val('".$campo['idplan']."'); $('#fechafin').val('".$fechafinal."'); $('#dias').val('".$campo['dias']."'); $('#porcentajeinteres').val('".$campo['porcentajeinteres']."'); $('#cantidad').val('".$cantidad."'); $('#divmodal3').modal('hide'); $('#divmodal2').modal('show'); \" ><i class='icon-check'></i></a>";
				echo"</td>";
				echo"</tr>";
			
		}
     echo"
		</tbody>
	</table>
     
      <script>
      $(document).ready(function() {

        $('a[rel=popover]').popover({placement: 'left'});
        $(\"a[rel='tooltip']\").tooltip(); //tooltips bootstrap
       

          $('#tablaplanes').dataTable({            
            \"sPaginationType\": \"bootstrap\",
            \"oLanguage\": {
						\"sLengthMenu\": \"_MENU_ registros por pagina\"   
						}         
          });
      });
      </script>
					
				</fieldset>
			</div>
			<div class='modal-footer'>
				<div class='response'></div>
				<button type='button' onClick=\"$('#divmodal3').modal('hide'); $('#divmodal2').modal('show');\"  class='btn'>Salir</button>
			</div>
		</div>
	
	";
}
if($a=="vistatotal"){
	
	$clientes = new clientes();

	$nuevomonto=$_REQUEST['mt'];
	$saldoanterior=$_REQUEST['saldo'];
	$cliente=$_REQUEST['idcli'];
	$montototal = number_format($nuevomonto-$saldoanterior,2,".",",");

	$regnombre= $clientes->sql("SELECT nombrecompleto  FROM clientes WHERE idcliente = '$cliente' LIMIT 1");
		
	echo"
	<!-- Formulario de Informacion -->
	<form class='form-horizontal' action='' id='formNewUsuario' method='post'>
			<div class='modal-header'>
				<a class='close' data-dismiss='modal'>×</a>
				<h3>Monto a dar</h3>
			</div>
			<div class='modal-body'>
				<fieldset>
					<h3>Extender cheque a nombre de: ".$regnombre[0]['nombrecompleto']."</h3>
					<h3>Monto del Cheque: Q.".$montototal."</h3>
				</fieldset>
			</div>
			<div class='modal-footer'>
				<div class='response'></div>
				<button type='button' onClick=\"$('#divmodal').modal('hide');\"  class='btn'>Aceptar</button>
			</div>
		</div>
	</form>
	<script>
	 	validator = $(\"#formNewUsuario\").validate();
	</script>
	";

}

?>
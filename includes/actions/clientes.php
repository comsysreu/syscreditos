<?php
session_start();
include("../class.clientes.php");
include("../class.usuarios.php");
include("../class.planes.php");
include("../class.creditos.php");
include("../class.detallecreditos.php");

$a = $_GET['a'];
$usu=$_SESSION['idUsuario'];

if($a=="tablaprincipal"){
	$idusuario = $_SESSION['idtipousuario'];
	$clientes= new clientes();
	$creditos= new creditos();
	$detallecreditos= new detallecreditos();

	$registros = $creditos->sql("SELECT 
		cre.idcredito as idcredito,
		cli.nombrecompleto as nombrecompleto,
		cli.dpi as dpi,
		cli.telefono as telefono,
		cli.direccion as direccion,
		cre.cobrador as cobrador,
		pla.idplan as idplan,
		pla.nombreplan as nombreplan
		FROM creditos cre
		INNER JOIN clientes cli ON cre.idcliente = cli.idcliente
		INNER JOIN planes pla ON cre.idplan = pla.idplan WHERE cre.estado = 1");

	echo"
	<div class='btn-group' >
    	<a target='_blank' class='btn btn-primary btn-medium' href='../includes/actions/reporteador.php?a=reporteclientesconcredito'>Imprimir R/clientes</a><br>
	</div>
	<br>
	<br>
	<table cellpadding='0' cellspacing='0' border='0' class='table table-striped table-bordered' id='tablaClientes'>
		<thead>
			<tr>	
		    	<th>#</th>
		    	<th>Nombre de Cliente</th>		     
		    	<th>DPI</th>
		    	<th>Teléfono</th>
		    	<th>Dirección</th>
		    	<th>Cobrador</th>
		    	<th>Estado Cuenta</th>
		     	<th>Detalles</th>
			</tr>
		</thead>
		<tbody>";
		    $lim=0;
		    $fechahoy = date('Y-m-d');
		    foreach($registros as $filas => $campo){
				$id1 = $campo['idcredito'];
				$regDetalle = $detallecreditos-> sql("SELECT COUNT(dc.idcredito) AS idcredito FROM detallecreditos dc WHERE ((dc.idcredito = $id1) AND (dc.estado = 1)) AND (dc.fecha <= \"$fechahoy\")");

		    	$lim++;
				echo"<tr >";
					echo"<td>".$lim."</td>";
					echo"<td>".$campo['nombrecompleto']."</td>";
					echo"<td>".$campo['dpi']."</td>";
					echo"<td>".$campo['telefono']."</td>";
					echo"<td>".$campo['direccion']."</td>";
					echo"<td>".$campo['cobrador']."</td>";
					/*echo"<td>";
						echo"<center><a rel='tooltip' title='Click para Ver' onclick=\" $('#divmodal').load('../includes/actions/clientes.php?a=verplan&id=".$campo['idplan']."').modal('show'); \" ><span class='label label-info'>".$campo['nombreplan']."</span></a></center>";
					echo"</td>";*/
					echo"<td><center>";
					if ($regDetalle[0]['idcredito']==1 || $regDetalle[0]['idcredito']==0){
						echo"<center><button class='btn btn-mini btn-success' style='width:70px; height:20px' title='Crear Cliente' onclick=\" $('#divmodal').load('../includes/actions/clientes.php?a=verdeuda&id=$id1').modal('show');\" type='button'>Verde</button></center>";
						//echo"<center><a rel='tooltip' title='Click para Ver' onclick=\" $('#divmodal').load('../includes/actions/clientes.php?a=verplan&id=".$campo['idplan']."').modal('show'); \" ><span class='label label-info'>".$campo['nombreplan']."</span></a></center>";
					}
					else
						if ($regDetalle[0]['idcredito']>1 && $regDetalle[0]['idcredito']<=3){
							echo"<center><button class='btn btn-mini btn-warning' style='width:70px; height:20px' title='Crear Cliente' onclick=\" $('#divmodal').load('../includes/actions/clientes.php?a=verdeuda&id=$id1').modal('show');\" type='button'>Naranja</button></center>";
						}
						else{							
							echo"<center><button class='btn btn-mini btn-danger' style='width:70px; height:20px' title='Crear Cliente' onclick=\" $('#divmodal').load('../includes/actions/clientes.php?a=verdeuda&id=$id1').modal('show');\" type='button'>Rojo</button></center>";						
						}
					echo"</center></td>";	
					echo"<td><center>";
						echo"<a rel='tooltip' title='Ver Estado de Cuenta' onclick=\"$('#divmodal').modal('hide'); $('#divtablaprincipal').load('../includes/actions/clientes.html?a=estadodecuenta&idcliente=$id1') \" ><i class='icon-edit'>\n  \n</i></a>";
						if($idusuario==1 || $idusuario==2)
						{
							echo"<a rel='tooltip' title='Anular Crédito' onclick=\"$('#divmodal').load('../includes/actions/clientes.html?a=anular&idcredito=$id1').modal('show');\" >\n  \n<i class='icon-ban-circle'></i></a>";
							echo"<a rel='tooltip' title='Modificar Cliente' onclick=\"$('#divmodal').load('../includes/actions/clientes.html?a=modificarCliente&idcredito=$id1').modal('show');\" >\n  \n<i class='icon-pencil'></i></a>";			
						}
					echo"</center></td>";	
				echo"</tr>";				
		}
     echo"
		</tbody>
	</table>
	     
    <script>
    	$(document).ready(function() {

        $('a[rel=popover]').popover({placement: 'left'});
        $(\"a[rel='tooltip']\").tooltip(); //tooltips bootstrap
        
          $('#tablaClientes').dataTable({            
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
if($a=="createCliente")
{
	$creditos= new creditos();
	$clientes= new clientes();
	$nombre= $_POST['nombre'];
	$dpi= $_POST['dpi'];
	$telefono= $_POST['telefono'];
	$direccion= $_POST['direccion'];
	$estado=1;
	$longitud = strlen($dpi); 
	
    if ($longitud == 13) 
	{ 
		//Comprobar si ya existe el cliente
		$regClientes= $clientes->sql("SELECT cl.idcliente as idcliente, cl.nombrecompleto as nombrecompleto, cl.dpi as dpi  FROM clientes cl WHERE (cl.nombrecompleto = '$nombre') or (cl.dpi = '$dpi') LIMIT 1");
	    
	    if ($regClientes[0]['dpi'] == $dpi || $regClientes[0]['nombrecompleto'] == $nombre){
    			$idcliente = $regClientes[0]['idcliente'];

    			$regCreditos= $creditos->sql("SELECT cre.idcredito as idcredito, cre.idcliente as id_cliente FROM creditos cre WHERE (cre.idcliente = '$idcliente') and (cre.estado = 1) LIMIT 1");
    			if ($regCreditos[0]['id_cliente'] == $idcliente){
    				$idcredito = $regCreditos[0]['idcredito'];
					echo"<script>setTimeout(\"$('#divmodal').modal('hide'); \",500);$('#divmodal2').load('../includes/actions/clientes.php?a=renovacion&idcli=$idcliente&idcre=$idcredito').modal('show');  </script>";
    			}
    			else{
    				echo"<script>setTimeout(\"$('#divmodal').modal('hide'); \",500); $('#divmodal2').load('../includes/actions/nuevocredito.php?a=nuevo&idcli=$idcliente').modal('show'); </script>";	
    			}
    	}
    	else
    		if($clientes->insertRecord(array($nombre,$dpi,$telefono,$direccion)))
			{
				//Encontrar el Ultimo Cliente Creado
				$regclientes = mysql_query("SELECT MAX(idcliente) FROM clientes");
				if ($row = mysql_fetch_row($regclientes)) {
					$idultimo = trim($row[0]);
				}

				$clientes->showMessage("Exito!", "Cliente Creado");
				echo"<script>setTimeout(\"$('#divmodal').modal('hide'); \",500); $('#divmodal2').load('../includes/actions/nuevocredito.php?a=nuevo&idcli=$idultimo').modal('show'); </script>";
			}
			else{
				$clientes->showMessage('Fallo','Error al crear cliente, verifique si no existe!','error');
			}
	}
	else{
			$clientes->showMessage("Error!", "El Número de DPI de contener 13 números... ",'error');		
	}	
}
else
	if($a=="nuevocliente"){
    
	echo"
	<!-- Formulario de Informacion -->
	<form class='form-horizontal' action='../includes/actions/clientes.php?a=createCliente' id='formNewCliente' method='post'>
			<div class='modal-header'>
				<a class='close' data-dismiss='modal'>×</a>
				<h3>Nuevo Cliente</h3>
			</div>
			<div class='modal-body'>
				<fieldset>
					
					<div class='control-group'>
						<label class='control-label' for='dpi'>DPI: </label>
						<div class='controls'>
						<input type='text' id='dpi' placeholder='Escriba el DPI sin espacios...' autofocus list='browsers' name='dpi' on autocomplete='off' class='input-xlarge' required>
                        	<datalist id='browsers'>";
							$pa=mysql_query("SELECT dpi as dpi FROM clientes");				
                             	while($row=mysql_fetch_array($pa))
                                {
                                	echo '<option value="'.$row['dpi'].'">';
                                }
                             echo"
                            </datalist>  
						</div>
					</div>

					<div class='control-group'>
						<label class='control-label' for='nombre'>Nombre Completo: </label>
						<div class='controls'>
							<input class='input-xlarge required'  id='nombre' name='nombre'  type='text'>
						</div>
					</div>

					<div class='control-group'>
						<label class='control-label' for='telefono'>Teléfono: </label>
						<div class='controls'>
							<input class='input-xlarge required' placeholder='Sin espacios...' id='telefono' name='telefono'  type='text'>
						</div>
					</div>

					<div class='control-group'>
						<label class='control-label' for='direccion'>Dirección: </label>
						<div class='controls'>
							<input class='input-xlarge required'   id='direccion' name='direccion'  type='text'>
						</div>
					</div>

				</fieldset>
			</div>
		

			<div class='modal-footer'>
				<div class='response'></div>
				<input type='submit' class='btn btn-primary' value='Guardar' id='btn_guardar' name='btn_guardar'  />
				<button type='button' onClick=\"$('#divmodalNuevoCliente').modal('hide');$('#divmodal2').modal('hide');\"  class='btn'>Salir</button>
			</div>
		</div>
	</form>
	<script>
	 	validator = $(\"#formNewCliente\").validate();
	</script>
	";
}
else
if($a=="editCliente"){
	$idCliente = $_REQUEST['idcliente'];
	$idCredito = $_REQUEST['idcredito'];

	$dpi = $_POST['dpi'];
	$nombre = $_POST['nombre'];
	$telefono = $_POST['telefono'];
	$direccion = $_POST['direccion'];
	$cobrador = $_POST['cobrador'];
			
	$regClientes = new clientes();
	$regCreditos = new creditos();

	if($regClientes->sqlTrans("UPDATE clientes SET nombrecompleto='".$nombre."', dpi='".$dpi."', telefono = '".$telefono."', direccion = '".$direccion."' WHERE idcliente= $idCliente")){
		$regCreditos->sqlTrans("UPDATE creditos SET cobrador = '".$cobrador."' WHERE idcredito = $idCredito");
		$regClientes->showMessage("Exito!", "cliente Editado");
		echo"<script>setTimeout(\"$('#divmodal').modal('hide'); location.reload();\",500); </script>";
	}
	else{
		$usuarios->showMessage('Fallo','Error al editar el usuario','error');
	}
}
else
if ($a=="modificarCliente"){
	$idCredito = $_REQUEST['idcredito'];
	$objUsuarios = new usuarios();
	$objCreditos = new creditos();
	$regCreditos = $objCreditos->sql("SELECT 
		cli.idcliente as idcliente,
		cli.dpi as dpi,
		cli.nombrecompleto as nombre,
		cli.telefono as telefono,
		cli.direccion as direccion,
		cre.cobrador as cobrador
		FROM creditos cre
		INNER JOIN clientes cli ON cre.idcliente = cli.idcliente
		WHERE cre.idCredito = '$idCredito' 
		");
	$idCliente = $regCreditos[0]['idcliente'];
	$regUsuarios = $objUsuarios->getRecords("idtipousuario=3");

	echo"
	<!-- Formulario de Informacion -->
	<form class='form-horizontal' action='../includes/actions/clientes.php?a=editCliente&idcliente=$idCliente&idcredito=$idCredito' id='formNewCliente' method='post'>
			<div class='modal-header'>
				<a class='close' data-dismiss='modal'>×</a>
				<h3>Modificar Cliente</h3>
			</div>
			<div class='modal-body'>
				<fieldset>
					
					<div class='control-group'>
						<label class='control-label' for='nombre'>No. de DPI: </label>
						<div class='controls'>
							<input class='input-xlarge required'  id='dpi' name='dpi' value='".$regCreditos[0]['dpi']."' type='text'>
						</div>
					</div>

					<div class='control-group'>
						<label class='control-label' for='nombre'>Nombre Completo: </label>
						<div class='controls'>
							<input class='input-xlarge required'  id='nombre' name='nombre' value='".$regCreditos[0]['nombre']."' type='text'>
						</div>
					</div>

					<div class='control-group'>
						<label class='control-label' for='telefono'>Teléfono: </label>
						<div class='controls'>
							<input class='input-xlarge required'  id='telefono' name='telefono' value='".$regCreditos[0]['telefono']."' type='text'>
						</div>
					</div>

					<div class='control-group'>
						<label class='control-label' for='direccion'>Dirección: </label>
						<div class='controls'>
							<input class='input-xlarge required'   id='direccion' name='direccion' value='".$regCreditos[0]['direccion']."' type='text'>
						</div>
					</div>

					<div class='control-group'>
						<label class='control-label' for='Cobrador'>Cobrador: </label>
						<div class='controls'>
							<select id='cobrador' class='input-xlarge required' name='cobrador'>
							<option value='' >Seleccione.. </option>"; 	
							foreach ($regUsuarios as $key => $campo) {
								if($regCreditos[0]['cobrador']==$campo['nombre'])
									echo"<option value='".$campo['nombre']."' selected>".$campo['nombre']."</option>";
								else
									echo"<option value='".$campo['nombre']."'>".$campo['nombre']."</option>";
							}
							echo"</select>
							
						</div>
					</div>

				</fieldset>
			</div>
		

			<div class='modal-footer'>
				<div class='response'></div>
				<input type='submit' class='btn btn-primary' value='Guardar' id='btn_guardar' name='btn_guardar'  />
				<button type='button' onClick=\"$('#divmodal').modal('hide');$('#divmodal2').modal('hide');\"  class='btn'>Salir</button>
			</div>
		</div>
	</form>
	<script>
	 	validator = $(\"#formNewCliente\").validate();
	</script>
	";

}
else
if($a=="verplan"){
	$id=$_REQUEST['id'];
	
	$creditos= new creditos();
			
	$registros =$creditos->sql("SELECT
		p.idplan as idplan,
		p.nombreplan as nombreplan,
		p.dias as dias,
		p.porcentajeinteres as porcentaje
		FROM creditos c
		INNER JOIN planes p ON p.idplan=c.idplan
		WHERE c.idplan=$id");
		
		

	echo"
	<!-- Formulario de Informacion -->
	<form class='form-horizontal'  id='formNewUsuario' method='post'>
			<div class='modal-header'>
				<a class='close' data-dismiss='modal'>×</a>
				<h3>Tipo de Plan</h3>
			</div>
			<div class='modal-body'>
				<fieldset>
										
					<div class='control-group'>
						<label class='control-label' for='nombreplanver'>Plan: </label>
						<div class='controls'>
							<input class='input-xlarge'  readonly id='nombreplanver' value='".$registros[0]['nombreplan']."' name='nombreplanver'  type='text' >
						</div>
					</div>
					
					<div class='control-group'>
						<label class='control-label' for='dias'>Dias de Crédito: </label>
						<div class='controls'>
						<div class='input-append'>
							<input class='input-mini'  readonly id='diasver' value='".$registros[0]['dias']."' name='diasver'  type='text' >
							<span class='add-on'>dias</span>
						</div>
						</div>
					</div>

					<div class='control-group'>
						<label class='control-label' for='porcentaje'>Porcentaje de Interés: </label>
						<div class='controls'>
						<div class='input-append'>
							<input class='input-mini'  readonly id='porcentajever' value='".$registros[0]['porcentaje']."' name='porcentajever'  type='text' >
						<span class='add-on'>%</span>
						</div>	
						</div>
					</div>

				</fieldset>
			</div>
			<div class='modal-footer'>
				<div class='response'></div>
				
				<button type='button' onClick=\"$('#divmodal').modal('hide');\"  class='btn'>Salir</button>
			</div>
		</div>
	</form>
	<script>
	 	validator = $(\"#formNewUsuario\").validate();
	</script>
	";
}
else
if($a=="verdeuda"){

	$id_credito=$_REQUEST['id'];
	$fechahoy = date("Y-m-d");
    $detallecreditos=new detallecreditos();
	
	$reg_Detalle = $detallecreditos-> sql("SELECT dc.fecha AS fecha FROM detallecreditos dc WHERE ((dc.idcredito = $id_credito) AND (dc.estado = 1)) AND (dc.fecha <= \"$fechahoy\")");

	echo"
			<div class='modal-header'>
				<a class='close' data-dismiss='modal'>×</a>
					<h3>Cuotas Pendientes</h3>
			</div>
			<div class='modal-body'>
				<fieldset>
				<table cellpadding='0' cellspacing='0' border='0' class='table table-striped table-bordered' id='tablaplanes'>
				<thead>
					<tr>	
		     			<th>#</th>
		     			<th>Fechas</th>
		     		</tr>
					</thead>
				<tbody>";


				
					
				$lim=0;
		    	foreach($reg_Detalle as $filas => $campo) {
		    		$lim++;
		    		$fecha=date("d-m-Y",strtotime($campo['fecha']));	
		    		
		    		echo"<tr >";
		    			echo"<td><center>".$lim."</center></td>";
						echo"<td><center>".$fecha."</center></td>";
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
					<button type='button' onClick=\"$('#divmodal').modal('hide');\"  class='btn'>Salir</button>
				</div>
			</div>

	";
}
else
if($a=="estadodecuenta"){
	
	$id= $_REQUEST['idcliente'];

	$creditos= new creditos();
	$detallecreditos= new detallecreditos();

	$registro= $creditos->sql("SELECT
	cl.nombrecompleto as nombrecompleto,
	cl.idcliente as idcliente,
	cr.cobrador as cobrador,
	cr.fechainicio as inicio,
	cr.fechafin as fin,
	cl.dpi as dpi,
	p.idplan as idplan,
	p.nombreplan as plan,
	p.dias as dias,
	m.montocapital as monto,
	cr.montointeres as interes,
	count(dc.estado) as estadodetalle,
	dc.saldointeres as saldointeres,
	dc.saldocapital as saldocapital
	FROM creditos cr
	INNER JOIN clientes cl ON cr.idcliente=cl.idcliente
	INNER JOIN montos m ON cr.idmonto=m.idmonto
	INNER JOIN planes p ON cr.idplan=p.idplan
	INNER JOIN detallecreditos dc ON dc.idcredito=cr.idcredito
	WHERE (cr.idcredito=$id) and (dc.estado = 1)");   

	$idcliente = $registro[0]['idcliente'];
	$saldo= number_format($registro[0]['saldocapital'] + $registro[0]['saldointeres'],2,".",",");
	$limite= number_format($registro[0]['monto'] + $registro[0]['interes'],2,".",",");
	$porcentajepagado = ($registro[0]['dias'] - $registro[0]['estadodetalle'])*100/($registro[0]['dias']);
	$entero = number_format($porcentajepagado,2,".",",");
	$cuotaspendientes = $registro[0]['estadodetalle'];
	$cuotaspagadas = $registro[0]['dias'] - $registro[0]['estadodetalle'];
	$id_plan = $registro[0]['idplan'];

	echo"
	<div class=\"btn-toolbar\" >
	<br>
	</div>
	<div class='row-fluid'>
		<div class='span6'>
		<div class='well well-small'>
			<i class='icon-ok'></i>DPI: <strong>".$registro[0]['dpi']." </strong><br>
			<i class='icon-ok'></i>Nombre: <strong><span class='label label-success'>".$registro[0]['nombrecompleto']."</span></strong><br>
			<i class='icon-ok'></i>Nombre Cobrador: <strong>".$registro[0]['cobrador']."</strong>
		</div>
		</div>
		<div class='span6'>
		<div class='well well-small'>	
			<i class='icon-ok'></i>Plan de Préstamo: <strong><a rel='tooltip' title='Click para Ver' onclick=\" $('#divmodal').load('../includes/actions/clientes.php?a=verplan&id=$id_plan').modal('show'); \" ><span class='label label-info'>".$registro[0]['plan']."</span></a> </strong><br>
			<i class='icon-ok'></i>Fecha de Inicio: <strong> ".$registro[0]['inicio']." </strong><br>
			<i class='icon-ok'></i>Fecha de Finalización: <strong> ".$registro[0]['fin']."</strong>
		</div>
		</div>
	</div>
	<br>
	<div class='row-fluid'>
		<div class='span6' >
			<table class='table table-bordered'>
				<tr>
					<td><center>
					<strong>Saldo Actual</strong><br>
					
						<div class='well well-small-c'>
						<h3 class='text-success' align='center'>Q ".$saldo."</h3>	
						</div>
					
					</center></td>
				</tr>
			</table>
		</div>
		<div class='span6' >
			<table class='table table-bordered' aling='right'>
				<tr>
					<td><center>
					<strong>Total de Crédito</strong><br>
						
						<div class='well well-small-c'>
						<h3 class='text-success' align='center'>Q ".$limite."</h3>	
						</div>	
						
					</center></td>
				</tr>
			</table>
		</div>		
	</div>

	<br>
	<center><strong>Cuotas Pendientes: <span class='text text-danger'><strong>".$cuotaspendientes."</strong></span> | Cuotas Pagadas: <span class='text text-success'><strong>".$cuotaspagadas."</strong></span></strong></center>
	<div class='progress progress-striped active'>
  		<div class='bar bar-danger' style='width: ".(100-$entero)."%;'></div>
  		<div class='bar bar-success' style='width: ".$entero."%;'></div>
	</div>
	<br>
	<center>
	<div class=\"btn-toolbar\" >
	<div class=\"btn-group\" >
		<button type='button' onClick=\"$('#divtablaprincipal').load('../includes/actions/clientes.html?a=tablaprincipal');\"  class='btn btn-primary'>Regresar</button>
	</div>
	<div class=\"btn-group\" >
		<button type='button' onclick=\" $('#divmodal').load('../includes/actions/clientes.php?a=verdetallecreditos&id2=$id').modal('show');\"  class='btn btn-info'>Ver Detalles de Estado de Cuenta</button>
	</div>
	";
	if($_SESSION['idtipousuario']==1 || $_SESSION['idtipousuario']==2){
		echo"
			<div class=\"btn-group\" >
			<button type='button'  class='btn btn-warning btn-medium' onclick=\"$('#divmodalPassword').load('../includes/actions/autorizacion.php?a=autorizacion').modal('show');\" >Registrar Abono</button>
			
			</div>
			<div class=\"btn-group\" >
				<button type='button' onclick=\" $('#divmodal4').load('../includes/actions/clientes.php?a=renovacion&idcre=$id&idcli=$idcliente').modal('show');\"  class='btn btn-success'>Renovar Préstamo</button>
			</div>	
	";};
	echo"
		
	</div>
	</center>
	";
}
if($a=="verdetallecreditos")
	{
		
		$dato = $_REQUEST['id2'];
		$detallecreditos= new detallecreditos();
		
		$registros = $detallecreditos->sql("SELECT                                     
            dc.fecha as fecha,
            dc.saldocapital as capital,
            dc.estado as estado, 
            dc.saldointeres as interes              
            FROM detallecreditos dc
            WHERE dc.idcredito=$dato");

		echo "
			<div class='modal-header'>
				<a class='close' data-dismiss='modal'>×</a>
					<h3>Detalles de Estado de Cuenta</h3>
			</div>
			<div class='modal-body'>
				<fieldset>
				<table cellpadding='0' cellspacing='0' border='0' class='table table-striped table-bordered' id='tablaplanes'>
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
					if ($campo['estado']==1){
						echo"<center><span class='label label-important'>Pendiente de Abono</span></center>";
					}
					else{
						echo"<center><span class='label label-success'>Cuota Abonada</span></center>";	
					}
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
				<a target=_'target' href='../includes/actions/reporteador.php?a=estadodecuenta&id3=$dato' class='btn btn-primary btn-medium' >Imprimir Detalle</a>
				<button type='button' onClick=\"$('#divmodal').modal('hide');\"  class='btn'>Salir</button>
			</div>
		</div>
	
	";
}
else
if($a=="renovacion"){
	$idcredito = $_REQUEST['idcre'];
	$idcliente = $_REQUEST['idcli'];

	$creditos= new creditos();
	$detallecreditos= new detallecreditos();

	$registro= $creditos->sql("SELECT
	cl.nombrecompleto as nombrecompleto,
	count(dc.estado) as estadodetalle,
	dc.saldointeres as saldointeres,
	dc.saldocapital as saldocapital
	FROM creditos cr
	INNER JOIN clientes cl ON cr.idcliente=cl.idcliente
	INNER JOIN detallecreditos dc ON dc.idcredito=cr.idcredito
	WHERE ((cr.idcredito=$idcredito) and (dc.estado = 1)) and (cr.estado = 1)");   

	$saldo= $registro[0]['saldocapital'] + $registro[0]['saldointeres'];
	$saldo1 = number_format($saldo,2,".",",");
	$cuotaspendientes = $registro[0]['estadodetalle'];

	echo"
	<!-- Formulario de Informacion -->
	<form class='form-horizontal' action='../includes/actions/renovaciones.php?a=renovaciones&idcre=$idcredito&idcli=$idcliente&saldo=$saldo'  id='formNewUsuario' method='post'>
			<div class='modal-header'>
				<a class='close' data-dismiss='modal'>×</a>
				<h3>Saldo Pendiente</h3>
			</div>
			<div class='modal-body'>
				<fieldset>
					<div class='control-group'>
						<label class='control-label' for='nombre'>Cliente: </label>
						<div class='controls'>
							<input class='input-xlarge'  readonly id='Cliente' value='".$registro[0]['nombrecompleto']."' name='nombreplanver'  type='text' style='text-align:center'>
						</div>
					</div>

					<div class='control-group'>
						<label class='control-label' for='saldopendiente'>Saldo Pendiente: </label>
						<div class='controls'>
							<input class='input-medium'  readonly id='SaldoPendiente' value='Q. ".$saldo1."' name='nombreplanver'  type='text' style='text-align:center' >
						</div>
					</div>
					
					<div class='control-group'>
						<label class='control-label' for='dias'>Cuotas Pendientes: </label>
						<div class='controls'>
							<input class='input-medium'  readonly id='cuotaspendientes' value='".$cuotaspendientes."' name='diasver'  type='text' style='text-align:center'>
						</div>
					</div>
				</fieldset>
			</div>
			<div class='modal-footer'>
			<div class='control-group'>
				<div class='controls'>
				<h4>Desea renovar contrato?</h4>
				</div>
				<div class='response'></div>
				<input type='submit' class='btn btn-primary' value='Si' id='btn_guardar' name='btn_guardar'  />
				<button type='button' onClick=\"$('#divmodal4').modal('hide');$('#divmodal').modal('hide');\"  class='btn'>No</button>
			</div>
			</div>
		</div>
	</form>

	<script>
	 	validator = $(\"#formNewUsuario\").validate();
	</script>
	";

}
else
	if($a == "anular"){
	$idCredito = $_REQUEST['idcredito'];
	echo"
		<!-- Formulario de Informacion -->
		<form class='form-horizontal' action='../includes/actions/clientes.php?a=anularcredito&idcredito=$idCredito' id='frmAnular' method='post'>
			<div class='modal-header'>
				<a class='close' data-dismiss='modal'>×</a>
				<h3>Anular Factura</h3>
			</div>
			<div class='modal-body'>
				<fieldset>
					<center><h3>Está seguro de anular el CRÉDITO...</h3></center>
					<br>
					<div class='control-group'>
						<label class='control-label' for='nombre'><strong>Ingrese su contraseña: </strong></label>
						<div class='controls'>
							<input class='input-xlarge required' autofocus='password' id='password' name='password'  type='password'>
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
		 	validator = $(\"#frmAnular\").validate();
		</script>
	";
}
else
	if($a == "anularcredito"){
		$password = $_POST['password'];
		$idCredito = $_REQUEST['idcredito'];
		
		$creditos = new creditos();
		$objUsuarios = new usuarios();
		$detallecreditos = new detallecreditos();

		$regUsuarios = $objUsuarios->getRecords("idusuario='$usu' and password='$password'");

		if (!$regUsuarios){
			$creditos->showMessage('Error!', 'Usuario no autorizado!','error');
		}
		else{
			$registros = $detallecreditos->getRecords("idcredito=$idCredito");
					
			$anular = 1;
			$estado = 0;

			if($creditos->sqlTrans("UPDATE creditos SET estadoanular='".$anular."', estado='".$estado."' WHERE idcredito= $idCredito")){
				for ($i=0; $i < sizeof($registros); $i++) { 
					$detallecreditos->sql("UPDATE detallecreditos SET estado=0 WHERE idcredito = $idCredito");
				}
				$creditos->showMessage("Éxito!", "Credito Anulado");
				echo"<script>setTimeout(\"$('#divmodal').modal('hide'); location.reload();\",500); </script>";
			}
			else
			$creditos->showMessage('Fallo','Error al anular el crédito','error');
		}
	}

?>
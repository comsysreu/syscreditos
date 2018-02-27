<?php
session_start();
include("../class.usuarios.php");
include("../class.creditos.php");
include("../class.clientes.php");
include("../class.tipousuarios.php");
include("../class.detallecreditos.php");

$a = $_GET['a'];


if($a=="tablaprincipal"){

$Usuarios= new usuarios();
$tiposusuarios=new tipousuarios();
$registros = $Usuarios->sql("SELECT
	u.idUsuario as idusuario,
	tu.nombre as tipo,
	u.nombreusuario as usuario,
	u.nombre as nombre,
	u.estado as estado
	FROM usuarios u
	INNER JOIN tipousuarios tu ON u.idtipousuario= tu.idtipousuario	
	where tu.idtipousuario=3 and u.estado = 1");	
	echo "
	<table cellpadding='0' cellspacing='0' border='0' class='table table-striped table-bordered' id='tablaUsuarios'>
		<thead>
			<tr>	
		    	<th>#</th>
		     	<th>Nombre</th>
		     	<th>Imprimir</th>
		     	<th>Acciones</th>
			</tr>
		</thead>
		<tbody>";		
		    $lim=0;		    
		    foreach($registros as $filas => $campo) {
		    	$lim++;
				echo"<tr >";
					echo"<td>".$lim."</td>";				
					echo"<td>".$campo['nombre']."</td>";
					echo"<td>";
						echo"<center><a target='_blank' class='btn btn-primary btn-mini' href='../includes/actions/reporteador.php?a=clientesCobrador&idUsuario=".$campo['idusuario']."'>Imprimir Lista</a></center>";						
					echo"</td>";						
					echo"<td>";					
						echo"<center><a rel='tooltip' title='ver Clientes' style='cursor: pointer;' class='btn btn-success btn-mini' onclick=\"$('#divTablaClientes').ScrollTo({duration:500,easing: 'linear'}); $('#divTablaClientes').load('../includes/actions/cobradores.html?a=tablaClientes&idUsuario=".$campo['idusuario']."');\" return false;>Ver clientes</a></center>";										
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
else if($a == "tablaClientes"){
	$idUsuario = $_REQUEST['idUsuario'];

	$objUsuarios = new usuarios();
	$objClientes = new clientes();
	$objCreditos = new creditos();
	$objDetalleCreditos = new detallecreditos();

	$regUsuarios = $objUsuarios->getRecords("idusuario=".$idUsuario);
	$nombreCobrador = $regUsuarios[0]['nombre'];
	$fechahoy = date('Y-m-d');

	$regCreditos = $objCreditos->sql("SELECT 
		cre.idcredito as idcredito,
		cre.cobrador as cobrador,
		cli.nombrecompleto as nombrecompleto,
		cli.telefono as telefono,
		cli.direccion as direccion,
		cre.cuotadiaria as cuotadiaria
		FROM creditos cre
		INNER JOIN clientes cli ON cre.idcliente = cli.idcliente
		WHERE (cre.fechaultimopago = \"$fechahoy\" and cre.cobrador = '$nombreCobrador') or (cre.cobrador = '$nombreCobrador' and cre.estado = 1) 
	");

	echo "
		<br/>
		<br/>
		<hr/>
		<div class=\"btn-toolbar\" >
			<div class=\"btn-group\" >    
		    	<h3>Clientes del Cobrador: ".$nombreCobrador."</h3>    
	    			  			      		
		    </div>
		</div>

		<div class='row-fluid'>
			<div class='span9'>
				<table cellpadding='0' cellspacing='0' border='0' class='table table-striped table-bordered' id='tablaClientes'>
			  		<thead>
			  			<tr>  
			  				<th>#</th>
			         		<th><center>Nombre Cliente</center></th>			         		
				        	<th><center>Cuota Diaria</center></th>
				        	<th><center>Cuotas pagadas</center></th>
				        	<th><center>Total</center></th>
				        	<th><center>Pago del día</center></th>				        	
			      		</tr>
			    	</thead>
			    	<tbody>";
			    	$lim=0;
			    	
			    	foreach($regCreditos as $filas => $campo){
			    		$lim++;
			    		$id1 = $campo['idcredito'];
			    		$regDetalle = $objDetalleCreditos->getRecords("idcredito = $id1 AND estado = 0 AND fechapago = \"$fechahoy\"");
			    		$cuotasPagadas = count($regDetalle);
			    		$totalpagado = $cuotasPagadas*$campo['cuotadiaria'];
			    		
			    		if (!$regDetalle){
			    			$pagoDia = "<span class='label label-important'>No pagado</span>";			    								
			    		}
						else{
							$pagoDia = "<span class='label label-success'>Pagado</span>"; 
							$total = $total + $totalpagado;
						}

						$totalaCobrar = $totalaCobrar + $campo['cuotadiaria'];
			    		
			    		echo"<tr >";
							echo"<td>".$lim."</td>";
							echo"<td>".$campo['nombrecompleto']."</td>";							
							echo"<td><center>Q.".number_format($campo['cuotadiaria'],2,".",",")."</center></td>";
							echo"<td><center>".$cuotasPagadas."</center></td>";
							echo"<td><center>Q.".number_format($totalpagado,2,".",",")."</center></td>";
							echo"<td><center>".$pagoDia."</center></td>";
						echo"</tr >";
			    	}

			    	echo"			    	
		    		</tbody>
				</table>
			</div>
			<div class='span3'>
				<table class='table table-bordered'>
        			<tr>
        				<td>        					
        					<center><h4><strong>Total a Cobrar: </strong></h4></center>        					
        					<pre>
        					<h2 class='text-success' align='center'>Q.".number_format($totalaCobrar,2,".",",")."</h2>
        					</pre>
        					<center><h4><strong>Total Cobrado: </strong></h4></center>
        					<pre>
        					<h2 class='text-success' align='center'>Q.".number_format($total,2,".",",")."</h2>
        					</pre>
        					<center>
								<div class='btn-group' align='center'>	
									<a target='_blank' class='btn btn-success btn-medium' href='../includes/actions/reporteador.php?a=clientesCobradorPagado&idUsuario=".$idUsuario."'>Imprimir Lista</a>
	  								
		   						</div>
							</center>    	
       					</td>
        			</tr>
        		</table>
	    	</div>    
		</div>
		
	    

		<script>
			$(document).ready(function() {

		    $(\"a[rel='tooltip']\").tooltip(); //tooltips bootstrap       
		        desbloquearUi();
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
?>
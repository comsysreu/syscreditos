<?php
session_start();
include("../class.planes.php");

$a = $_GET['a'];


if($a=="tablaprincipal"){

$planes= new planes();
$registros = $planes->getRecords();
	echo "
	<ul class=\"thumbnails\">
	";
		
		    $lim=0;
		    
		    foreach ($registros as $key => $campo) {
		    	if($campo['idplan'] != 0 && $campo['estado']==1){
		    	$lim++;
				echo"
				
					<li class='span3'>
						<a rel='tooltip' title='ver ".$campo['nombreplan']."' class='btn thumbnail' onclick=\" $('#divmodal').load('../includes/actions/planes.php?a=verplan&idplan=".$campo['idplan']."').modal('show'); \" ></i>
        					<img src='../img/escritorio128x128/depositos.png' class=''/>
        					<h4><p><b>".$campo['nombreplan']."</b></p></h4>
      					</a>
    				</li>";
				
				echo"</tr>";
				}

		}
     echo"
	</ul>
     
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
if($a=="createplan")
{
	$planes= new planes();
	$nombre= $_POST['nombre'];
	$dias= $_POST['dias'];
	$estado = 1;
	$porcentaje= $_POST['porcentaje'];
	
	if($planes->insertRecord(array($nombre,$dias,$porcentaje,$estado)))
	{
		
			$planes->showMessage("Exito!", "Plan Creado");
			echo"<script>setTimeout(\"$('#divmodal').modal('hide'); location.reload();\",1500); </script>";
		}
			else
	{
		$planes->showMessage('Fallo','Error al crear el plan, verifique si no existe!','error');
	}
	

}
else
	if($a=="nuevoplan"){
	echo"
	<!-- Formulario de Informacion -->
	<form class='form-horizontal' action='../includes/actions/planes.php?a=createplan' id='formNewUser' method='post'>
			<div class='modal-header'>
				<a class='close' data-dismiss='modal'>×</a>
				<h3>Nuevo Plan de Préstamo</h3>
			</div>
			<div class='modal-body'>
				<fieldset>
					
					<div class='control-group'>
						<label class='control-label' for='nombre'>Nombre de Plan: </label>
						<div class='controls'>
							<input class='input-large required' id='nombre' name='nombre'  type='text'>
						</div>
					</div>

					<div class='control-group'>
						<label class='control-label' for='dias'>Cantidad de Días: </label>
						<div class='controls'>
						<div class='input-append'>
							<input class='input-mini required' id='dias' name='dias'  type='text'>
						<span class='add-on'>días</span>
						</div>
						</div>
					</div>

					<div class='control-group'>
						<label class='control-label' for='porcentaje'>Porcentaje de Interes: </label>
						<div class='controls'>
						<div class='input-append'>
							<input class='input-mini required'   id='porcentaje' name='porcentaje'  type='text'>
						<span class='add-on'>%</span>
						</div>
						</div>
					</div>

					</fieldset>
			</div>
			<div class='modal-footer'>
				<div class='response'></div>
				<input type='submit' class='btn btn-primary' value='Guardar' id='btn_guardar' name='btn_guardar'  />
				<button type='button' onClick=\"$('#divmodal').modal('hide');\"  class='btn'>Salir</button>
			</div>
		</div>
	</form>
	<script>
	 	validator = $(\"#formNewUser\").validate();
	</script>
	";
}
else
if($a=="verplan"){
	$id=$_REQUEST['idplan'];
    $planes=new planes();
	$plan = $planes->getRecords("idplan = $id");

	echo"
			<div class='modal-header'>
				<a class='close' data-dismiss='modal'>×</a>
					<h3>".$plan[0]['nombreplan']."</h3>
			</div>
			<div class='modal-body'>
				<fieldset>
				<table cellpadding='0' cellspacing='0' border='0' class='table table-striped table-bordered' id='tablaplanes'>
				<thead>
					<tr>	
		     			<th>Cantidad Días</th>
		     			<th>% Interes</th>
		     			<th>Acciones</th>
					</tr>
					</thead>
				<tbody>";

				$lim=0;
		    	foreach($plan as $filas => $campo) {
		    		echo"<tr >";
					echo"<td><strong><h3><center>".$campo['dias']." días"."</center></h3></strong></td>";
					echo"<td><strong><h3><center>".$campo['porcentajeinteres']." %"."</center></h3></strong></td>";
					echo"<td><strong><h3><center>";
					if($_SESSION['idtipousuario']==1){
						echo"<a rel='tooltip' title='Modificar' onclick=\" $('#divmodal2').load('../includes/actions/planes.php?a=modificarplan&id=".$plan[0]['idplan']."').modal('show'); $('#divmodal').modal('hide'); \" ><i class='icon-pencil'></i></a>";
						echo" ";
						echo"<a rel='tooltip' title='Desactivar' onclick=\" $('#divmodal2').load('../includes/actions/planes.php?a=desactivarplan&id=".$plan[0]['idplan']."').modal('show'); $('#divmodal').modal('hide');\" ><i class='icon-ban-circle'></i></a>";
					};
					echo"</center></center></h3></td>";
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
	if($a=="modificar"){
		
		$planes= new planes();
		$nombre= $_REQUEST['nombre'];
		$dias = $_REQUEST['dias'];
		$porcentaje = $_REQUEST['porcentaje'];

		$id = $_REQUEST['idplan'];

			if($planes->sqlTrans("UPDATE planes SET nombreplan='".$nombre."', dias='".$dias."', porcentajeinteres='".$porcentaje."' WHERE idplan= $id"))
			{
							
				$planes->showMessage("Exito!", "Plan Editado");
				echo"<script>$('#divtablaprincipal').load('../includes/actions/planes.php?a=tablaprincipal'); setTimeout(\"$('#divmodal2').modal('hide'); \",1500); </script>";
				
			}
			else
			{
				$planes->showMessage('Fallo','Error al editar el plan','error');
			}
		
		

}

else
if($a=="modificarplan"){
	$evento = 'modificar';
	$id=$_REQUEST['id'];
    $planes=new planes();
	$plan = $planes->getRecords("idplan = $id");

	echo"

	<!-- Formulario de Informacion -->
	<form class='form-horizontal' action='../includes/actions/planes.html?a=modificar&idplan=$id' id='formNewUser' method='post'>
			<div class='modal-header'>
				<a class='close' data-dismiss='modal'>×</a>
				<h3>".$plan[0]['nombreplan']." </h3>
			</div>
			<div class='modal-body'>
				<fieldset>
					
					<div class='control-group'>
						<label class='control-label' for='nombre'>Nombre: </label>
						<div class='controls'>
							<input class='input-mini' id='nombre' value='".$plan[0]['nombreplan']."' name='nombre'  type='text'>
						</div>
					</div>

					<div class='control-group'>
						<label class='control-label' for='dias'>Cantidad de Días: </label>
						<div class='controls'>
						<div class='input-append'>
							<input class='input-mini' id='dias' value='".$plan[0]['dias']."' name='dias'  type='text'>
						<span class='add-on'>días</span>
						</div>
						</div>
					</div>

					<div class='control-group'>
						<label class='control-label' for='porcentaje'>Porcentaje de Interes: </label>
						<div class='controls'>
						<div class='input-append'>
							<input class='input-mini' id='porcentaje' value='".$plan[0]['porcentajeinteres']."' name='porcentaje'  type='text'>
						<span class='add-on'>%</span>
						</div>
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
	<script>
	 	validator = $(\"#formNewUser\").validate();
	</script>
	";
}
else
	if($a=="deactiveplan"){
		$id = $_REQUEST['idplan'];
		$planes= new planes();
		$estado=0;
		if($planes->sqlTrans("UPDATE planes SET estado='".$estado."' WHERE idplan= $id"))
		{
			$planes->showMessage("Exito!", "Plan desactivado...");
			echo"<script>$('#divtablaprincipal').load('../includes/actions/planes.php?a=tablaprincipal'); setTimeout(\"$('#divmodal2').modal('hide'); \",1000); </script>";	
		}
		else
		{
			$planes->showMessage('Fallo','Error al desactivar el usuario','error');
		}
	}
else
if($a=="desactivarplan"){
	
	$id=$_REQUEST['id'];
	echo"
	<!-- Formulario de Informacion -->
	<form class='form-horizontal' action='../includes/actions/planes.php?a=deactiveplan&idplan=$id' id='formNewUser' method='post'>
			<div class='modal-header'>
				<a class='close' data-dismiss='modal'>×</a>
				<h3>Desactivar Plan</h3>
			</div>
			<div class='modal-body'>
				<fieldset>
					<h3>Esta seguro de desactivar el plan...</h3>
				</fieldset>
			</div>
			<div class='modal-footer'>
				<div class='response'></div>
				<input type='submit' class='btn btn-primary' value='Si' id='btn_guardar' name='btn_guardar'  />
				<button type='button' onClick=\"$('#divmodal2').modal('hide');\"  class='btn'>No</button>
			</div>
		</div>
	</form>
	<script>
	 	validator = $(\"#formNewUser\").validate();
	</script>
	";

}
if($a=="verplandesactivados"){
    $planes=new planes();
	$plan = $planes->getRecords("estado = 0");

	echo"
			<div class='modal-header'>
				<a class='close' data-dismiss='modal'>×</a>
					<h3>Planes Desactivados</h3>
			</div>
			<div class='modal-body'>
				<fieldset>
				<table cellpadding='0' cellspacing='0' border='0' class='table table-striped table-bordered' id='tablaplanes'>
				<thead>
					<tr>
						<th>Nombre de Plan</th>	
		     			<th>Cantidad Días</th>
		     			<th>% Interes</th>
		     			<th>Acciones</th>
					</tr>
					</thead>
				<tbody>";

				$lim=0;
		    	foreach($plan as $filas => $campo) {
		    		echo"<tr >";
					echo"<td><center>".$campo['nombreplan']."</center></td>";
					echo"<td><center>".$campo['dias']." días"."</center></td>";
					echo"<td><center>".$campo['porcentajeinteres']." %"."</center></td>";
					echo"<td><center>";
						echo"<a rel='tooltip' title='Activar' onclick=\" $('#divmodal2').load('../includes/actions/planes.php?a=activarplan&id=".$campo['idplan']."').modal('show'); $('#divmodal').modal('hide'); \" ><i class='icon-ok'></i></a>";
						echo" ";
						echo"<a rel='tooltip' title='Eliminar' onclick=\" $('#divmodal2').load('../includes/actions/planes.php?a=eliminarplan&id=".$campo['idplan']."').modal('show'); $('#divmodal').modal('hide');\" ><i class='icon-remove'></i></a>";
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
	if($a=="activeplan"){
		$id = $_REQUEST['idplan'];
		$planes= new planes();
		$activo=1;
		if($planes->sqlTrans("UPDATE planes SET estado='".$activo."' WHERE idplan= $id"))
		{
			$planes->showMessage("Exito!", "Plan activado");
			echo"<script>$('#divtablaprincipal').load('../includes/actions/planes.php?a=tablaprincipal'); setTimeout(\"$('#divmodal2').modal('hide'); \",1000); </script>";
		}
		else
		{
			$planes->showMessage('Fallo','Error al activar el plan','error');
		}
	}
	else
if($a=="activarplan"){
	
	$id=$_REQUEST['id'];	
	echo"
	<!-- Formulario de Informacion -->
	<form class='form-horizontal' action='../includes/actions/planes.php?a=activeplan&idplan=$id' id='formNewUser' method='post'>
			<div class='modal-header'>
				<a class='close' data-dismiss='modal'>×</a>
				<h3>Activar Plan</h3>
			</div>
			<div class='modal-body'>
				<fieldset>
					<h3>Esta seguro de activar el plan...</h3>
				</fieldset>
			</div>
			<div class='modal-footer'>
				<div class='response'></div>
				<input type='submit' class='btn btn-primary' value='Si' id='btn_guardar' name='btn_guardar'  />
				<button type='button' onClick=\"$('#divmodal2').modal('hide');\"  class='btn'>No</button>
			</div>
		</div>
	</form>
	<script>
	 	validator = $(\"#formNewUser\").validate();
	</script>
	";
}
if($a=="deleteplan")
{
	$id = $_REQUEST['idplan'];
	
	$planes= new planes();

	if($planes->sqlTrans("DELETE FROM planes  WHERE idplan= $id"))
	{
		
			$planes->showMessage("Exito!", "Plan Eliminado"); 
			echo"<script>$('#divtablaprincipal').load('../includes/actions/planes.php?a=tablaprincipal'); setTimeout(\"$('#divmodal2').modal('hide'); \",1000); </script>";
			
	}
	else
	{
		echo"".$id."";
		$planes->showMessage('Fallo','Error al eliminar el plan','error'); 
	}

}
else
if($a=="eliminarplan")
{
	$id = $_REQUEST['id'];
				
	echo"
	<!-- Formulario de Informacion -->
	<form class='form-horizontal' action='../includes/actions/planes.php?a=deleteplan&idplan=$id' id='formDeleteUser' method='post'>
			<div class='modal-header'>
				<a class='close' data-dismiss='modal'>×</a>
				<h3>Eliminar Plan</h3>
			</div>
			<div class='modal-body'>
				<fieldset>
					<h3>¿Esta seguro de eliminar el plan?</h3>						
				</fieldset>
			</div>
			<div class='modal-footer'>
				<div class='response'></div>
				<input type='submit' class='btn btn-primary' value='Si' id='btn_guardar' name='btn_guardar'  />
				<button type='button' onClick=\"$('#divmodal2').modal('hide');\"  class='btn'>No</button>
			</div>
		</div>
	</form>
	<script>
	 	validator = $(\"#formDeleteUser\").validate();
	</script>
	";
	}

?>
<?php
session_start();
include("../class.montos.php");

$a = $_GET['a'];


if($a=="crearmonto")
{
	$montos= new montos();
	$monto= $_POST['monto'];
	$registros=$montos->getRecords();

	foreach($registros as $filas => $campo)
    {
    	if ($campo['montocapital'] == $monto)
    	{
    		$existe = 1;	
    	}
    }

    if ($existe != 1)
    {
		if($montos->insertRecord(array($monto)))
		{		
			$montos->showMessage("Exito!", "Monto creado exitosamente");
			echo"<script>setTimeout(\"$('#divmodal').modal('hide'); location.reload();\",1000); </script>";
		}
		else
			{
				$usuarios->showMessage('Fallo','Error al crear usuario, verifique si no existe!','error');
			}
	}
	else
		{
			$montos->showMessage("Error!", "Monto duplicado, verifique.",'error');
		}
}
else
	if($a=="nuevomonto"){
	echo"
	<!-- Formulario de Informacion -->
	<form class='form-horizontal' action='../includes/actions/montos.php?a=crearmonto' id='formNewUser' method='post'>
			<div class='modal-header'>
				<a class='close' data-dismiss='modal'>×</a>
				<h3>Nuevo Monto para Préstamo</h3>
			</div>
			<div class='modal-body'>
				<fieldset>
					
					<div class='control-group'>
						<label class='control-label' for='nombre'>Cantidad a crear: </label>
						<div class='controls'>
							<input class='input-large required' id='monto' name='monto'  type='text'>
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

?>
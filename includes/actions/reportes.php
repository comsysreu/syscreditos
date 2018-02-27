<?php
session_start();
include("../class.planes.php");

$a = $_GET['a'];

if($a=="tiporeportediario")
  {
  	//$fecha="2014-09-09";
  	echo"
  	<!-- Formulario de Informacion -->
	<form class='form-horizontal' action='../includes/actions/reportes.php?a=prueba'   id='formNewUser' method='post'>
			<div class='modal-header'>
				<a class='close' data-dismiss='modal'>×</a>
				<h3>Reporte Diario</h3>
			</div>
			<div class='modal-body'>
				<fieldset><br>
					<div class='control-group'>
						<label class='control-label' for='fechaini'>Fecha a generar reporte: </label>
						<div class='controls'>
						<input class='datepicker input-large required' id='fechareporte' value='' name='fechareporte' type='text' placeholder='Click para seleccionar fecha' style='text-align:center'>
						</div>
					</div>
				</fieldset>
			</div>

			<div class='modal-footer'>
				<div class='response'></div>
				<button type='submit' class='btn btn-primary' id='btn_guardar' name='btn_guardar'>Imprimir</button>
				<button type='button' onClick=\"$('#divmodal').modal('hide');\"  class='btn'>Salir</button>
			</div>
		</div>
	</form>

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
if($a=="prueba")
{
    
    ?>
    <script type="text/javascript">
    $(document).ready(function() {
    		monto = document.getElementById('fechareporte').value;
            window.location.replace('../includes/actions/reporteador.php?a=reportediario&fecha='+monto,'_blank');   
                });
    		setTimeout("$('#divmodal').modal('hide');","500"); 
    </script>
    <?php
}
else
if($a=="tiporeportemensual")
  {
  	echo"
  	<!-- Formulario de Informacion -->
	<form class='form-horizontal' action='../includes/actions/reportes.php?a=imprimirreportemensual'   id='formNewUser2' method='post'>
			<div class='modal-header'>
				<a class='close' data-dismiss='modal'>×</a>
				<h3>Reporte Mensual</h3>
			</div>
			<div class='modal-body' align='center'>
				<fieldset><br>
				<div>
					<span class='label label-important'>Seleccione el Mes:</span><br>
					<select name='mes' id='mes'>
						<option value='' selected='selected'>Seleccione...</option>
						<option value='01'>Enero</option>
						<option value='02'>Febrero</option>
						<option value='03'>Marzo</option>
						<option value='04'>Abril</option>
						<option value='05'>Mayo</option>
						<option value='06'>Junio</option>
						<option value='07'>Julio</option>
						<option value='08'>Agosto</option>
						<option value='09'>Septiembre</option>
						<option value='10'>Octubre</option>
						<option value='11'>Noviembre</option>
						<option value='12'>Diciembre</option>
					</select>
				</div>
				<div><br>
					<span class='label label-important'>Seleccione el Año:</span><br>
					<select name='anio' id='anio'>
						<option value='' selected='selected'>Seleccione...</option>
						<option value='2014'>Año 2014</option>
						<option value='2015'>Año 2015</option>
						<option value='2016'>Año 2016</option>
						<option value='2017'>Año 2017</option>
						<option value='2018'>Año 2018</option>
						<option value='2019'>Año 2019</option>
						<option value='2020'>Año 2020</option>
						<option value='2021'>Año 2021</option>
						<option value='2022'>Año 2022</option>
						<option value='2023'>Año 2023</option>
						<option value='2024'>Año 2024</option>
						<option value='2025'>Año 2025</option>
					</select>
				</div>
				<br>
				</fieldset>
			</div>

			<div class='modal-footer'>
				<div class='response'></div>
				<button type='submit' class='btn btn-primary' id='btn_guardar' name='btn_guardar'>Imprimir</button>
				<button type='button' onClick=\"$('#divmodal2').modal('hide');\"  class='btn'>Salir</button>
			</div>
		</div>
	</form>

	<script>
	 	validator = $(\"#formNewUser\").validate();
	 	$('.datepicker').datepicker({ dateFormat: \"yy-mm-dd\", dayNamesMin: [\"Do\", \"Lu\", \"Ma\", \"Mi\", \"Ju\", \"Vi\", \"Sa\"],yearRange: '".(date(Y)-100).":".(date(Y))."', 
	 		changeYear: true, monthNames: [\"Enero\",\"Febrero\",\"Marzo\",\"Abril\",\"Mayo\",\"Junio\",\"Julio\",\"Agosto\",\"Septiembre\",\"Octubre\",\"Noviembre\",\"Diciembre\"]});
	</script>

	<script>
	 	validator = $(\"#formNewUser2\").validate();
	</script>
	";
}
else
if($a=="imprimirreportemensual")
{
    ?>
    <script type="text/javascript">
    $(document).ready(function() {
    		fmes = document.getElementById('mes').value;
    		fanio = document.getElementById('anio').value;
            window.location.replace('../includes/actions/reporteador.php?a=reportemensual&fmes='+fmes+'&fanio='+fanio);   
                });
    		setTimeout("$('#divmodal2').modal('hide');","500"); 
    </script>
    <?php
}

?>

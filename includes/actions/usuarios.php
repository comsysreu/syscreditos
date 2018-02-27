 <?php
session_start();
include("../class.usuarios.php");
include("../class.tipousuarios.php");

$a = $_GET['a'];


if($a=="tablaprincipal"){

$Usuarios= new usuarios();
$tiposusuarios=new tipousuarios();
$registros = $Usuarios->sql("SELECT
	u.idusuario as idusuario,
	tu.nombre as tipo,
	u.nombreusuario as usuario,
	u.nombre as nombre,
	u.estado as estado
	FROM usuarios u
	INNER JOIN tipousuarios tu ON u.idtipousuario= tu.idtipousuario");
	echo "
	<table cellpadding='0' cellspacing='0' border='0' class='table table-striped table-bordered' id='tablaUsuarios'>
		<thead>
			<tr>	
		     <th>#</th>
		     <th>Tipo Usuario</th>		     
		     <th>Nombre</th>
		     <th>Usuario</th>
		     <th>Estado</th>
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
				echo"<td>".$campo['tipo']."</td>";
				echo"<td>".$campo['nombre']."</td>";
				echo"<td>".$campo['usuario']."</td>";
				if($campo['estado']=='1')
					echo"<td><center><img src='../img/active.png' /></center></td>";
				else
					echo"<td><center><img src='../img/deactive.png' /></center></td>";
				
				echo"<td>";
					echo"<a rel='tooltip' title='Modificar' onclick=\" $('#divmodal').load('../includes/actions/usuarios.php?a=editarusuario&idusuario=".$campo['idusuario']."').modal('show'); \" ><i class='icon-edit'></i></a>";

						if($_SESSION['idUsuario']!=$campo['idusuario'])
						

					if($campo['estado']=='1')
							echo"<a rel='tooltip' title='Desactivar' onclick=\" $('#divmodal').load('../includes/actions/usuarios.php?a=desactivarusuario&idusuario=".$campo['idusuario']."').modal('show'); \" ><i class='icon-ban-circle'></i></a>";
					else
						echo"<a rel='tooltip' title='Activar' onclick=\" $('#divmodal').load('../includes/actions/usuarios.php?a=activarusuario&idusuario=".$campo['idusuario']."').modal('show'); \" ><i class='icon-ok-circle'></i></a>";
					//echo"<a rel='tooltip' title='eliminar' onclick=\" $('#divmodal').load('../includes/actions/usuarios.php?a=eliminarusuario&idUsuario=".$campo['idUsuario']."').modal('show'); \" ><i class='icon-remove'></i></a>";
				echo"</td>";
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
if($a=="createUser")
{
	$usuarios= new usuarios();
	$registros= $usuarios->getRecords();
	$pass= $_POST['pass'];
	$pass1= $_POST['pass1'];
	$user= $_POST['usuario'];
	$nombre= $_POST['nombre'];
	$tipo= $_POST['tipousuario'];
	$activo=1;


	foreach($registros as $filas => $campo)
    {
    	if ($campo['nombreusuario'] == $user)
    		$existe = 1;	
    }
    
    if ($existe != 1)
    {
		if ($pass == $pass1)
		{
			if($usuarios->insertRecord(array($nombre,$user,$pass,$activo,$tipo)))
			{
				$usuarios->showMessage("Exito!", "Usuario Creado");
				echo"<script>setTimeout(\"$('#divmodal').modal('hide'); location.reload();\",1500); </script>";
			}
			else
			{
				$usuarios->showMessage('Fallo','Error al crear usuario, verifique si no existe!','error');
			}
		}
		else
		{
			$usuarios->showMessage("Error!", "Las Contraseña no coinciden. verifique",'error');		
		}
	}
	else{
		$usuarios->showMessage("Error!", "Usuario duplicado. verifique",'error');
	}

    
}
else
	if($a=="nuevouser"){
    $tipousuarios=new tipousuarios();
	$registro = $tipousuarios->getRecords();

	echo"
	<!-- Formulario de Informacion -->
	<form class='form-horizontal' action='../includes/actions/usuarios.php?a=createUser' id='formNewUser' method='post'>
			<div class='modal-header'>
				<a class='close' data-dismiss='modal'>×</a>
				<h3>Nuevo Usuario</h3>
			</div>
			<div class='modal-body'>
				<fieldset>
					<div class='control-group'>
						<label class='control-label' for='tipousuario'>Tipo: </label>
						<div class='controls'>
						<select id='tipousuario' class='input-xlarge required' name='tipousuario'>
							<option value='' >Seleccione.. </option>"; 	
							foreach ($registro as $key => $campo) {
								echo"<option value='".$campo['idtipousuario']."'>".$campo['nombre']."</option>";
							}
							echo"</select>
						</div>
					</div>

					<div class='control-group'>
						<label class='control-label' for='nombre'>Nombre: </label>
						<div class='controls'>
							<input class='input-xlarge required' id='nombre' name='nombre'  type='text'>
						</div>
					</div>

					<div class='control-group'>
						<label class='control-label' for='usuario'>Usuario: </label>
						<div class='controls'>
							<input class='input-xlarge required' id='usuario' name='usuario'  type='text'>
						</div>
					</div>

					<div class='control-group'>
						<label class='control-label' for='pass'>Contraseña: </label>
						<div class='controls'>
							<input class='input-xlarge required'   id='pass' name='pass'  type='password'>
						</div>
					</div>

					<div class='control-group'>
						<label class='control-label' for='pass1'>Confirmar Contraseña: </label>
						<div class='controls'>
							<input class='input-xlarge required1'   id='pass1' name='pass1'  type='password'>
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
	if($a=="editusuario"){
		$id = $_REQUEST['idusuario'];
		$usuarios= new usuarios();
		$tipousuario= $_REQUEST['tipousuarioeditar'];
		$usuario = $_REQUEST['usuarioeditar'];
		$clave = $_REQUEST['claveeditar'];
		
		if($clave==""){
			if($usuarios->sqlTrans("UPDATE usuarios SET nombreusuario='".$usuario."', idtipousuario='".$tipousuario."' WHERE idusuario= $id"))
			{
				$usuarios->showMessage("Exito!", "Usuario Editado");
				echo"<script>setTimeout(\"$('#divmodal').modal('hide'); location.reload();\",1500); </script>";
			}
			else
			{
				$usuarios->showMessage('Fallo','Error al editar el usuario','error');
			}
		}
		else
			{
				if($usuarios->sqlTrans("UPDATE usuarios SET nombreusuario='".$usuario."', password='".$clave."', idtipousuario='".$tipousuario."' WHERE idusuario= $id"))
				{
					$usuarios->showMessage("Exito!", "Usuario Editado");
					echo"<script>setTimeout(\"$('#divmodal').modal('hide'); location.reload();\",1500); </script>";
				}
				else
				{
					$usuarios->showMessage('Fallo','Error al editar el usuario','error');
				}
			}
	}
	else
	if($a=="editarusuario"){
		$id=$_REQUEST['idusuario'];
		$usuarios= new usuarios();
		$usuario= $usuarios->getRecords("idusuario=$id");
		$tipousuarios=new tipousuarios();
		$registro = $tipousuarios->getRecords();
				
	echo"
	<!-- Formulario de Informacion -->
	<form class='form-horizontal' action='../includes/actions/usuarios.php?a=editusuario&idusuario=$id' id='formNewUsuario' method='post'>
			<div class='modal-header'>
				<a class='close' data-dismiss='modal'>×</a>
				<h3>Editar Usuario</h3>
			</div>
			
			<div class='modal-body'>
				<fieldset>
				
					<div class='control-group'>
						<label class='control-label' for='tipousuarioeditar'>Tipo: </label>
						<div class='controls'>
							<select id='tipousuarioeditar' class='input-xlarge required' name='tipousuarioeditar'>
							<option value='' >Seleccione.. </option>"; 	
							foreach ($registro as $key => $campo) {
								if($usuario[0]['idtipousuario']==$campo['idtipousuario'])
									echo"<option value='".$campo['idtipousuario']."' selected>".$campo['nombre']."</option>";
								else
									echo"<option value='".$campo['idtipousuario']."'>".$campo['nombre']."</option>";
							}
							echo"</select>
						</div>
					</div> 
					
					<div class='control-group'>
						<label class='control-label' for='usuarioeditar'>Usuario: </label>
						<div class='controls'>
							<input class='input-xlarge required'   id='usuarioeditar' value='".$usuario[0]['nombreusuario']."' name='usuarioeditar'  type='text' >
						</div>
					</div>

					<div class='control-group'>
						<label class='control-label' for='claveeditar'>Contraseña: </label>
						<div class='controls'>
							<input class='input-xlarge'   id='claveeditar' name='claveeditar'  type='password'>
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
	 	validator = $(\"#formNewUsuario\").validate();
	</script>
	";
}
else
	if($a=="deactiveusuario"){
		$id = $_REQUEST['idusuario'];
		$usuarios= new usuarios();
		$activo=0;
		if($usuarios->sqlTrans("UPDATE usuarios SET estado='".$activo."' WHERE idusuario= $id"))
		{
			$usuarios->showMessage("Exito!", "Usuario desactivado");
			echo"<script>setTimeout(\"$('#divmodal').modal('hide'); location.reload();\",1500); </script>";
		}
		else
		{
			$usuarios->showMessage('Fallo','Error al desactivar el usuario','error');
		}
	}
	else
if($a=="desactivarusuario"){
		$id=$_REQUEST['idusuario'];
		$usuarios= new usuarios();
		$usuario= $usuarios->getRecords("idusuario=$id");
		$tipousuarios=new tipousuarios();
		$registro = $tipousuarios->getRecords();

	
		
	echo"
	<!-- Formulario de Informacion -->
	<form class='form-horizontal' action='../includes/actions/usuarios.php?a=deactiveusuario&idusuario=$id' id='formNewUsuario' method='post'>
			<div class='modal-header'>
				<a class='close' data-dismiss='modal'>×</a>
				<h3>Desactivar Usuario</h3>
			</div>
			<div class='modal-body'>
				<fieldset>
					<h3>Esta seguro de desactivar el usuario...</h3>
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
	 	validator = $(\"#formNewUsuario\").validate();
	</script>
	";

}
else
	if($a=="activeusuario"){
		$id = $_REQUEST['idusuario'];
		$usuarios= new usuarios();
		$activo=1;
		if($usuarios->sqlTrans("UPDATE usuarios SET estado='".$activo."' WHERE idusuario= $id"))
		{
			$usuarios->showMessage("Exito!", "Usuario activado");
			echo"<script>setTimeout(\"$('#divmodal').modal('hide'); location.reload();\",1500); </script>";
		}
		else
		{
			$usuarios->showMessage('Fallo','Error al activar el usuario','error');
		}
	}
	else
if($a=="activarusuario"){
		$id=$_REQUEST['idusuario'];
		$usuarios= new usuarios();
		$usuario= $usuarios->getRecords("idusuario=$id");
		$tipousuarios=new tipousuarios();
		$registro = $tipousuarios->getRecords();
		
	echo"
	<!-- Formulario de Informacion -->
	<form class='form-horizontal' action='../includes/actions/usuarios.php?a=activeusuario&idusuario=$id' id='formNewUsuario' method='post'>
			<div class='modal-header'>
				<a class='close' data-dismiss='modal'>×</a>
				<h3>Activar Usuario</h3>
			</div>
			<div class='modal-body'>
				<fieldset>
					<h3>Esta seguro de activar el usuario...</h3>
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
	 	validator = $(\"#formNewUsuario\").validate();
	</script>
	";
}
if($a=="deleteusuario")
{
	$id = $_REQUEST['idUsuario'];
	
	$usuarios= new usuarios();

	if($usuarios->sqlTrans("DELETE FROM usuarios  WHERE idUsuario= $id"))
	{
		
				$usuarios->showMessage("Exito!", "Usuario Eliminado"); 
			echo"<script>setTimeout(\"$('#divmodal').modal('hide'); location.reload();\",1500); </script>";
			
	}
	else
	{
		echo"".$id."";
		$usuarios->showMessage('Fallo','Error al eliminar el usuario','error'); 
	}

}
else
	if($a=="eliminarusuario")
	{
		$id = $_REQUEST['idUsuario'];
				
		echo"
	<!-- Formulario de Informacion -->
	<form class='form-horizontal' action='../includes/actions/usuarios.php?a=deleteusuario&idUsuario=$id' id='formDeleteUser' method='post'>
			<div class='modal-header'>
				<a class='close' data-dismiss='modal'>×</a>
				<h3>Eliminar Usuario</h3>
			</div>
			<div class='modal-body'>
				<fieldset>
					<h3>¿Esta seguro de eliminar el usuario?</h3>						
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
	 	validator = $(\"#formDeleteUser\").validate();
	</script>
	";
	}
?>
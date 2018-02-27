<?php
	session_start();
	include ("class.usuarios.php");
	$usuarios=new usuarios ();
	$usuario = $_REQUEST['username'];
	$clave = $_REQUEST['password'];
	$activo=1;
	$registros = $usuarios->getRecords("nombreusuario='$usuario' and password='$clave' and estado='$activo'");

	if (count ($registros)>0)
	{
		$_SESSION['idtipousuario']=$registros[0]['idtipousuario'];
		$_SESSION['nombre']=$registros[0]['nombre'];
		$_SESSION['idUsuario']=$registros[0]['idUsuario'];
							
			echo "<script>setTimeout(\"window.location='layouts/principal.html'\",1000);
			$('.form').css('box-shadow','inset 0 0 10px 2px rgba(0,255,0,0.25), 0 0 50px 10px rgba(0,255,0,0.3)');
			</script>";
	}

	else
	{ 
		if($_SESSION['activo'] == 1){
	$usuarios->showMessage('Error','Usuario o Password incorrecto','error');
	echo "<script> $('#frmLogin').effect('shake', { times:3 }, 70);
	setTimeout(\"$('.alert').fadeIn(1000)\",500)
	$('.form').css('box-shadow','inset 0 0 10px 2px rgba(255,0,0,0.25), 0 0 50px 10px rgba(255,0,0,0.7)');
	</script>";
	}
	else{
	$usuarios->showMessage('Error','El Usuario no existe','error');
	echo "<script> $('#frmLogin').effect('shake', { times:3 }, 70);
	setTimeout(\"$('.alert').fadeIn(1000)\",500)
	$('.form').css('box-shadow','inset 0 0 10px 2px rgba(255,0,0,0.25), 0 0 50px 10px rgba(255,0,0,0.7)');
	</script>";
	}
	}
?>
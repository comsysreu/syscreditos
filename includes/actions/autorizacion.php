<?php
session_start();
include("../class.usuarios.php");

$a = $_GET['a'];
$usu=$_SESSION['idUsuario'];

if($a == "autorizacion"){
        echo"
        <!-- Formulario de Informacion -->
        <form class='form-horizontal' action='../includes/actions/autorizacion.php?a=autorizado' id='frmAnular' method='post'>
            <div class='modal-header'>
                <a class='close' data-dismiss='modal'>×</a>
                <h3>Registrar abonos</h3>
            </div>
            <div class='modal-body'>
                <fieldset>
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
                    <button type='button' onClick=\"$('#divmodalPassword').modal('hide');\"  class='btn'>No</button>
                </div>
            </div>
        </form>
        <script>
            validator = $(\"#frmAnular\").validate();

            $(document).ready(function(){
				window.onhashchange = function(){
					if (typeof (window.stop) != 'undefined')
						window.stop();
					if (typeof (document.execCommand) != 'undefined')
						document.execCommand('Stop');
					if (document.location.hash.length < 10)
					history.go(1);
				};
 
				document.location += \"#\";
				for(i = 0; i < 10; i++)
					document.location += \"@\";
				});
        </script>
        ";
    }
    else if ($a == "autorizado"){
        $password = $_POST['password'];
        $objUsuarios = new usuarios();
        $regUsuarios = $objUsuarios->getRecords("idusuario='$usu' and password='$password'");

        if (!$regUsuarios){
            $objUsuarios->showMessage('Error!', 'Usuario no autorizado!','error');
        }
        else{
        	echo "<script>setTimeout(\"window.location='../layouts/pagocreditos.html'\",100);</script>";
        }
     }
?>
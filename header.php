<?php
session_start(); if(!$_SESSION["idUsuario"]) { $_SESSION['redirect'] = 'http://'.$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI']; header ("Location: ../index.html"); }
include("../includes/class.usuarios.php");
$_SESSION['idtipousuario'];
?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
		<title>SysCredit 1.0</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
    	<meta name="description" content="SysCredit">
    	<meta name="author" content="tyd-web">
    	
        <!--Estilos bootstrap-->
      <link rel="shortcut icon" href="../img/icono.ico">
    	<link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    	<link href="../css/ui-lightness/jquery-ui.css" rel="stylesheet">

    	
    	<style type="text/css">
    		body{
    			padding-top: 60px;
    			padding-bottom: 40px;
    		}
    		.sidebar-nav {
    			padding: 9px 0;
  			}
    	</style>

    	<link rel="stylesheet" type="text/css" href="../css/bootstrap-responsive.css">
    	<link rel="stylesheet" type="text/css" href="../css/DT_bootstrap.css">
        <link href="../css/style.css" rel="stylesheet">
        
    	<script src="../js/jquery.js"></script>
        <script src="../js/jquery.blockUI.js"></script>
        
    	<script type="text/javascript" src="../js/jquery.ui.datepicker.js"></script>
    	    	<!--Falta el shortcut icon-->

    	<script type="text/javascript">

                function bloquearUi() { 
                $.blockUI({ 
                    message: $('#mensaje'),
                    css: { 
                      border: 'none', 
                      padding: '15px', 
                      
                      backgroundColor:'transparent', 
                      '-webkit-border-radius': '10px', 
                      '-moz-border-radius': '10px', 
                       color: '#fff' 
                      } 
                    });
             
                
                }
                function desbloquearUi()  {
                    setTimeout($.unblockUI, 500); 

                }

    		$(document).ready(function() {
                

        		$.validator.setDefaults({
            		submitHandler: function(form) {
                		var idForm = $(form).attr('id');
                		$.post($(form).attr('action'), $(form).serialize(), function(data) {
                    		$("#"+idForm+" .response").html(data);
                		});
            		}
        		});
    		});
        </script>

	</head>
	<body>
        <div id='mensaje' style='display:none;'> <!--<img src="img/loader1.gif" width="48" height="48" />-->
        <div class="blockMsg">Cargando espere un momento<br><br><br></div>
    <div class="circle"></div>
    <div class="circle1"></div>
    </div>
		<div class="navbar navbar-inverse navbar-fixed-top"><!-- Inicio Barra Negra-->
			<div class="navbar-inner">
				<div class="container-fluid">
					<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
            			<span class="icon-bar"></span>
            			<span class="icon-bar"></span>
					</button>
                    
					<a class="brand1" href="#">SysCredit</a>
					<div class="nav-collapse collapse">
			            <ul class="nav">
			            	                                

                                                        
			            </ul>
			            <ul class="nav pull-right">
              				<li class="dropdown"><a href="#"  class="dropdown-toggle"  data-toggle="dropdown">Opciones<b class="caret"></b></a>
                				<ul class="dropdown-menu">
                                    <!--<li><a id="cpass" name="cpass" href="#">Cambiar Password</a></li>-->
                  					<li><a href="../index.html?a=logout"><i class="icon-off"> </i> Cerrar Sesión</a></li>
                				</ul>
              				</li>
            			</ul>
                         <!-- Obtiene el nombre del usuario-->
                         <?php 
                         $usuarios= new usuarios();
                         $registros=$usuarios->getRecords();
                         foreach ($registros as $key => $campo) {
                                if($_SESSION['idUsuario']==$campo['idUsuario'])
                                    $nombre=$campo['nombre'];
                          } 
                         ?>

            			<p class="navbar-text pull-right">Bienvenido, <a style="color: #ffffff" href="#"><?php echo $nombre ?></a></p>      
			        </div>
				</div>
			</div>
		</div><!-- Fin Barra Negra-->
        
        <div class="modal hide fade in" id="MostrarDatos"></div>
        <div class="modal hide fade in" id="divmensajes"></div>
        <div class="modal hide fade in" id="divcambiarpass"></div>
        <script type="text/javascript">       
            
            
            $("#acercade").click(function () {     
                $("#divmensajes").load("includes/actions/acercaDe.html?a=ver").modal('show');
            });

            $("#cpass").click(function () {     
                $("#divcambiarpass").load("includes/actions/usuarios.html?a=cambiarpass&id=" + <?php echo $_SESSION['idusuario'] ?>).modal('show');
            });
            $("#cCicloActual").click(function () {     
                $("#divCambiarCiclo").load("includes/actions/usuarios.html?a=cambiarCiclo").modal('show');
            });
            
        </script>
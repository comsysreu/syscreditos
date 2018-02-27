<?php 
session_start(); error_reporting(0);
if($_GET['a']=="logout")
{
  session_destroy();
  header ("Location: index.html");
}
?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<!--<meta charset="utf-8">-->
    <meta charset=utf-8>
		<title>SysCredit 1.0</title>
    <link rel="shortcut icon" href="img/icono.ico">
		<!-- css -->
		<link rel="stylesheet" href="css/login.css" media="screen" type="text/css" >
		<link href="css/bootstrap.css" rel="stylesheet">
		<!-- js -->
		<script src="js/prefixfree.min.js"></script>
		<script src="js/jquery.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
  
		<script src="js/bootstrap-transition.js"></script>
    <script src="js/bootstrap-alert.js"></script>
  	<script type="text/javascript">
  		$(document).ready(function() {
    		$("#frmLogin").submit(function(event) {
      		event.preventDefault(); 
      		var $form = $( this ),
      		url = $form.attr( 'action' );
      		$.post( url,  $form.serialize(),
      		function(data) 
          {
          	$("#frmLogin .response").html(data);
        	}
          );                  
    	 });
  		});
  	</script>
	</head>
	<body>
        <div id="logo"><center><img src="img/logo.png"></center></div>
		    <div id="wrapper" class="container">
   			        
    		<form class="form rounded-5 shadow "  id="frmLogin" method="post" action="includes/login.html?a=login">
     			<input id="username" required  type="text" name="username" placeholder="Usuario" />
          <input id="password" required type="password" name="password" placeholder="Contraseña" />
          <input class="btn  btn-success" type="submit" value="Login" />
     			<div class="response" ></div>
   			</form>
   		</div>
    	</body>
</html>

<?php
function ObtenerNavegador($user_agent) {
  $ie= strpos($user_agent,"MSIE");
  return $ie;
}
  
?>

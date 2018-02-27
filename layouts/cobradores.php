<?php 
include("../header2.php");
if($_SESSION['idtipousuario']==1 || $_SESSION['idtipousuario']==2) {
?>
	<div class="container" id="contenido" name="contenido">
		<div class="span2">                                            
      		<img src='../img/escritorio128x128/cobradores.png' width="90" height="90" class=""> 
    	</div>
  		<div class="hero-unit">
   			<h2 style="text-indent:220px;">Cobradores</h2>
 		</div>		
		<br>
		<aside>
			<div  id="divtablaprincipal" name="divtablaprincipal"></div>
			<div id="divTablaClientes" name="divTablaClientes"></div>	
			<div class="  modal hide fade in" data-backdrop="static" id="divmodal" name="divmodal"></div>
		</aside>
	</div>
	<script type="text/javascript">
 		$(document).ready(function() {
		$("#divtablaprincipal").load('../includes/actions/cobradores.html?a=tablaprincipal');
		desbloquearUi();

		function limpiarTablaDetalle(){
        	$("#divTablaClientes").html("");
    	}
	});
	</script>
<?php 
} 
else
	echo"<center><img src='../img/denegado.jpg' class=''/></center>";
include("../footer.php");
?>
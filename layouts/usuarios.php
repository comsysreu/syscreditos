<?php 
include("../header2.php");
if($_SESSION['idtipousuario']==1) {
?>

<div class="container" id="contenido" name="contenido">
	<div class="span2">                                            
      <img src='../img/escritorio128x128/usuarios.png' width="90" height="90" class=""> 
    </div>
  <div class="hero-unit">
		
    <h2 style="text-indent:220px;">Usuarios SysCredit</h2>
 </div>
<div class="btn-toolbar" >
	<div class="btn-group" >
		<a data-toggle="modal" onclick="$('#divmodal').load('../includes/actions/usuarios.html?a=nuevouser').modal('show');" id="btnNewUsuario" name="btnNewUsuario" class="btn btn-success btn-medium">Nuevo usuario</a>
	</div>
</div><br>
<aside>
	<div  id="divtablaprincipal" name="divtablaprincipal"></div>
	<div class="  modal hide fade in" data-backdrop="static" id="divmodal" name="divmodal"></div>
</aside>

</div>

<script type="text/javascript">
 


$(document).ready(function() {

	$("#divtablaprincipal").load('../includes/actions/usuarios.html?a=tablaprincipal');
	desbloquearUi();
	
});
</script>

<?php 
} 
else
	echo"<center><img src='../img/denegado.jpg' class=''/></center>";
include("../footer.php");
?>
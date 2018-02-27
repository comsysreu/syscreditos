<?php  
include("../header2.php");
if($_SESSION['idtipousuario']==1 || $_SESSION['idtipousuario']==2 || $_SESSION['idtipousuario']==3) {
?>

<div class="container" id="contenido" name="contenido">
	<div class="span2">                                            
      <img src='../img/escritorio128x128/planes.png' width="90" height="90" class=""> 
    </div>
  <div class="hero-unit">
		
     <h2 style="text-indent:220px;">Planes de Créditos</h2>
 </div>
<?php if($_SESSION['idtipousuario']==1){?>
<div class="btn-toolbar" >
	<div class="btn-group" >
		<a data-toggle="modal" onclick="$('#divmodal').load('../includes/actions/planes.html?a=nuevoplan').modal('show');" id="btnNewPlan" name="btnNewPlan" class="btn btn-success btn-medium">Nuevo Plan</a>
	</div>
	<div class="btn-group" >
		<a data-toggle="modal" onclick="$('#divmodal').load('../includes/actions/montos.php?a=nuevomonto').modal('show');" id="btnNewMonto" name="btnNewMonto" class="btn btn-primary btn-medium">Nuevo Monto</a>
	</div>
	<div class="btn-group" >
		<a data-toggle="modal" onclick="$('#divmodal').load('../includes/actions/planes.html?a=verplandesactivados').modal('show');" id="btnNewPlan" name="btnNewPlan" class="btn btn-danger btn-medium">Planes Desactivados</a>
	</div>
</div>
<?php
}
?>
<br>
<aside>
	<div  id="divtablaprincipal" name="divtablaprincipal"></div>
	<div class="  modal hide fade in" data-backdrop="static" id="divmodal" name="divmodal"></div>
	<div class="  modal hide fade in" data-backdrop="static" id="divmodal2" name="divmodal2"></div>
</aside>

</div>

<script type="text/javascript">
 


$(document).ready(function() {

	$("#divtablaprincipal").load('../includes/actions/planes.html?a=tablaprincipal');
	desbloquearUi();
	
});
</script>

<?php 
} 
else
	echo"<center><img src='../img/denegado.jpg' class=''/></center>";
include("../footer.php");
?>
<?php	
  
	include("../header.php");
  
$_SESSION['idtipousuario'];
$_SESSION['flagclientes']=0;

?>

<div class="container">
	<aside>
<div class="jumbotron masthead">
<div class="hero-unit1">
	<center><img src="../img/logoprincipal.png"  /></center>	
</div>
</div>
<ul class="thumbnails">
    <?php if($_SESSION['idtipousuario']==1 || $_SESSION['idtipousuario']==2){  ?>    
    <li class='span3'>
      <a rel="tooltip"  class="btn thumbnail" onclick="$('#divmodalNuevoCliente').load('../includes/actions/clientes.php?a=nuevocliente').modal('show');" ></i>
        <img src="../img/escritorio128x128/creditos.png" class=""/>
        <p><b>Nuevo Crédito</b></p>
      </a>
    </li>

    <li class="span3">
      <a rel="tooltip"  class="btn thumbnail" onclick="$('#divmodalPassword').load('../includes/actions/autorizacion.php?a=autorizacion').modal('show');" ></i>
        <img src='../img/escritorio128x128/abonos.png' class=""/>
        <p><b>Registrar Abonos</b></p>
      </a>
    </li>
    <li class="span3">
      <a href="historial.html" class="btn thumbnail" onclick="bloquearUi();">
        <img src='../img/escritorio128x128/reportes2.png' class=""/>
        <p><b>Historial de Pagos</b></p>
      </a>
    </li>
    <?php } if($_SESSION['idtipousuario']==1 || $_SESSION['idtipousuario']==2 || $_SESSION['idtipousuario']==3){  ?>    
    <li class="span3">
      <a href="clientes.php" class="btn thumbnail" onclick="bloquearUi();">
        <img src='../img/escritorio128x128/clientes.png' class=""/>
        <p><b>Clientes</b></p>
      </a>
    </li>

    <li class="span3">
      <a href="planes.html" class="btn thumbnail" onclick="bloquearUi();">
        <img src='../img/escritorio128x128/planes.png' class=""/>
        <p><b>Planes</b></p>
      </a>
    </li>

    <?php }if($_SESSION['idtipousuario']==1 || $_SESSION['idtipousuario']==2){  ?> 
     <li class="span3">
      <a href="cobradores.html" class="btn thumbnail" onclick="bloquearUi();">
        <img src='../img/escritorio128x128/cobradores.png' class=""/>
        <p><b>Cobradores</b></p>
      </a>
    </li>

    <?php } if($_SESSION['idtipousuario']==1){  ?>    
    <li class="span3">
      <a href="reportes.html" class="btn thumbnail" onclick="bloquearUi();">
        <img src='../img/escritorio128x128/reportes.png' class=""/>
        <p><b>Reportes</b></p>
      </a>
    </li>

    <li class="span3">
      <a href="usuarios.html" class="btn thumbnail" onclick="bloquearUi();">
        <img src='../img/escritorio128x128/usuarios.png' class=""/>
        <p><b>Usuarios</b></p>
      </a>
    </li>
    <?php } ?>

<aside>
  <div  id="divtablaprincipal" name="divtablaprincipal"></div>
  <div class="  modal hide fade in" data-backdrop="static" id="divmodalNuevoCliente" name="divmodal"></div>
  <div class="  modal hide fade in" data-backdrop="static" id="divmodalPassword" name="divmodalPassword"></div>
  <div class="  modal hide fade in" data-backdrop="static" id="divmodal2" name="divmodal2"></div>
  <div class="  modal hide fade in" data-backdrop="static" id="divmodal3" name="divmodal3"></div>
  <div class="  modal hide fade in" data-backdrop="static" id="divmodal4" name="divmodal4"></div>
</aside>

</ul>
</aside>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$('#contenido').css({'margin-left':'auto'});
  desbloquearUi();

});
</script>
<?php 
include("../footer.php");
?>
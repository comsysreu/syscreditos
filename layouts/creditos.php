<?php 
include("../header2.php");
if($_SESSION['idtipousuario']==1) {
?>
<div class="container" id="contenido" name="contenido">
	<div class="hero-unit">
		<center><h2>PRESTAMOS</h2></center>
	</div>

<ul class="thumbnails">  
    <center>
    <li class="span3">
      <a href="creditos.html" class="btn thumbnail" onclick="bloquearUi();">
        <img src='../img/escritorio128x128/creditos.png' class=""/>
        <p><b>Créditos</b></p>
      </a>
    </li>
  
    <li class="span3">
      <a href="clientes.html" class="btn thumbnail" onclick="bloquearUi();">
        <img src='../img/escritorio128x128/clientes.png' class=""/>
        <p><b>Clientes</b></p>
      </a>
    </li>
    </center>
    <?php } ?>
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
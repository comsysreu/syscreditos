<?php 
  include("../header2.php");

  if($_SESSION['idtipousuario']==1 || $_SESSION['idtipousuario']==2 || $_SESSION['idtipousuario']==3) {
  ?>

  <div class="container" id="contenido" name="contenido">
  	<div class="span2">                                            
      <img src='../img/escritorio128x128/clientes.png' width="90" height="90" class=""> 
    </div>
    <div class="hero-unit">
      <h2 style="text-indent:220px;">Registro de Clientes</h2>
	  </div>
    <div class="btn-toolbar" >
    </div>
    <aside>
      <div  id="divtablaprincipal" name="divtablaprincipal"></div>
      <div class="  modal hide fade in" data-backdrop="static" id="divmodal" name="divmodal"></div>
      <div class="  modal hide fade in" data-backdrop="static" id="divmodal2" name="divmodal2"></div>
      <div class="  modal hide fade in" data-backdrop="static" id="divmodal4" name="divmodal4"></div>
      <div class="  modal hide fade in" data-backdrop="static" id="divmodalPassword" name="divmodalPassword"></div>
    </aside>
  </div>
  <script type="text/javascript">
    $(document).ready(function() {
    $("#divtablaprincipal").load('../includes/actions/clientes.php?a=tablaprincipal');
    desbloquearUi();
    });
  </script>
  <script type="text/javascript">
    $(document).ready(function() {
	  $('#contenido').css({'margin-left':'auto'});
    desbloquearUi();
    });
  </script>
<?php 
} 
else
  echo"<center><img src='../img/denegado.jpg' class=''/></center>";
include("../footer.php");
?>
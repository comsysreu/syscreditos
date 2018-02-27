<?php 
  include("../header2.php");

  if($_SESSION['idtipousuario']==1 || $_SESSION['idtipousuario']==2 || $_SESSION['idtipousuario']==3) {
  ?>
  <script type="text/javascript">
  function buscar(){
    $("#divtablaprincipal").load('../includes/actions/historial.php?a=tablaprincipal&fecha='+$("#fecha").val());
    desbloquearUi();
  }
  </script>
  <div class="container" id="contenido" name="contenido">
  	<div class="span2">                                            
      <img src='../img/escritorio128x128/reportes2.png' width="90" height="90" class=""> 
    </div>
    <div class="hero-unit">
      <h2 style="text-indent:220px;">Historial de pagos</h2>
	  </div>
    <div class="btn-toolbar" >
    </div>
    <aside>
    <div class='form-horizontal'>
      <label>Fecha Inicio: </label>

        <input class='datepicker input-medium required' id='fecha'  name='fecha' type='text' placeholder='Click para seleccionar'>
        <button type='button' onClick="buscar()"  class='btn btn-info'>Buscar</button>

    </div>
    <br>
      <div  id="divtablaprincipal" name="divtablaprincipal"></div>
      <div class="  modal hide fade in" data-backdrop="static" id="divmodal" name="divmodal"></div>
      <div class="  modal hide fade in" data-backdrop="static" id="divmodal2" name="divmodal2"></div>
      <div class="  modal hide fade in" data-backdrop="static" id="divmodal4" name="divmodal4"></div>
      <div class="  modal hide fade in" data-backdrop="static" id="divmodalPassword" name="divmodalPassword"></div>
    </aside>
  </div>

  <script type="text/javascript">
  $(document).ready(function() {
    $('#fecha').datepicker({
        locale: 'no',
        dateFormat: 'yy-mm-dd'
    });

    $('#contenido').css({'margin-left':'auto'});

  });
</script>

<?php 
} 
else
  echo"<center><img src='../img/denegado.jpg' class=''/></center>";
include("../footer.php");
?>
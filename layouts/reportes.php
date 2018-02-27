<?php 
include("../header2.php");
if($_SESSION['idtipousuario']==1) {
?>
<div class="container" id="contenido" name="contenido">
  <div class="hero-unit">
    <center><h2>Reportes Generales</h2></center>
  </div>
<br>
<ul class="thumbnails">
   <li class='span3'>
      <a rel="tooltip"  class="btn thumbnail" onclick="$('#divmodal').load('../includes/actions/reportes.html?a=tiporeportediario').modal('show');" ></i>
        <img src="../img/escritorio128x128/reportes2.png" class=""/>
        <p><b>Reporte Diario</b></p>
      </a>
    </li>

    <li class='span3'>
      <a rel="tooltip"  class="btn thumbnail" onclick="$('#divmodal2').load('../includes/actions/reportes.html?a=tiporeportemensual').modal('show');" ></i>
        <img src="../img/escritorio128x128/reportes2.png" class=""/>
        <p><b>Reporte Mensual</b></p>
      </a>
    </li>
<aside>
  <div  id="divtablaprincipal" name="divtablaprincipal"></div>
  <div class="  modal hide fade in" data-backdrop="static" id="divmodal" name="divmodal"></div>
  <div class="  modal hide fade in" data-backdrop="static" id="divmodal2" name="divmodal2"></div>
</aside>

</ul>
</div>


<?php 
} 
else
  echo"<center><img src='../img/denegado.jpg' class=''/></center>";
include("../footer.php");
?>
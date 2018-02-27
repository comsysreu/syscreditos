<?php 
include("../header2.php");
include("../class.usuarios.php");


$nomUs=$_SESSION['nombre'];

$fecha=date('d-M-Y');


if($_SESSION['idtipousuario']==1 || $_SESSION['idtipousuario']==2 || $_SESSION['idtipousuario']==3){
?> 
        	<div align="center">
            	<table width="95%">
                  <tr>
                    <td>            
                    	<table class="table table-bordered">
                          <tr class="hero-unit">
                            <td>
                           	    <div class="row-fluid">
                                    <h2 style="margin:0 0 0 30px">Abonos</h2>             
                                    <div class="span2">                                             
                                       <img src='../img/escritorio128x128/abonos.png' width="90" height="90" class=""> 
                                    </div>
        	                        <div class="span6">
                                    	<form name="formulario" onsubmit="#"  method="post">
                                            <strong>DPI o Nombre del Cliente</strong><br>                                    
                                            <input type="text" id="imp" placeholder='Escriba el nombre completo o DPI sin espacios...' autofocus list="browsers" name="buscar" on autocomplete="off" class="input-xxlarge" required>
                                            <datalist id="browsers">
                                                <?php
                                                    $pa=mysql_query("SELECT cli.nombrecompleto as nombre FROM creditos cre INNER JOIN clientes cli ON cre.idcliente = cli.idcliente WHERE cre.estado=1");				
                                                    while($row=mysql_fetch_array($pa)){
                                                        echo '<option value="'.$row['nombre'].'">';
                                                    }
                                                ?> 
                                            </datalist>                                    
                                        </form>
                                    </div>                             
            	                    <div class="span3"> 
                                        <i class="icon-ok"></i> <strong>Registrador: </strong><?php echo $nomUs; ?><br>
                                        <i class="icon-ok"></i> <strong>Fecha: </strong> <?php echo $fecha; ?><br>                                                                                                
                                    </div>
                                </div>
                            </td>
                          </tr>
                        </table>               
                        </br>
                        <div class="row-fluid">            
            	            <div class="span8">
                              	<div id="divtablapagos" style="width:100%; height:700px; overflow: auto;"></br>                               
                                </div>
                            </div>
                            <div id="divtabladerecha"class="span4">
                            </div>
                        </div>                
                    </td>
                  </tr> 
                </table>
                <div class="modal hide fade in" data-backdrop="static" id="divmodal" name="divmodal"></div>
            </div>
        
            <script type="text/javascript">
            $(document).ready(function() {
                $("#divtabladerecha").load('../includes/actions/pagocreditos.html?b=tabladerecha');
                desbloquearUi();
                });
            </script>
           
            <?php

            if($_POST['buscar']){
                $_SESSION['id']=$_POST['buscar'];
                $_SESSION['cuota']=$_POST['buscar'];
                
                ?>
                <script type="text/javascript">
                $(document).ready(function() {
                            $("#divtablapagos").load('../includes/actions/pagocreditos.html?a=tablaprincipalcreditos');
                             desbloquearUi();
                            });
                </script>
                <?php
            }   
}
else
    echo"<center><img src='../img/denegado.jpg' class=''/></center>";
include("../footer.php");
?>
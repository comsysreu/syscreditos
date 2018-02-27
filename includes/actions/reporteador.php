<?php 
	require_once("dompdf/dompdf_config.inc.php");
	include("../class.clientes.php");
  include("../class.creditos.php");
  include("../class.planes.php");
  include("../class.montos.php");
  include("../class.usuarios.php");
  include("../class.detallecreditos.php");

    
$a = $_GET['a'];

if($a == "reporteclientes"){
$codigoHTML='
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>SysCredit 1.0</title>
</head>
<body>
  <img src="../../img/credito1.png"/>
  <div align="center"><h1>Reporte de Clientes</h1></div><p>
  <div align="center">    
    <table width="100%" border="0">
      <tr>
        <td bgcolor="#81BEF7"><strong>#</strong></td>
        <td bgcolor="#81BEF7"><strong>Nombre del Cliente</strong></td>
        <td bgcolor="#81BEF7"><strong>DPI</strong></td>
        <td bgcolor="#81BEF7"><strong>Dirección</strong></td>
        <td bgcolor="#81BEF7"><strong>Teléfono</strong></td>
      </tr>';
        $con=0;
        $consulta=mysql_query("SELECT idcliente, nombrecompleto, dpi, direccion, telefono FROM clientes ORDER BY idcliente ASC");
        while($dato=mysql_fetch_array($consulta)){
          $con++;
$codigoHTML.='
      <tr>
        <td>'.$con.'</td>
        <td>'.$dato['nombrecompleto'].'</td>
        <td>'.$dato['dpi'].'</td>
        <td>'.$dato['direccion'].'</td>
        <td>'.$dato['telefono'].'</td>
      </tr>';
      }
$codigoHTML.='
    </table>
</div>
<br><br>
<b>Fecha:</b> '.date("d/m/Y").'<br>
<b>Hora:</b> '.date("H:i:s").'
</body>
</html>';

$codigoHTML=utf8_decode($codigoHTML);
$dompdf=new DOMPDF();
$dompdf->load_html($codigoHTML);
ini_set("memory_limit","128M");
$dompdf->render();
$dompdf->stream("Clientes");
}
//******************************************************************************************
else
  if($a=="reporteclientesconcredito"){
      $codigoHTML='
      <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
      <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>SysCredit 1.0</title>
      </head>

      <body>
      <img src="../../img/clientesconcredito.png"/><br><br>
      <div align="center">
          <table width="100%" border="0">
            <tr>
              <td bgcolor="#81BEF7"><strong>#</strong></td>
              <td bgcolor="#81BEF7"><strong>Nombre del Cliente</strong></td>
              <td bgcolor="#81BEF7"><strong>DPI</strong></td>
              <td bgcolor="#81BEF7"><strong>Dirección</strong></td>
              <td bgcolor="#81BEF7"><strong>Teléfono</strong></td>
              <td bgcolor="#81BEF7"><strong>Plan</strong></td>
            </tr>';
              $con=0;
              $consulta=mysql_query("SELECT cre.idcredito as idcredito, cli.nombrecompleto as nombrecompleto, cli.dpi as dpi, cli.telefono as telefono, cli.direccion as direccion, pla.idplan as idplan, pla.nombreplan as nombreplan FROM creditos cre INNER JOIN clientes cli ON cre.idcliente = cli.idcliente INNER JOIN planes pla ON cre.idplan = pla.idplan");
              while($dato=mysql_fetch_array($consulta)){
              $con++;
      $codigoHTML.='
            <tr>
              <td>'.$con.'</td>
              <td>'.$dato['nombrecompleto'].'</td>
              <td>'.$dato['dpi'].'</td>
              <td>'.$dato['direccion'].'</td>              
              <td>'.$dato['telefono'].'</td>
              <td>'.$dato['nombreplan'].'</td>
            </tr>';
            } 
      $codigoHTML.='
          </table>
      </div>
      <br><br>
      <b>Fecha:</b> '.date("d/m/Y").'<br>
      <b>Hora:</b> '.date("H:i:s").'
      </body>
      </html>';

      $codigoHTML=utf8_decode($codigoHTML);
      $dompdf=new DOMPDF();
      $dompdf->load_html($codigoHTML);
      ini_set("memory_limit","128M");
      $dompdf->render();
      $dompdf->stream("ClientesConCrédito");
  }
  else
//******************************************************************************************
    if($a=="estadodecuenta"){
      
      $id= $_REQUEST['id3'];

      $codigoHTML='
      <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
      <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>SysCredit 1.0</title>
      </head>
      ';
      $creditos= new creditos();
      $consulta=$creditos->sql("SELECT
          cl.nombrecompleto as nombrecompleto,
          cl.dpi as dpi,
          cl.direccion as direccion,
          cl.telefono as telefono,
          p.nombreplan as nombreplan,
          m.montocapital as montocapital,
          cr.montointeres as montointeres,
          dc.fecha as fecha,
          dc.saldocapital as saldocapital, 
          dc.saldointeres as saldointeres,
          dc.estado as estado
          FROM creditos cr
          INNER JOIN clientes cl ON cr.idcliente = cl.idcliente
          INNER JOIN planes p ON cr.idplan = p.idplan
          INNER JOIN montos m ON cr.idmonto = m.idmonto
          INNER JOIN detallecreditos dc ON dc.idcredito = cr.idcredito
          WHERE dc.idcredito = $id");
      $nombre=$consulta[0]['nombrecompleto'];
      $dpi=$consulta[0]['dpi'];;
      $direccion=$consulta[0]['direccion'];
      $telefono=$consulta[0]['telefono'];
      $plan=$consulta[0]['nombreplan'];
      $montocapital=$consulta[0]['montocapital'];
      $montointeres=$consulta[0]['montointeres'];
      $prestamo=$montocapital+$montointeres;

      $codigoHTML='
      <body>
      <img src="../../img/estadodecuenta.png"/><br><br>
      <div><br>
          <b>Cliente: </b>'.$nombre.'<br>
          <b>DPI: </b>'.$dpi.'<br>
          <b>Dirección: </b>'.$direccion.'<br>
          <b>Teléfono: </b>'.$telefono.'<br>
          <b>Plan: </b>'.$plan.'<br>
          <b>Préstamo: </b>'.$prestamo.'<br><br>
      <div><p>
      <div align="center">
          <table width="100%" border="0">
            <tr>
              <td bgcolor="#81BEF7"><strong>#</strong></td>
              <td bgcolor="#81BEF7"><strong>Fecha de Pago</strong></td>
              <td bgcolor="#81BEF7"><strong>Saldo Capital</strong></td>
              <td bgcolor="#81BEF7"><strong>Saldo Interés</strong></td>
              <td bgcolor="#81BEF7"><strong>Total Saldo</strong></td>
              <td bgcolor="#81BEF7"><strong>Estado</strong></td>
            </tr>';

              $con=0;
              //while($dato=mysql_fetch_array($consulta)){
              foreach($consulta as $filas => $dato) {
              $con++;
              $saldototal=$dato['saldocapital']+$dato['saldointeres'];
              if($dato['estado']=='0'){
                $estadocuota="Cuota Abonada";
              }
              else{
                  $estadocuota="Pendiente de Abono";
                  }
              ;
      $codigoHTML.='
            <tr>
              <td>'.$con.'</td>
              <td>'.$dato['fecha'].'</td>
              <td>Q.'.number_format($dato['saldocapital'],2,"."," ").'</td>
              <td>Q.'.number_format($dato['saldointeres'],2,"."," ").'</td>                   
              <td>Q.'.number_format($saldototal,2,"."," ").'</td>
              <td>'.$estadocuota.'</td>
            </tr>';
            } 
      $codigoHTML.='
          </table>
      </div>
      <br><br>
      <b>Fecha:</b> '.date("d/m/Y").'<br>
      <b>Hora:</b> '.date("H:i:s").'
      </body>
      </html>';

      $codigoHTML=utf8_decode($codigoHTML);
      $dompdf=new DOMPDF();
      $dompdf->load_html($codigoHTML);
      ini_set("memory_limit","128M");
      $dompdf->render();
      $dompdf->stream($nombre);
    }
else
//******************************************************************************************
if($a=="reportediario")
  {
  $fecha= $_REQUEST['fecha'];
    //$fecha= $_POST['fechareporte'];
    //$fecha=date("Y-m-d");
    //$fecha = "<script> document.write(variablejs) </script>";
    //$fecha=$_GET('variablejs');
   $codigoHTML='
      <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
      <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>SysCredit 1.0</title>
      </head>
      ';
      $creditos= new creditos();
      $consulta=$creditos->sql("SELECT
          cl.nombrecompleto as nombrecompleto,
          cl.dpi as dpi,
          cr.cuotadiaria as cuotadiaria,
          cr.idcredito as idcredito
          FROM creditos cr
          INNER JOIN clientes cl ON cr.idcliente = cl.idcliente
          INNER JOIN detallecreditos dc ON cr.idcredito = dc.idcredito
          WHERE dc.fechapago = \"$fecha\" and dc.estado = 0 and dc.estadorenovacion = 0
        ");
      $codigoHTML='
      <body>
      <img src="../../img/estadodecuenta.png"/>
      <br>
      <div>
      <p><b>Control de pagos de la fecha:   </b>'.$fecha.'</p>
      </div>
      <div align="center">
          <table width="100%" border="0">
            <tr>
              <td bgcolor="#81BEF7"><strong>#</strong></td>
              <td bgcolor="#81BEF7"><strong>Nombre del Cliente</strong></td>
              <td bgcolor="#81BEF7"><strong>DPI</strong></td>
              <td bgcolor="#81BEF7"><strong>Abono</strong></td>
            </tr>';

              $con=0;
              $abonosdiario=0;
              $interesdiario=0;
              //while($dato=mysql_fetch_array($consulta)){
              foreach($consulta as $filas => $dato) {
                $con++;
                 $abonosdiario=$abonosdiario+$dato['cuotadiaria'];
      $codigoHTML.='
            <tr>
              <td>'.$con.'</td>
              <td>'.$dato['nombrecompleto'].'</td>
              <td>'.$dato['dpi'].'</td>             
              <td>'.$dato['cuotadiaria'].'</td>
            </tr>';
            } 
            $interesdiario= $abonosdiario*0.30;
            $capitaldiario= $abonosdiario-$interesdiario; 
      $codigoHTML.='
          </table>
      </div><br>
      <p><b>*****************************************************************************************</b></p>
      <div>
          <b>Capital retornado: </b>     Q.'.$capitaldiario.'.00<br>
          <b>Interés retornado: </b>     Q.'.$interesdiario.'.00<br>
          <b>Monto total retornado: </b> Q.'.$abonosdiario.'.00<br>
      </div>
      <br>
      <b>Fecha:</b> '.date("d/m/Y").'<br>
      <b>Hora:</b> '.date("H:i:s").'
      </body>
      </html>';

      $codigoHTML=utf8_decode($codigoHTML);
      $dompdf=new DOMPDF();
      $dompdf->load_html($codigoHTML);
      ini_set("memory_limit","128M");
      $dompdf->render();
      $dompdf->stream("Ingresos_Diarios");
    }

else
//******************************************************************************************
if($a=="clientesCobrador"){
  $idUsuario = $_REQUEST['idUsuario'];
  $codigoHTML='
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>SysCredit 1.0</title>
    </head>
    ';
    
    $objUsuarios = new usuarios();
    $objClientes = new clientes();
    $objCreditos = new creditos();
    $objDetalleCreditos = new detallecreditos();
    
    $regUsuarios = $objUsuarios->getRecords("idUsuario=".$idUsuario);
    $nombreCobrador = $regUsuarios[0]['nombre'];

    $regCreditos = $objCreditos->sql("SELECT 
      cre.idcredito as idcredito,
      cre.cobrador as cobrador,
      cli.nombrecompleto as nombrecompleto,
      cli.telefono as telefono,
      cli.direccion as direccion,
      cre.cuotadiaria as cuotadiaria,
      mo.montocapital as monto,
      pla.dias as dias
      FROM creditos cre
      INNER JOIN clientes cli ON cre.idcliente = cli.idcliente
      INNER JOIN montos mo on cre.idmonto = mo.idmonto
      INNER JOIN planes pla on cre.idplan = pla.idplan
      WHERE cre.estado = 1 and cre.cobrador = \"$nombreCobrador\"
      ORDER by cli.nombrecompleto ASC 
      "); 



  $codigoHTML='
    <body>
      <center><h3>LISTADO DE CLIENTES A COBRAR</h3></center>
      <strong>Nombre de cobrador: '.$nombreCobrador.'</strong>        
      <div align="center">
        <table width="100%" border="0" align="center">
          <tr>
            <td bgcolor="#81BEF7" width=270px><strong><center>Nombre Cliente</center></strong></td>
            <td bgcolor="#81BEF7" width=90px><strong><center>Telefóno</center></strong></td>
            <td bgcolor="#81BEF7" width=100px><strong><center>Cuota Diaria</center></strong></td>
            <td bgcolor="#81BEF7" width=70px><strong><center>Cuota Pendientes</center></strong></td>
            <td bgcolor="#81BEF7" width=70px><strong><center>Cuotas Atrasadas</center></strong></td>
            <td bgcolor="#81BEF7" width=65px><strong><center>Pago Realizado</center></strong></td>
          </tr>';
            
          $fechahoy = date('Y-m-d');
          foreach($regCreditos as $filas => $campo) {
            $id_credito = $campo['idcredito'];
            $regDetalleCreditos = $objDetalleCreditos->sql("SELECT count(dc.fecha) AS fecha FROM detallecreditos dc WHERE ((dc.idcredito = $id_credito) AND (dc.estado = 1)) AND (dc.fecha <= \"$fechahoy\")");
            $cuotasPendientes = $objDetalleCreditos->sql("SELECT count(dc.estado) AS estadodetalle FROM detallecreditos dc WHERE ((dc.idcredito = $id_credito) AND (dc.estado = 1))");
            $totalacobrar = $totalacobrar + $campo['cuotadiaria'];
            $cuotaminima = $campo['monto']/$campo['dias'];

            $sumacuotaminima = $sumacuotaminima + $cuotaminima;
      
            $codigoHTML.='
            <tr>
              <td>'.$campo['nombrecompleto'].'</td>
              <td><center>'.$campo['telefono'].'</center></td>              
              <td><center>Q.'.number_format($campo['cuotadiaria'],2,"."," ").'</center></td>
              <td><center>'.$cuotasPendientes[0]['estadodetalle'].'</center></td>
              <td><center>'.$regDetalleCreditos[0]['fecha'].'</center></td>
              <td><center>________</center></td>
            </tr>';}
            
            $codigoHTML.='
        </table>
      </div>
      <p>
      <b>*****************************************************************************************</b></p>
      <b>Total a cobrar: </b>Q. '.number_format($totalacobrar,2,"."," ").'<br>
      <b>Cuota Minima Total: </b>Q. '.number_format($sumacuotaminima,2,"."," ").'<br>
      <b>Generado</b><br> 
      <b>Fecha:</b> '.date("d/m/Y").'  <b>Hora:</b> '.date("H:i:s").'
    </body>
  </html>';

  $codigoHTML=utf8_decode($codigoHTML);
  $dompdf=new DOMPDF();
  $dompdf->load_html($codigoHTML);
  ini_set("memory_limit","128M");
  $dompdf->set_paper('folio', 'portrait');
  $dompdf->render();
  $dompdf->stream("".$nombreCobrador." - Lista Clientes");
}
else
  if($a=="clientesCobradorPagado"){
  $idUsuario = $_REQUEST['idUsuario'];
  $codigoHTML='
      <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
      <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>SysCredit 1.0</title>
      </head>
      ';
      $objUsuarios = new usuarios();
      $objClientes = new clientes();
      $objCreditos = new creditos();
      $objDetalleCreditos = new detallecreditos();

      $regUsuarios = $objUsuarios->getRecords("idusuario=".$idUsuario);
      $nombreCobrador = $regUsuarios[0]['nombre'];
      $fechahoy = date('Y-m-d');

      $regCreditos = $objCreditos->sql("SELECT 
        cre.idcredito as idcredito,
        cre.cobrador as cobrador,
        cli.nombrecompleto as nombrecompleto,
        cli.telefono as telefono,
        cli.direccion as direccion,
        cre.cuotadiaria as cuotadiaria
        FROM creditos cre
        INNER JOIN clientes cli ON cre.idcliente = cli.idcliente
        WHERE (cre.fechaultimopago = \"$fechahoy\" and cre.cobrador = '$nombreCobrador') or (cre.cobrador = '$nombreCobrador' and cre.estado = 1) 
      ");

  $codigoHTML='
      <body>
        <img src="../../img/estadodecuenta.png"/>
        <br>
        <br>
        <br>
        <div align="center">
          <table width="100%" border="0" align="center">
            <tr>
              <td bgcolor="#81BEF7"><strong>Nombre Cliente</strong></td>
              <td bgcolor="#81BEF7"><strong>Telefóno</strong></td>
              <td bgcolor="#81BEF7"><strong>Dirección</strong></td>
              <td bgcolor="#81BEF7"><strong>Cuota Diaria</strong></td>
              <td bgcolor="#81BEF7"><strong>Pago Realizado</strong></td>
            </tr>';
              //while($dato=mysql_fetch_array($consulta)){
              $lim=0;
            
            foreach($regCreditos as $filas => $campo){
              $lim++;
              $id1 = $campo['idcredito'];
              $regDetalle = $objDetalleCreditos->getRecords("idcredito = $id1 AND estado = 0 AND fechapago = \"$fechahoy\"");
              $cuotasPagadas = count($regDetalle);
              $totalpagado = $cuotasPagadas*$campo['cuotadiaria'];
              
              if (!$regDetalle){
                $pagoDia = "<span class='label label-important'>No pagado</span>";                          
              }
            else{
              $pagoDia = "<span class='label label-success'>Pagado</span>"; 
              $total = $total + $totalpagado;
            }

            $totalaCobrar = $totalaCobrar + $campo['cuotadiaria'];
      $codigoHTML.='
            <tr>
              <td>'.$campo['nombrecompleto'].'</td>
              <td>Q.'.number_format($campo['cuotadiaria'],2,".",",").'</td>
              <td>'.$cuotasPagadas.'</td>
              <td>Q.'.number_format($totalpagado,2,".",",").'</td>
              <td>'.$pagoDia.'</td>
            </tr>';}
      $codigoHTML.='
          </table>
        </div>
        <br>
        <p>
        <b>*****************************************************************************************</b></p>
        
        <h3><b>Total reportado:</b>Q. '.number_format($total,2,"."," ").'</h3>
        <b>Generado</b><br> 
        <b>Fecha:</b> '.date("d/m/Y").'<br>
        <b>Hora:</b> '.date("H:i:s").'
      </body>
    </html>';

      $codigoHTML=utf8_decode($codigoHTML);
      $dompdf=new DOMPDF();
      $dompdf->load_html($codigoHTML);
      ini_set("memory_limit","128M");
      $dompdf->set_paper('folio', 'portrait');
      $dompdf->render();
      $dompdf->stream("".$nombreCobrador." - Reporte Dia");
    }

else if($a=="reportediario")
  {
  $fecha= $_REQUEST['fecha'];
    //$fecha= $_POST['fechareporte'];
    //$fecha=date("Y-m-d");
    //$fecha = "<script> document.write(variablejs) </script>";
    //$fecha=$_GET('variablejs');
   $codigoHTML='
      <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
      <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>SysCredit 1.0</title>
      </head>
      ';
      $creditos= new creditos();
      $consulta=$creditos->sql("SELECT
          cl.nombrecompleto as nombrecompleto,
          cl.dpi as dpi,
          cr.cuotadiaria as cuotadiaria,
          cr.idcredito as idcredito
          FROM creditos cr
          INNER JOIN clientes cl ON cr.idcliente = cl.idcliente
          INNER JOIN detallecreditos dc ON cr.idcredito = dc.idcredito
          WHERE dc.fechapago = \"$fecha\" and dc.estado = 0 and dc.estadorenovacion = 0
        ");
      $codigoHTML='
      <body>
      <img src="../../img/estadodecuenta.png"/>
      <br>
      <div>
      <p><b>Control de pagos de la fecha:   </b>'.$fecha.'</p>
      </div>
      <div align="center">
          <table width="100%" border="0">
            <tr>
              <td bgcolor="#81BEF7"><strong>#</strong></td>
              <td bgcolor="#81BEF7"><strong>Nombre del Cliente</strong></td>
              <td bgcolor="#81BEF7"><strong>DPI</strong></td>
              <td bgcolor="#81BEF7"><strong>Abono</strong></td>
            </tr>';

              $con=0;
              $abonosdiario=0;
              $interesdiario=0;
              //while($dato=mysql_fetch_array($consulta)){
              foreach($consulta as $filas => $dato) {
                $con++;
                 $abonosdiario=$abonosdiario+$dato['cuotadiaria'];
      $codigoHTML.='
            <tr>
              <td>'.$con.'</td>
              <td>'.$dato['nombrecompleto'].'</td>
              <td>'.$dato['dpi'].'</td>             
              <td>'.$dato['cuotadiaria'].'</td>
            </tr>';
            } 
            $interesdiario= $abonosdiario*0.30;
            $capitaldiario= $abonosdiario-$interesdiario; 
      $codigoHTML.='
          </table>
      </div><br>
      <p><b>*****************************************************************************************</b></p>
      <div>
          <b>Capital retornado: </b>     Q.'.$capitaldiario.'.00<br>
          <b>Interés retornado: </b>     Q.'.$interesdiario.'.00<br>
          <b>Monto total retornado: </b> Q.'.$abonosdiario.'.00<br>
      </div>
      <br>
      <b>Fecha:</b> '.date("d/m/Y").'<br>
      <b>Hora:</b> '.date("H:i:s").'
      </body>
      </html>';

      $codigoHTML=utf8_decode($codigoHTML);
      $dompdf=new DOMPDF();
      $dompdf->load_html($codigoHTML);
      ini_set("memory_limit","128M");
      $dompdf->render();
      $dompdf->stream("Ingresos_Diarios");
    }

?>
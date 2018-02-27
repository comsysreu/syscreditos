<?php
require('pdf/fpdf.php');
include("../includes/class.usuarios.php");
include("../includes/class.clientes.php");
include("../includes/class.creditos.php");
include("../includes/class.detallecreditos.php");

session_start();

$pdf=new FPDF('P','mm','Legal');
$pdf->AddPage();

$pdf->SetFont('Arial','B',18);
$pdf->Cell(0,8,'La Matraca Store',0,1);
$pdf->Image("../img/Logo.png", 220, 5, 40, 25, "png");
$pdf->Ln(1);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,4,'Calzada Aguilar Batres',0,1);
$pdf->Cell(0,5,'Guatemala C.A.',0,1);
$pdf->Ln(5);

$pdf->SetFont('Arial','B',8);

$pdf->Cell(50,7,"Nombre de Cliente",1,0,"C");
$pdf->Cell(23,7,utf8_decode("Telefóno"),1,0,"C");
$pdf->Cell(43,7,"Direccion",1,0,"C");
$pdf->Cell(23,7,"Cuota diaria",1,0,"C");
$pdf->Cell(23,7,"Cuotas Pendientes",1,0,"C");
$pdf->Cell(23,7,"Pago realizado",1,0,"C");	
$pdf->Ln();

$idUsuario = $_REQUEST['idUsuario'];

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
    cre.cuotadiaria as cuotadiaria
    FROM creditos cre
    INNER JOIN clientes cli ON cre.idcliente = cli.idcliente
    WHERE cre.estado = 1 and cre.cobrador = \"$nombreCobrador\"
    "); 

$fechahoy = date('Y-m-d');				
foreach ($regCreditos as $key => $campo){
	$id_credito = $campo['idcredito'];
    $regDetalleCreditos = $objDetalleCreditos->sql("SELECT count(dc.fecha) AS fecha FROM detallecreditos dc WHERE ((dc.idcredito = $id_credito) AND (dc.estado = 1)) AND (dc.fecha <= \"$fechahoy\")");
    $totalacobrar = $totalacobrar + $campo['cuotadiaria'];
	
	$pdf->Cell(50,5,utf8_decode($campo['nombrecompleto']),0);
	$pdf->Cell(23,5,$campo['telefono'],1);
	$pdf->Cell(43,5,utf8_decode($campo['direccion']),1);
	$pdf->Cell(23,5,$campo['cuotadiaria'],1);
	$pdf->Cell(23,5,$regDetalleCreditos[0]['fecha'],1);
	$pdf->Cell(33,5,"_________",1);
	$pdf->Ln();
}
		
$pdf->Ln(8);
$pdf->SetFont('Arial','B',8);
//$pdf->Cell(0,4,'Generado por: '.$_SESSION[usuario],0,1);
$pdf->Cell(0,4,'Fecha: '.date("d/m/Y"),0,1);
$pdf->Cell(0,4,'Hora: '.date("H:i:s"),0,1);
$pdf->Output();

?>


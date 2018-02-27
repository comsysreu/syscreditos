<?php
require('pdf/fpdf.php');
include("../includes/class.clientes.php");
session_start();

$pdf=new FPDF('L','mm','Letter');
$pdf->AddPage();

$pdf->SetFont('Arial','B',18);
$pdf->Cell(0,8,'La Matraca Store',0,1);
$pdf->Image("../img/Logo.png", 220, 5, 40, 25, "png");
$pdf->Ln(1);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,4,'Calzada Aguilar Batres',0,1);
$pdf->Cell(0,5,'Guatemala C.A.',0,1);
$pdf->Ln(5);

$pdf->SetFont('Arial','B',10);

    $pdf->Cell(60,7,"Nombre",1,0,"C");
	$pdf->Cell(33,7,"Nit",1,0,"C");
	$pdf->Cell(53,7,"Direccion",1,0,"C");
	$pdf->Cell(33,7,"Tipo cliente",1,0,"C");
	$pdf->Cell(33,7,"Tiempo Credito",1,0,"C");
	$pdf->Cell(33,7,"Cantidad Credito",1,0,"C");	
    $pdf->Ln();

$clientes= new clientes();
$registros =$clientes->sql("SELECT
t1.id_tipo_cliente as idTipoCliente,
t1.nombre as nombre,
t1.nit as nit,
t1.direccion as direccion,
t2.nombre as tipocliente,
t2.tiempo_credito as tiempocredito,
t2.cantidad_credito as cantidad
FROM clientes t1
INNER JOIN tipo_clientes t2 ON t2.id_tipo_cliente=t1.id_tipo_cliente
WHERE t1.id_tipo_cliente=1
");
										
	foreach ($registros as $key => $campo) 
	{
		$pdf->Cell(60,5,$campo['nombre'],1);
		$pdf->Cell(33,5,$campo['nit'],1);
		$pdf->Cell(53,5,$campo['direccion'],1);
		$pdf->Cell(33,5,$campo['tipocliente'],1);
		$pdf->Cell(33,5,$campo['tiempocredito']." Dias",1);
		$pdf->Cell(33,5,"Q ".number_format($campo['cantidad'],2,".","."),1);
		$pdf->Ln();
		
	}
		
$pdf->Ln(8);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(0,4,'Generado por: '.$_SESSION[usuario],0,1);
$pdf->Cell(0,4,'Fecha: '.date("d/m/Y"),0,1);
$pdf->Cell(0,4,'Hora: '.date("H:i:s"),0,1);
$pdf->Output();
?>
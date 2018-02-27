<?php
require('pdf/fpdf.php');
include("../includes/class.detalle_credito_cliente.php");
include("../includes/class.clientes.php");
session_start();

$id=$_REQUEST['id'];

$pdf=new FPDF('L','mm','Letter');
$pdf->AddPage();

 $pdf->SetTextColor(10, 73, 88); 
$pdf->SetFont('Arial','B',18);
$pdf->Cell(0,8,'La Matraca Store',0,1);
$pdf->Image("../img/Logo.png", 220, 5, 40, 25, "png");
$pdf->Ln(1);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,4,'Calzada Aguilar Batres',0,1);
$pdf->Cell(0,5,'Guatemala C.A.',0,1);
$pdf->Ln(5);

$clientes=new clientes();
$reg=$clientes->getRecords("id_cliente=$id");

	$detalle_credito_cliente =new detalle_credito_cliente();
	$registros = $detalle_credito_cliente->sql("SELECT		
		t1.saldo as saldo,
		t1.fecha as fecha,
		t1.total as total,
		t1.id_credito_cliente as id,
		t1.id_detalle_credito_cliente as iddetalle,
		t2.num_factura as factura,
		t4.nombre as usuario,
		t4.apellido as ape
		FROM detalle_credito_cliente t1
		INNER JOIN facturas t2 ON t1.id_factura=t2.id_factura
		INNER JOIN credito_clientes t3 ON t1.id_credito_cliente=t3.id_credito_cliente
		INNER JOIN usuarios t4 ON t1.id_usuario=t4.id_usuario
		WHERE t1.saldo>0 and t3.id_cliente=$id
		");

 $pdf->SetTextColor(0,0,0); 
$pdf->Cell(0,5,"Cliente: ".$reg[0]['nombre']."",0,1);
	$pdf->Ln();
	$pdf->SetFont('Arial','B',10);
    $pdf->Cell(45,7,"No. de Factura",1,0,"C");
	$pdf->Cell(78,7,"Vendedor",1,0,"C");
	$pdf->Cell(45,7,"Total Venta",1,0,"C");
	$pdf->Cell(45,7,"Saldo",1,0,"C");
	$pdf->Cell(45,7,"Fecha  Venta",1,0,"C");	
    $pdf->Ln();
										
	foreach ($registros as $key => $campo) 
	{
		$pdf->Cell(45,5," ".$campo['factura'],1);
		$pdf->Cell(78,5," ".$campo['usuario']." ".$campo['ape'],1);
		$pdf->Cell(45,5," Q ".number_format($campo['total'],2,".","."),1);				
		$pdf->Cell(45,5," Q ".number_format($campo['saldo'],2,".","."),1);		
		$pdf->Cell(45,5," ".$campo['fecha'],1);
		$total=$total+$campo['saldo'];
		$pdf->Ln();
		
	}
$pdf->Ln();
$pdf->SetFont('Arial','B',14);
$pdf->SetTextColor(170, 1, 20);
$pdf->Cell(0,8," Total a pagar: Q ".number_format($total,2,".","."),0,1);
$pdf->SetTextColor(0,0,0);
$pdf->Ln(10);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(0,4,'Generado por: '.$_SESSION[usuario],0,1);
$pdf->Cell(0,4,'Fecha: '.date("d/m/Y"),0,1);
$pdf->Cell(0,4,'Hora: '.date("H:i:s"),0,1);
$pdf->Output();

?>
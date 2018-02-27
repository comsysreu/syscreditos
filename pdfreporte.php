<?php

include_once('PDF.php');

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B', 11);

//Margen decorativo iniciando en 0, 0
//$pdf->Image('credito.png', 0,0, 210, 295, 'PNG');

//Imagen izquierda/Posición en Página:Ancho/Alto, Imagen:Ancho/Alto
$pdf->Image('img/prueba2.png', 25, 25, 25, 25, 'PNG');
 
//Imagen derecha/Posición en Página:Ancho/Alto, Imagen:Ancho/Alto
$pdf->Image('img/prueba.png', 165, 25, 25, 25, 'PNG');
 
//Texto de Título
$pdf->SetXY(80, 38);
$pdf->MultiCell(65, 5, utf8_decode('Sistema de Créditos Personales'), 0, 'C');
 
//Texto Explicativo
$pdf->SetFont('Arial','', 12);
$pdf->SetXY(85, 45);
$pdf->MultiCell(100, 4, utf8_decode('Reporte de Créditos Activos'), 0, 'J');
 
//De aqui en adelante se colocan distintos métodos
//para diseñar el formato.

//Fecha
$pdf->SetFont('Arial','', 12);
$pdf->SetXY(145,60);
$pdf->Cell(15, 8, 'FECHA:', 0, 'L');
$pdf->Line(163, 65.5, 185, 65.5);
 
//Nombre //Apellidos //DNI //TELEFONO
$pdf->SetXY(25, 80);
$pdf->Cell(20, 8, 'NOMBRE(S):', 0, 'L');
$pdf->Line(52, 85.5, 120, 85.5);
//*****
$pdf->SetXY(25,100);
$pdf->Cell(19, 8, 'APELLIDOS:', 0, 'L');
$pdf->Line(52, 105.5, 180, 105.5);
//*****
$pdf->SetXY(25, 120);
$pdf->Cell(10, 8, 'DNI:', 0, 'L');
$pdf->Line(35, 125.5, 90, 125.5);
//*****
$pdf->SetXY(110, 120);
$pdf->Cell(10, 8, utf8_decode('TELÉFONO:'), 0, 'L');
$pdf->Line(135, 125.5, 170, 125.5);
 
//LICENCIATURA  //CARGO   //CÓDIGO POSTAL
$pdf->SetXY(25, 140);
$pdf->Cell(10, 8, 'LINCECIATURA EN:', 0, 'L');
$pdf->Line(27, 154, 65, 154);
//*****
$pdf->SetXY(80, 140);
$pdf->Cell(10, 8, 'CARGO:', 0, 'L');
$pdf->Line(75, 154, 105, 154);
//*****
$pdf->SetXY(125, 140);
$pdf->Cell(10, 8, utf8_decode('CÓDIGO POSTAL:'), 0, 'L');
$pdf->Line(120, 154, 170, 154);
 
$pdf->Output(); //Salida al navegador
 
?>
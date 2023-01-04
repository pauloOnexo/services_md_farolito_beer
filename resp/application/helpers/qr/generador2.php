<?php 

include('qrlib.php');
$content = "NUEVOC2";
QRcode::png($content,"unidad".$content.".png",QR_ECLEVEL_L,10,2);
$img = "unidad".$content.".png";
$contenidoBinario = file_get_contents($img);
$imagenComoBase64 = base64_encode($contenidoBinario);
echo "<img alt='Embedded Image' src='data:image/jpeg;base64,$imagenComoBase64' />";
echo $imagenComoBase64;
//echo "<img src='hola.png'/>";


<?php 
$carpeta = './clases/FotosVentas/';
$idMedia = '2';
$nombre = 'blaze';
$fecha = '2018-06-29';
$nombreImagen = $idMedia."_".$nombre."_".$fecha;
echo $carpeta.$nombreImagen;
$arrImagenes = glob($carpeta.$nombreImagen.".{jpeg,jpg,png}",GLOB_BRACE);

echo var_dump($arrImagenes);
?>
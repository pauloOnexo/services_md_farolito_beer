 <html> 
<head> 
<title>Sucursales</title> 
</head> 
<body> 
<table>
<a href="<?=base_url("index.php/sucursal/add?id_marca=1")?>">Agregar</a>
<?php
foreach($registros as $fila){
?>
    <tr>
        <td>
            <?=$fila->id;?>
        </td>
        <td>
            <?=$fila->nombre;?>
        </td>
        <td>
            <?=$fila->latitud;?>
        </td>
        <td>
            <?=$fila->longitud;?>
        </td>
        <td>
            <a href="<?=base_url("index.php/sucursal/update?id=$fila->id")?>">Modificar</a>
			<!--<a href="<?=base_url("sucursal/update_sucursaldb/$fila->id")?>">Eliminar</a>!-->
        </td>
    </tr>
<?php    
}
?>

</table>

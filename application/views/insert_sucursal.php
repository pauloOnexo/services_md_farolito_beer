 <html> 
<head> 
<title>Sucursales</title> 
</head> 
<body> 
<?php
          
	?>		  
<form method="post" action="<?= site_url('sucursal/add_db') ?>"> 
<table width=50%>
	<tr> 
		<td>Marca </td>
		<td><select name="id_marca">
        <option value="0" >Seleccione:</option>
        <?php
          foreach ($marcas as $valores) {
            echo '<option value="'.$valores->id.'">'.$valores->nombre.'</option>';
          }
        ?>
		</select></td>
	</tr>
	<tr> 
		<td>Region </td>
		<td><select name="id_region">
        <option value="0" >Seleccione:</option>
        <?php
          foreach ($regiones as $valores) {
            echo '<option value="'.$valores->id.'">'.$valores->nombre.'</option>';
          }
        ?>
		</select></td>
	</tr>
	<tr> 
		<td>Nombre </td>
		<td><input type="text" name="nombre" value="" size="50" /> </td>
	</tr>

	<tr> 
		<td>Latitud</td>
		<td><input type="text" name="latitud" value="" size="50" /> </td>
	</tr>
	<tr> 
		<td>Longitud</td>
		<td><input type="text" name="longitud" value="" size="50" /> </td>
	</tr>
</table>
<div><input type="submit" value="Submit" /></div> 
</form> 
</body> 
</html> 
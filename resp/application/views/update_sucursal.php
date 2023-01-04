 <html> 
<head> 
<title>Sucursales</title> 
</head> 
<body> 
<form method="post" action="<?= site_url('sucursal/update_db') ?>"> 
<table width=50%>
	
	<tr> 
		<td>Nombre 
			<input type="hidden" name="id" value="<?php echo $registro['id'] ?>" size="0" />
		</td>
		<td><input type="text" name="nombre" value="<?php echo $registro['nombre'] ?>" size="50" /> </td>
	</tr>
    <tr> 
		<td>marca</td>
		<td><input type="text" name="id_marca" value="1" size="50" /> </td>
	</tr>
	<tr> 
		<td>region</td>
		<td><input type="text" name="id_region" value="1" size="50" /> </td>
	</tr>
	<tr> 
		<td>id</td>
		<td><input type="text" name="id" value="1" size="50" /> </td>
	</tr>
	
	<tr> 
		<td>token</td>
		<td><input type="text" name="token" value="1dc9c3aaee44d39697ee8bf0d03c114be9587ac0553268f3da0e79091bf83da6b19155ef4bfb8066b752e6509d05e9bca73d8e87d4539acc69f5810684dfe2ba" size="50" /> </td>
	</tr>
	<tr> 
		<td>activo</td>
		<td><input type="text" name="activo" value="1" size
	<tr> 
		<td>Latitud</td>
		<td><input type="text" name="latitud" value="<?php echo $registro['latitud'] ?>" size="50" /> </td>
	</tr>
	<tr> 
		<td>Longitud</td>
		<td><input type="text" name="longitud" value="<?php echo $registro['longitud'] ?>" size="50" /> </td>
	</tr>
</table>
<div><input type="submit" value="Submit" /></div> 
</form> 
</body> 
</html> 
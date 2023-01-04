 <html> 
<head> 
<title>Sucursales</title> 
</head> 
<body> 
<form method="post" action="<?= site_url('region/update_db') ?>"> 
<table width=50%>
	<tr> 
		<td>Nombre 
			<input type="hidden" name="id" value="<?php echo $registro['id'] ?>" size="0" />
		</td>
		<td><input type="text" name="nombre" value="<?php echo $registro['nombre'] ?>" size="50" /> </td>
	</tr>
	<tr> 
		<td>Descripcion</td>
		<td><input type="text" name="descripcion" value="<?php echo $registro['descripcion'] ?>" size="50" /> </td>
	</tr>
</table>
<div><input type="submit" value="Submit" /></div> 
</form> 
</body> 
</html> 
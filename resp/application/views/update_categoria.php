 <html> 
<head> 
<title>Sucursales</title> 
</head> 
<body> 
<form method="post" action="<?= site_url('categoria/update_db') ?>"> 
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
	
	<tr> 
		<td>activo</td>
		<td><input type="text" name="activo" value="0" size="50" /> </td>
	</tr>
	
	<tr> 
		<td>token</td>
		<td><input type="text" name="token" value="b04e4881002c2fdd1b436e8e0901c678eeb471f703a0fde93ec6e517be36a02af33e2df76d23250ad2b122a43d94ae0836f6ee3c481dd98579e1872c195ca951" size="50" /> <input type = "file" name = "file" size = "20" /> </td>
	</tr>
	<tr> 
		<td>id</td>
		<td><input type="text" name="id" value="2" size="50" /> </td>
	</tr>
</table>
<div><input type="submit" value="Submit" /></div> 
</form> 
</body> 
</html> 
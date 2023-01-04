 <html> 
<head> 
<title>Sucursales</title> 
</head> 
<body> 
<?php
          
	?>		  
<form method="post" action="<?= site_url('region/add_db') ?>"> 
<table width=50%>
	<tr> 
		<td>Nombre </td>
		<td><input type="text" name="nombre" value="" size="50" /> </td>
	</tr>
	<tr> 
		<td>Descripcion</td>
		<td><input type="text" name="descripcion" value="" size="50" /> </td>
	</tr>
</table>
<div><input type="submit" value="Submit" /></div> 
</form> 
</body> 
</html> 
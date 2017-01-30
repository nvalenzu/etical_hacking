<?php 
session_start();
if(!isset($_SESSION['user'])) 
{
	header("Location:index.php"); 
	exit();
}
else{
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Test penetration</title>
</head>
<style>
	body{
		background: black;
		color: whitesmoke;
	}
	.login{
		width: 300px;
		margin: 0 auto;
		text-align: center;
	}
	.logo{
		margin: 0 auto;
		text-align: center;
	}
	.login .user input{
		width: 200px;
		padding: 10px;
		margin-bottom: 10px;
	}
	.login .pass input{
		width: 200px;
		padding: 10px;
		margin-bottom: 10px;
	}
	.login button{
		width: 100px;
		padding: 10px;
	}
	.descripcion{
		color: #2cc36b;
		text-align: center;
		font-size: 20px;
	}
</style>
<body>
	<div class="logo">
		<img src="http://siliconangle.com/files/2014/01/googles-new-portal-provides-help-for-hacked-sites.jpg">
	</div>
	<div class="login">
		<form method="post" enctype="multipart/form-data">
			<div class="user">
				<input type="file" name="archivo">
			</div>
			<div class="botton">
				<button type="submit">Subir archivo</button>
			</div>
		</form>
	</div>
	<br><br>
	<div class="descripcion">
		<p>Felicidades, lograste entrar, primer paso listo</p>
		<p>El segundo paso es hacer el defaced</p>
	</div>
</body>
</html>

<?php
if(isset($_FILES['archivo']))
{
	$nombre_archivo   = $_FILES['archivo']['name'];
	$archivo_temporal = $_FILES['archivo']['tmp_name'];
	$formatos         = array("image/gif","image/png","image/jpeg","image/jpg");
	$nombre           = strstr($nombre_archivo, ".", true);
	$extension        = $_FILES['archivo']['type'];
	$directorio       = "uploads/"."$nombre_archivo";
	
	 // Verificamos que la extension del archivo este permitido
    if (in_array($extension, $formatos))
    {		
	    if (move_uploaded_file($archivo_temporal, $directorio))
	    {
	    	echo "<center>Subido en: $directorio</center>";
	    }
		else
		{
			echo "<script>alert('No se pudo subir el archivo')</script>";
		}
	}
	else
	{
		echo "<script>alert('No se pudo subir el archivo')</script>";
	}
}

}


?>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-62406113-1', 'auto');
  ga('send', 'pageview');

</script>

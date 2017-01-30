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
		<form method="post">
			<div class="user">
				<input type="text" name="user_md5" placeholder="Nombre de usuario">
			</div>
			<div class="pass">
				<input type="password" name="pass_normal" placeholder="Contraseña">
			</div>
			<div class="botton">
				<button type="submit">Entrar</button>
			</div>
		</form>
	</div>
	<br><br>
	<div class="descripcion">
		<p>Test de penetracion, la idea es que logren hacer un defaced a esta web</p>
		<p>La seguridad de este sitio en escala de 1 a 100 es 55</p>
		<p>Cada vez que logren hacer un defaced la seguridad ira aumentando</p>
		<h1>HAPPY HACKING</h1>
		<h5>by unkndown</h5>
	</div>
</body>
</html>

 <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-62406113-1', 'auto');
  ga('send', 'pageview');

</script>
 <?php 

	$servidor='localhost';
	$usuario='icodeart';
	$pass='andres21';
	$bd='test6';

	$conexion = new mysqli($servidor, $usuario, $pass, $bd);
	$conexion->set_charset('utf8');

	if (isset($_POST['user_md5'])) 
	{
		$user = $_POST['user_md5'];

		$pass = md5(bin2hex($_POST['pass_normal']));

		$login = $conexion->query("SELECT * FROM users WHERE user='$user'"); 

		$pass_login = $conexion->query("SELECT * FROM users WHERE pass='$pass'");

		if($ra=$login->fetch_assoc())
		{
			if($ra=$pass_login->fetch_assoc())
			{
				session_start();
				$_SESSION['user'] = "done";
				header("location:server_entraste_on.php");
			}
			else
			{
				echo "<script>alert('Datos incorrectos')</script>";
			}
		}
		else
		{
			echo "<script>alert('Datos incorrectos')</script>";
		}

	}
 ?>
<?php 

	$servidor='localhost';
	$usuario='icodeart';
	$pass='andres21';
	$bd='test1';

	$conexion = new mysqli($servidor, $usuario, $pass, $bd);
	$conexion->set_charset('utf8');

	if (isset($_GET['user'])) 
	{
		$user = $_GET['user'];
		$pass = $_GET['pass'];

		$login = $conexion->query("SELECT * FROM users WHERE user='$user' AND pass='$pass'");

		if($ra=$login->fetch_assoc())
		{
			session_start();
			$_SESSION['user'] = "done";
			header("location:entraste.php");
		}
		else
		{
			echo "<script>alert('Datos incorrectos')</script>";
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
 
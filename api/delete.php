<?php

if (isset($_POST["id"])) {
   $appid=$_POST['id'];
   
   $token_url="http://104.154.208.78/doctor/BackEnd/Keyckloak/GetToken.php";
	$token=file_get_contents($token_url);
   
   $url="https://fhir.ehealth4u.eu/fhir/Appointment/".$appid;
   
	$headers = array('Content-Type: application/json','Authorization: Bearer '.$token);
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLINFO_HEADER_OUT, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_URL, $url);
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
}

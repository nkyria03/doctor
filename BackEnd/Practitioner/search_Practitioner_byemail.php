<?php
//get token
$token_url="http://104.154.208.78/doctor/BackEnd/Keyckloak/GetToken.php";
$token=file_get_contents($token_url);

$email=$_GET['email'];

//fhir end point
$url="https://fhir.ehealth4u.eu/fhir/Practitioner?email=".$email;

$options = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Authorization: Bearer ".$token
  )
);
$context=stream_context_create($options);

echo $data=file_get_contents($url,false,$context);


?>
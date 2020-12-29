<?php
$token_url="http://104.154.208.78/doctor/BackEnd/Keyckloak/GetToken.php";
$token=file_get_contents($token_url);
$doctor_id=$_GET['doctor_id'];
$url='https://fhir.ehealth4u.eu/fhir/Practitioner?identifier='.$doctor_id;

$options = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Authorization: Bearer ".$token
  )
);
$context=stream_context_create($options);

echo $data=file_get_contents($url,false,$context);

?>
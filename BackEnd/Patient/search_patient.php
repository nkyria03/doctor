<?php
//get token
$token_url="http://104.154.208.78/doctor/BackEnd/Keyckloak/GetToken.php";
$token=file_get_contents($token_url);

$patientID=$_GET['patientID'];
$Phone=$_GET['Phone'];

//find fhir end point
if($Phone!=NULL)
{    
	$url="https://fhir.ehealth4u.eu/fhir/Patient?phone=".$Phone;	 
}
else if($patientID!=NULL)
{
    $url='https://fhir.ehealth4u.eu/fhir/Patient?identifier='.$patientID;
} 
else
{
    echo -1;
}
$options = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Authorization: Bearer ".$token
  )
);
$context=stream_context_create($options);

//response
echo $data=file_get_contents($url,false,$context);




?>
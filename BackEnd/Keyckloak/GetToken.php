<?php
session_start();
function get_string_between($string, $start, $end){
    $string = " ".$string;
    $ini = strpos($string,$start);
    if ($ini == 0) return "";
    $ini += strlen($start);
    $len = strpos($string,$end,$ini) - $ini;
    return substr($string,$ini,$len);
}
if (isset($_SESSION["token"])==false)
{
	//Get Token
	$comm='curl -H "Content-Type: application/x-www-form-urlencoded" -d "client_id=ucy" -d "client_secret=50b0b62d-6305-40ab-81a3-41e092738cc4" -d "username=nikolask" -d "password=fhir" -d "grant_type=password" -X POST https://auth.ehealth4u.eu/auth/realms/ehealth4u/protocol/openid-connect/token';

	$output= shell_exec("$comm 2>&1; echo $?");
	$token=get_string_between($output, '"access_token":"','","expires_in"');
	$Refreshtoken=get_string_between($output, '"refresh_token":"','",');
	//Save token and Refreshtoken
	$_SESSION["token"]=$token;
	$_SESSION["Refreshtoken"]=$Refreshtoken;
}	
else
{
	//check if token expired
	$token_is_active=Check_Token($_SESSION["token"]);
	
	//Refresh token
	if($token_is_active==false) 
	{
		$comm='curl -H "Content-Type: application/x-www-form-urlencoded" -d "client_id=ucy" -d "client_secret=50b0b62d-6305-40ab-81a3-41e092738cc4" -d refresh_token="'.$_SESSION["Refreshtoken"].'" -d "username=nikolask" -d "password=fhir" -d "grant_type=password" -X POST https://auth.ehealth4u.eu/auth/realms/ehealth4u/protocol/openid-connect/token';
		
		$output= shell_exec("$comm 2>&1; echo $?");
		$token=get_string_between($output, '"access_token":"','","expires_in"');
		$Refreshtoken=get_string_between($output, '"refresh_token":"','",');
		$_SESSION["token"]=$token;
		$_SESSION["Refreshtoken"]=$Refreshtoken;
	}
}
echo $_SESSION["token"];


Function Check_Token($token)
{
	$url="https://fhir.ehealth4u.eu/fhir/Patient";
	
	$options = array(
		'http'=>array(
		'method'=>"GET",
		'header'=>"Authorization: Bearer ".$token
		)
	);
	$context=stream_context_create($options);
	$data=file_get_contents($url,false,$context);
	if ($data ==NULL)
	{
		return false;
	}else{
		return true;
	}
}

?>
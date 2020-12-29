<?php
session_start();
$token_url="http://104.154.208.78/doctor/BackEnd/Keyckloak/GetRefreshToken.php";
$token=file_get_contents($token_url);

$comm='curl -H "Content-Type: application/x-www-form-urlencoded" -d "client_id=ucy" -d "client_secret=50b0b62d-6305-40ab-81a3-41e092738cc4" -d "username=nikolask" -d "password=fhir" -d "grant_type=password" -d "refresh_token='.$token.'" -X POST https://auth.ehealth4u.eu/auth/realms/ehealth4u/protocol/openid-connect/logout?redirect_uri=encodedRedirectUri';

$output= shell_exec("$comm 2>&1; echo $?");
	
session_unset ();
session_destroy();
echo "logout";

?>
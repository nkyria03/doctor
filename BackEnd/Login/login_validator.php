<?php 
session_start();

$email=$_GET['email'];
$uesrpassword=$_GET['password'];
include 'config.php';

$conn = mysqli_connect($servername, $username, $password,  $dbname ,$db_port);

// Check connection
if (!$conn) {
    echo "Connection failed: " . mysqli_connect_error();
    exit();
}

$sql = "SELECT * FROM users WHERE email='".$email."' and Password='".$uesrpassword."'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $url="http://104.154.208.78:8080/baseDstu3/Practitioner?email=".$email;
    $PractitionerDeatail=file_get_contents($url);
    $Practitioner=json_decode($PractitionerDeatail, true);
    $total=$Practitioner['total'];
    if ($total>0)
    {
        $Doctor_Name=$Practitioner['entry'][0]['resource']['name'][0]['given'][0];
        $Doctor_Surname=$Practitioner['entry'][0]['resource']['name'][0]['family'];
        $Doctor_prefix=$Practitioner['entry'][0]['resource']['name'][0]['prefix'][0];
        $Doctor_FullName=$Doctor_prefix.' '.$Doctor_Name.' '.$Doctor_Surname;
        $Doctor_id=$Practitioner['entry'][0]['resource']['identifier'][0]['value'];
        $_SESSION["DoctorName"]=$Doctor_FullName;
        $_SESSION['Doctor_id']=$Doctor_id;
        echo "OK";
    }
    else{
        echo "Please try again.";
    }
}
else
{
    echo "Please try again.";
}

?>
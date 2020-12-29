<?php
// Start the session
session_start();
if (isset($_SESSION["DoctorName"])==false) 
{
  echo("<script>location.href = 'index.php';</script>");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>Doctor Main Page</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- IonIcons -->
  <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
   <script>
    $('.txtbirth').datepicker({
        startDate: '-3d'
    });
  </script>
   <script>
  function logout() {
    var form_data = {logout: "logout"};
    $.ajax({
            type: "POST",
            url: "login_page.php",
            data: form_data,
            async:false,
            success: function(response)
            {
				console.log('test');
				console.log(response);
                if(response == 'logout'){
					window.location.href = 'https://auth.ehealth4u.eu/auth/realms/ehealth4u/protocol/openid-connect/logout?redirect_uri=http%3A%2F%2F104.154.208.78%2Fdoctor%2F';
                }
                else
				{
					console.log("error logout")
					window.location.href = 'http://104.154.208.78/doctor/ErrorPage.php';
				}
			}
        });
	}
  </script>
<script type="text/JavaScript">
  function EditForm()
  {
		  var nametextbox = document.getElementById('txtname');
		  if (nametextbox.disabled)
      {
			//edit button
        document.getElementById("txtname").disabled = false;
        document.getElementById("txtsurname").disabled = false;
        document.getElementById("txtemail").disabled = false;
        document.getElementById("txtbirth").disabled = false;
        document.getElementById("txtphone").disabled = false;
        document.getElementById("txtCity").disabled = false;
        document.getElementById("txtAddress").disabled = false;
        document.getElementById("txtPostalCode").disabled = false;
        document.getElementById("txtgender").disabled = false;
        document.getElementById("btnsave").hidden = false;
        document.getElementById("btnedit").hidden = true;
		  }
		  else
		  {
          //save button
        var Name=document.forms['Edit_Form']['txtname'].value;
        var surname=document.forms['Edit_Form']['txtsurname'].value;
        var patientID=document.forms['Edit_Form']['txtid'].value;
        var email=document.forms['Edit_Form']['txtemail'].value;
        var birth=document.forms['Edit_Form']['txtbirth'].value;
        var phone=document.forms['Edit_Form']['txtphone'].value;
        var Address=document.forms['Edit_Form']['txtAddress'].value;
        var PostalCode=document.forms['Edit_Form']['txtPostalCode'].value;
        var City=document.forms['Edit_Form']['txtCity'].value;
        var gender=document.forms['Edit_Form']['txtgender'].value;

        //alert(Name + " "+surname+ " "+patientID+ " "+email+ " "+patientID+ " "+birth+ " "+phone+ " "+Address+ " "+gender+ " "+PostalCode+ " "+City);

        document.getElementById("txtname").disabled = true;
        document.getElementById("txtsurname").disabled = true;
        document.getElementById("txtid").disabled = true;
        document.getElementById("txtemail").disabled = true;
        document.getElementById("txtbirth").disabled = true;
        document.getElementById("txtphone").disabled = true;
        document.getElementById("txtAddress").disabled = true;
        document.getElementById("txtPostalCode").disabled = true;
        document.getElementById("txtCity").disabled = true;
        document.getElementById("txtgender").disabled = true;
        document.getElementById("btnsave").hidden = true;
        document.getElementById("btnedit").hidden = false;
        //call ajax to save
        $(document).ready(function(){
        $.ajax({
          url: "http://104.154.208.78/doctor/BackEnd/Patient/save_patientDetails.php?patientID="+patientID+"&name="+Name+"&surname="+surname+"&email="+email+"&birth="+birth+"&phone="+phone+"&gender="+gender+"&Address="+Address+"&PostalCode="+PostalCode+"&City="+City,
          type: "GET",
          success: function (response){
            var k=response;
            alert(k);
            if(k.indexOf("OK") != -1)
            {
              alert('Saved');
              return false;
            }
            else{
              alert('Please try again!');
              return false;
            }
          },
          error: function (error){
            alert('Please Try Again!');
            return false;
          }
          })
        })
		}
	  return false;
  }
  </script>
</head>
<?php
  
  $patientID=$_POST['patientID'];
  $Phone=$_POST['Phone'];
  
  if ($patientID!=NULL or $Phone!=NULL or $patientID!="" or $Phone!="")
  {
    $Patient_Name=NULL;
    $Patient_Surname=NULL;
    $Patient_id=NULL;
    $Patient_DateOfBirth=NULL;
    $Patient_Phone=NULL;
    $Patient_Gender=NULL;
    $Patient_City=NULL;
    $Patient_PostalCode=NULL;
    $Patient_Address=NULL;
    $Patient_Email=NULL;

    $url="http://104.154.208.78/doctor/BackEnd/Patient/search_patient.php?patientID=".$patientID."&Phone=".$Phone;
    $data=file_get_contents($url);
    $patient=json_decode($data, true);
   // print_r($patient);
    $total=$patient['total'];
    
    if ($total>0)
    {
      $Patient_Name=$patient['entry'][0]['resource']['name'][0]['given'][0];
      $Patient_Surname=$patient['entry'][0]['resource']['name'][0]['family'];
      $Patient_id=$patient['entry'][0]['resource']['identifier'][0]['value'];
      $Patient_DateOfBirth=$patient['entry'][0]['resource']['birthDate'];
      for ($i=0;$i<sizeof($patient['entry'][0]['resource']['telecom']);$i++)
      {  
        if ($patient['entry'][0]['resource']['telecom'][$i]['system']=="phone")
        {
          $Patient_Phone=$patient['entry'][0]['resource']['telecom'][$i]['value'];
        }
        elseif ($patient['entry'][0]['resource']['telecom'][$i]['system']=="email"){
          $Patient_Email=$patient['entry'][0]['resource']['telecom'][$i]['value'];
        }
      } 
      $Patient_Gender=$patient['entry'][0]['resource']['gender'];
      $Patient_City=$patient['entry'][0]['resource']['address'][0]['city'];
      $Patient_PostalCode=$patient['entry'][0]['resource']['address'][0]['postalCode'];
      $Patient_Address=$patient['entry'][0]['resource']['address'][0]['district'];
    }
  }
?>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  
<!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- SEARCH FORM -->
    <form class="form-inline ml-3" name='search_form' method="post" action="PatientDetails.php">
      <div class="input-group input-group-sm">
        <p><b>Patiet ID: </b></p>
        <input class="form-control form-control-navbar" id="patientID" name="patientID" type="search" placeholder="121212" aria-label="Search">
        
        <p><b>Patiet Phone Number: </b></p>
        <input class="form-control form-control-navbar" id="Phone" name="Phone" type="search" placeholder="96969696" aria-label="Search">
        <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Search</button>
          </div>
      </div>
    </form>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="Doctor_MainPage.php" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Doctor</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">
          <?php
              echo $_SESSION['DoctorName'];
            ?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                Doctor:
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">2</span>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="DoctorDetails.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Personal Details</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="DoctorCalendar.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Calendar</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Patient
                <i class="right fas fa-angle-left"></i>
                <span class="badge badge-info right">5</span>
              </p>
            </a>
            <ul >
              <li class="nav-item">
                <a href="PatientDetails.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Personal Details</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="Calendar.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Set Appointment</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="PatientHistory.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Appointments History</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="CreateNewPatient.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                  <p> New Patient </p>
              </a>
              </li>
			  <li class="nav-item">
                <a href="PatientList.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                  <p>Patients List</p>
              </a>
              </li>
            </ul>
          </li>
         <li class="nav-item">
                <i class="far fa-circle nav-icon"></i><a href="javascript:logout();">Logout</a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Main content -->
    <div class="content">
  <!-- general form elements disabled -->
  
  <div class="card card-warning">
    <div class="card-header">
      <h3 class="card-title">General Details</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
    <form  name='Edit_Form' method="post" onsubmit="return EditForm()">
        <div class="row">
          <div class="col-sm-6">
            <!-- text input -->
            <div class="form-group">
              <label>Name</label>
              <input type="text" id='txtname' name='txtname' class="form-control" value="<?php echo $Patient_Name; ?>" placeholder="Enter ..." disabled>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label>Surname</label>
              <input type="text" id='txtsurname' name='txtsurname'class="form-control" value="<?php echo $Patient_Surname; ?>"placeholder="Enter ..." disabled>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <!-- text input -->
            <div class="form-group">
              <label>ID</label>
              <input type="text" id='txtid' name='txtid'class="form-control" value="<?php echo $Patient_id; ?>" placeholder="Enter 1..." disabled>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label>Email</label>
              <input type="text" id='txtemail' name='txtemail' class="form-control" value="<?php echo $Patient_Email; ?>" placeholder="Enter ..." disabled>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <!-- text input -->
            <div class="form-group">
              <label>Date of Birth</label>
              <input type="date" id='txtbirth' name='txtbirth'class="form-control" value="<?php echo $Patient_DateOfBirth; ?>" placeholder="Enter 1..." disabled>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label>Phone</label>
              <input type="text" id='txtphone' name='txtphone' class="form-control" value="<?php echo $Patient_Phone; ?>" placeholder="Enter ..." disabled>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <!-- text input -->
            <div class="form-group">
              <label>Gender</label>
			  <?php
				  if ($Patient_Gender=='male')
				  {
					  $options='<option value="female">female</option>
					  <option value="male" selected>male</option>';
				  }else{
					  $options='<option value="female" selected>female</option>
					  <option value="male">male</option>';
				  }
			  ?>
              <select id="txtgender" name="txtgender" class="form-control" disabled>
				 <?php
					echo $options;
				 ?>
			  </select>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label>City</label>
              <input type="text" id='txtCity' name='txtCity' class="form-control" value="<?php echo $Patient_City; ?>" placeholder="Enter ..." disabled>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <!-- text input -->
            <div class="form-group">
              <label>Postal Code</label>
              <input type="text" id='txtPostalCode' name='txtPostalCode' value="<?php echo $Patient_PostalCode; ?>" class="form-control" placeholder="Enter 1..." disabled>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label>Address</label>
              <input type="text" id='txtAddress' name='txtAddress' class="form-control" value="<?php echo $Patient_Address; ?>" placeholder="Enter ..." disabled>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <button type="submit" id='btnedit' name='btnedit' class="btn btn-primary btn-block">Edit</button>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <button type="submit" id='btnsave' name='btnsave'class="btn btn-primary btn-block"  hidden="hidden">Save</button>
            </div>
          </div>
        </div>
    </form> 
 </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <strong>FHIR Server.</strong>

    
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="dist/js/adminlte.js"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<script src="dist/js/demo.js"></script>
<script src="dist/js/pages/dashboard3.js"></script>
</body>
</html>

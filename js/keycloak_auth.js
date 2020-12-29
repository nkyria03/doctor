var keycloak = Keycloak({
    url: 'https://auth.ehealth4u.eu/auth/',
    realm: 'ehealth4u',
    clientId: 'ucy',
	credentials: {
		secret: '50b0b62d-6305-40ab-81a3-41e092738cc4'
	}
});

var loadData = function () 
{	
	var form_data = {
			email: keycloak.tokenParsed.email
		};

	$.ajax({
		type: "POST",
		url: "login_page.php",
		data: form_data,
		async:false,
		success: function(response)
		{
			if(response == 'OK'){
				console.log("sessions ajax established");
				window.location.href = 'http://104.154.208.78/doctor/Doctor_MainPage.php';
			}
			else
			{
				window.location.href = 'http://104.154.208.78/doctor/ErrorPage.php';
				console.log("Ajax error")
			}
		}
	});
};


window.onload = function() {
	keycloak.init({onLoad: 'login-required' })
		.success(loadData)
		.error(function (errorData) {
			console.log('<b>Failed to load data. Error: ' + JSON.stringify(errorData) + '</b>');
			alert('<b>Failed to load data. Error: ' + JSON.stringify(errorData) + '</b>');
	});	
}
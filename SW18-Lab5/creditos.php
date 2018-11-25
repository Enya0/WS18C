<?php
include "ParametrosDB.php";

//Creamos la conexión
$mysql = mysqli_connect($server,$user,$pass,$basededatos);
if (!$mysql){
	die ("Fallo al conectar a MySQL: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="tipo_contenido" content="text/html;" http-equiv="content-type" charset="utf-8">
	<title>Preguntas</title>
    <link rel='stylesheet' type='text/css' href='estilos/style.css' />
	<link rel='stylesheet' 
		   type='text/css' 
		   media='only screen and (min-width: 530px) and (min-device-width: 481px)'
		   href='estilos/wide.css' />
	<link rel='stylesheet' 
		   type='text/css' 
		   media='only screen and (max-width: 480px)'
		   href='estilos/smartphone.css' />
  </head>
  <body>
  <div id='page-wrap'>
	<?php
		if(isset($_GET['email'])){
			$email = $_GET['email'];
			$resultado = mysqli_query($mysql, "SELECT imagen FROM usuarios WHERE email='$email'");
			if($foto = mysqli_fetch_row($resultado)){
				if ($foto[0] != ""){
					$imagenUsuario = "<img src='data:image/jpg;base64,".base64_encode($foto[0]). "' width='30'/>";
				}else{
					$imagenUsuario = "";}}

			echo "<header class='main' id='h1'>
				<span class='right'><a href='Logout.php'>Logout </a>$imagenUsuario $email </span>
				<h2>Quiz: el juego de las preguntas</h2>
			</header>
			<nav class='main' id='n1' role='navigation'>
				<span><a href='layout.php?email=$email'>Inicio</a></span>
				<span><a href='GestionPreguntas.php?email=$email'>Gestionar Preguntas</a></span>
				<span><a href='RevisarPregunta.php?email=$email'>Revisar Pregunta</a></span>
				<span><a href='creditos.php?email=$email'>Creditos</a></span>
			</nav>";
		}else{
			echo "<header class='main' id='h1'>
			<span class='right'><a href='Registrar.php'>Registrarse</a></span>
				<span class='right'><a href='Login.php'>Login</a></span>
				<h2>Quiz: el juego de las preguntas</h2>
		    </header>
			<nav class='main' id='n1' role='navigation'>
				<span><a href='layout.php'>Inicio</a></span>
				<span><a href='creditos.php'>Creditos</a></span>
			</nav>";
		}
	?>
    <section class="main" id="s1">
    
	<div>
	Mis datos:
	<br>
	Enya Goñi Maganto
	<br>
	Estudiante de la especialidad de Computación
	<br>
	<img src="fotos/foto.JPG" width="75px"/>
	<br>
	Donostia/San Sebastián
	</div>
	<div align="center">
		<table border>
            <tr>
                Tus datos:
            </tr>
            <tr>
                <td>País:</td>
                <td id="pais"></td>
            </tr>
            <tr>
                <td>Región:</td>
                <td id="region"></td>
            </tr>
            <tr>
                <td>Ciudad:</td>
                <td id="ciudad"></td>
            </tr>
            <tr>
                <td>IP pública:</td>
                <td id="ip"></td>
            </tr>
        </table>
	</div>
	Tu ubicación:<br/>
    <div id="mapa" style="height: 250px; width: 95%;"></div>
    </section>
	<footer class='main' id='f1'>
		<a href='https://github.com/Enya0/WS18C/tree/master/SW18-Lab5'>Link GITHUB</a>
	</footer>
</div>
</body>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAzu1h2MY0l_9HhN-AmNUch4ScYQyQh5Ro">
</script>
<script>
    var requestURL = "http://ip-api.com/json";
    var request = new XMLHttpRequest();
    request.open('GET', requestURL);
    request.responseType = 'json';
    request.send();
    request.onload = function () {
        var info = request.response;
        document.getElementById('pais').innerHTML = info['country'] + " (" + info['countryCode'] + ")";
        document.getElementById('region').innerHTML = info['regionName'];
        document.getElementById('ciudad').innerHTML = info['city'];
        document.getElementById('ip').innerHTML = info['query'];
        var lat = info['lat'];
        var lng = info['lon'];
        var myLatlng = new google.maps.LatLng(lat, lng);
        var mapOptions = {
            zoom: 16,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.SATELLITE
        };
        var map = new google.maps.Map(document.getElementById('mapa'), mapOptions);
        var marker = new google.maps.Marker({
            position: myLatlng ,
            map: map
        });
        marker.setMap(map);
    }
</script>
</html>
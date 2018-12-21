<?php
include "ParametrosDB.php";
session_start();

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
		if(isset($_SESSION['email'])){
			echo "<header class='main' id='h1'>
			<span class='right'><a href='Registrar.php'>Registrarse</a></span>
				<span class='right'><a href='Login.php'>Login</a>'Anónimo'</span>
				<h2>Quiz: el juego de las preguntas</h2>
		    </header>
			<nav class='main' id='n1' role='navigation'>
				<span><a href='layout.php'>Inicio</a></span>
				<span><a href='ModificarPassword.php'>Modificar Contraseña</a></span>
				<span><a href='creditos.php'>Creditos</a></span>
			</nav>";
		}
	?>
    <section class="main" id="s1">
		<div>
			<form id='fpassword' name='fpassword' action="RecuperarPassword.php" method="post" enctype="multipart/form-data">
				E-mail*: <input type="text" id="email" name="email" onblur="comprobarEmailVIP()">
				<small id="correoValido" class="form-text text-muted"></small><br/>
				Introduce tu nueva contraseña*: <input type="password" id="contrasena" name="contrasena" onblur="comprobarContrasenaGuay()">
				<small id="contrasenaValida" class="form-text text-muted"></small><br/>
				Repite tu nueva contraseña*: <input type="password" id="contrasenaRep" name="contrasenaRep"><br/>
				Introduce el código de recuperación*: <input type="text" id="code" name="code"><br/>
				<small><input type="submit" id="enviar" name="enviar" value="Enviar solicitud""></small>
			</form>

			<?php

				if (isset($_POST['email'])){

					$email = $_POST['email'];
					$contrasena = $_POST['contrasena'];
					$contrasenaRep = $_POST['contrasenaRep'];
					$code = $_POST['code'];
					$ticket = 1010;
					require_once('nusoap-0.9.5/lib/nusoap.php');

					$soapclient = new nusoap_client('http://ehusw.es/jav/ServiciosWeb/comprobarmatricula.php?wsdl', true);
					$mail = $soapclient->call('comprobar', array('x' => $email));

					if(strstr($mail, 'NO')){
						die('<script>$("#errorRegistro").text("El email NO es válido");</script>');
					}

					$soapclientPass = new nusoap_client('http://enya-sw15.hol.es/SW18-Lab6/ComprobarPassword.php?wsdl', true);
					$passw = $soapclientPass->call('comprobarPassword', array('passw' => $contrasena, 'ticket' => $ticket));

					if(strstr($passw, 'INVALIDA')){
						die('<script>$("#errorRegistro").text("El password NO es válido");</script>');
					}

					if($email!="" && $contrasena!="" && $contrasenaRep!="" && $code!=""){
						if($contrasena != $contrasenaRep){
							$contrasenaErr = "Las contraseñas introducidas no coinciden";
							echo $contrasenaErr;
						}else{
							if($_SESSION['code'] == $code && $_SESSION['email'] == $email){
								$contrasena = hash('md5', $contrasena);

								$sql = "UPDATE usuarios SET password = '$contrasena' WHERE email = '$email' ";

								if (mysqli_query($mysql, $sql)){
									echo "Se ha actualizado la contraseña correctamente";
									session_unset();
								}else{
									echo "Ha habido un error";
								}
							}else{
								echo "Código o email incorrecto.";
							}
						}
					}else{
						echo ("El email introducido no existe");
					}
					mysqli_close($mysql);
				}
			?>
		</div>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script type="text/javascript">

			var correoVIP = false;
			var contrasenaGuay = false;

			xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function () {
				if (xmlhttp.readyState == 4){
					document.getElementById("correoValido").innerHTML = xmlhttp.responseText;
					if (xmlhttp.responseText.trim() == "SI") {
						$('#correoValido').text('El email es VIP');
						correoVIP = true;
					}else{
						$('#correoValido').text('El email NO es VIP');
						correoVIP = false;
					}
					cambiarEstadoBoton();
				}
			}

		    function comprobarEmailVIP() {
		        if ($('#email').val().length != 0) {
		            xmlhttp.open("GET", "ComprobarUsuarioVIP.php?email=" + $('#email').val(), true);
		            xmlhttp.send();
		        }
		    }
		    comprobarEmailVIP();

			xmlhttpContrasena = new XMLHttpRequest();
			xmlhttpContrasena.onreadystatechange = function () {
				if (xmlhttpContrasena.readyState == 4){
					document.getElementById("contrasenaValida").innerHTML = xmlhttpContrasena.responseText;
					if (xmlhttpContrasena.responseText.trim() == "VALIDA") {
						$('#contrasenaValida').text('La contraseña es válida');
						contrasenaGuay = true;
					}else{
						$('#contrasenaValida').text('La contraseña NO es válida');
						contrasenaGuay = false;
					}
					cambiarEstadoBoton();
				}
			}

		    function comprobarContrasenaGuay() {
		        if ($('#contrasena').val().length != 0) {
		            xmlhttpContrasena.open("GET", "ComprobarContrasena.php?contrasena=" + $('#contrasena').val(), true);
		            xmlhttpContrasena.send();
		        }
		    }
		    comprobarContrasenaGuay();

		    function cambiarEstadoBoton(){
		    	if (correoVIP && contrasenaGuay){
		    		$('#enviar').removeAttr('disabled');
		    	}else{
		    		$('#enviar').attr('disabled','disabled');
		    	}
		    }
		</script>
    </section>
	<footer class='main' id='f1'>
		<a href='https://github.com/Enya0/WS18C/tree/master/SW18-Final'>Link GITHUB</a>
	</footer>
  </div>
</body>
</html>

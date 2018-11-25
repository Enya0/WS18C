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
		<form id='fregistro' name='fregistro' action="Registrar.php" method="post" enctype="multipart/form-data">
			Dirección de correo(*): <input type="text" id="email" name="email" onblur="comprobarEmailVIP()">
			<small id="correoValido" class="form-text text-muted"></small><br/>
			Nombre y apellido/s(*): <input type="text" id="nombreApellidos" name="nombreApellidos"><br/>
			Password(*): <input type="password" id="contrasena" name="contrasena" onblur="comprobarContrasenaGuay()">
			<small id="contrasenaValida" class="form-text text-muted"></small><br/>
			Repetir password(*): <input type="password" id="contrasenaRep" name="contrasenaRep"><br/>
			<input type="file" typ="uploadedfile" id="imagen" name="imagen"><br/>
			<input type="submit" id="enviar" name="enviar" value="Enviar" disabled>
		</form>
		<p id="errorRegistro" style="width: 100%; text-align: center;"></p>

		<?php

			if (isset($_POST['email'])){

				include "ParametrosDB.php";

				$email = $_POST['email'];
				$nombreApellidos = $_POST['nombreApellidos'];
				$contrasena = $_POST['contrasena'];
				$contrasenaRep = $_POST['contrasenaRep'];
				$ticket = 1010;
				require_once('nusoap-1-master/lib/nusoap.php');

				function comprobarEmail($str){
					$matches = null;
					return (1 === preg_match('/[a-zA-Z]+[0-9]{3}@ikasle\.ehu\.eus/', $str, $matches));
				}

				$soapclient = new nusoap_client('http://ehusw.es/jav/ServiciosWeb/comprobarmatricula.php?wsdl', true);
				$mail = $soapclient->call('comprobar', array('x' => $email));

				if(strstr($mail, 'NO')){
					die('<script>$("#errorRegistro").text("El email NO es válido");</script>');
				}

				function comprobarNombre($str){
					$matches = null;
					return (1 === preg_match('/[a-zA-Z]+([ ][a-zA-Z]+)+/', $str, $matches));
				}
				function comprobarLongitudPassword($str){
					return (strlen($str)>=8);
				}

				$soapclientPass = new nusoap_client('http://magnasis.com/enya/SW18-Lab5/ComprobarPassword.php?wsdl', true);
				$passw = $soapclientPass->call('comprobarPassword', array('contrasena' => $contrasena, 'ticket' => $ticket));
				if(strstr($passw, 'INVALIDA')){
					die('<script>$("#errorRegistro").text("El password NO es válido");</script>');
				}
				function passwordsIguales($str1,$str2){
					return (strcmp($str1, $str2) == 0);
				}
				function emailExiste($str,$conexion){
					$existe = mysqli_query($conexion, "SELECT * FROM usuarios WHERE email='$str'");
					return (mysqli_num_rows($existe) != 0);
				}
				function camposRellenados($conexion){
					return (comprobarEmail($_POST['email']) && isset($_POST['nombreApellidos']) && comprobarNombre($_POST['nombreApellidos']) && isset($_POST['contrasena']) && isset($_POST['contrasenaRep']) && comprobarLongitudPassword($_POST['contrasena']) && passwordsIguales($_POST['contrasena'], $_POST['contrasenaRep']) && !emailExiste($_POST['email'], $conexion));
				}

				$contrasena = hash('md5', $contrasena);

				$mysql = mysqli_connect($server,$user,$pass,$basededatos);
				if (!$mysql){
					echo ("Fallo al conectar a MySQL: " . mysqli_connect_error());
					echo ("<br/><a href='pregunta.html'> Volver al formulario </a>");
				}
				else{
					if(camposRellenados($mysql)){
						if($_FILES["imagen"]["type"]){
							$imagen = mysqli_real_escape_string($mysql, file_get_contents($_FILES["imagen"]["tmp_name"]));
							$sql = "INSERT INTO usuarios (email, nombreApellidos, password, imagen) VALUES ('$email', '$nombreApellidos', '$contrasena', '$imagen')";
						}
						else{
							$sql = "INSERT INTO usuarios (email, nombreApellidos, password) VALUES ('$email', '$nombreApellidos', '$contrasena')";
						}
						if (mysqli_query($mysql, $sql)){
							echo "El usuario ha sido añadido a la base de datos <br>";
						}
						else{
							echo "Error al añadir el usuario a la base de datos: " . $sql . "<br>" . mysqli_error($mysql);
						}
					}
					else{
						echo "Rellene todos los campos, por favor";
					}
					mysqli_close($mysql);
				}
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
				console.log(xmlhttpContrasena.responseText.trim());
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
	        	console.log($('#contrasena').val());
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

		$("#imagen").click(function(event){
			$("input[type=file]").change(function(event) {
				readURL(this);
			});
			const readURL = (input) => {
 
		        if (input.files && input.files[0]) {
		            const reader = new FileReader();

		            reader.onload = (e) => {
		            	$("#fregistro + img").remove();
		            	$("img").remove();
            			$("#imagen").after('<img src="'+e.target.result+'" width="100" height="75"/>');
		            }
		            reader.readAsDataURL(input.files[0]);
		        }
			}
			if(!$("#quitarImagen").length){
				var input = $("<input/>", {
					type: "button", id: "quitarImagen", name: "Eliminar imagen", value: "Eliminar imagen"
				});
				$("#fregistro").append(input);
				$("#quitarImagen").bind("click",function(){
					$("img").remove();
					$("#quitarImagen").remove();
					$("img").css("style", "display:none;");
					$("#imagen").val("");
				});
		    }
		});
	</script>
    </section>
	<footer class='main' id='f1'>
		<a href='https://github.com/Enya0/WS18C/tree/master/SW18-Lab5'>Link GITHUB</a>
	</footer>
</div>
</body>
</html>
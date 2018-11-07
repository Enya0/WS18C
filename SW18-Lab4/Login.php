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
				<span><a href='pregunta.php?email=$email'>Insertar Pregunta</a></span>
				<span><a href='VerPreguntasConFoto.php?email=$email'>Ver Preguntas</a></span>
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
		<form id='flogin' name='flogin' action="Login.php" method="post" enctype="multipart/form-data">
			Dirección de correo(*): <input type="text" id="email" name="email"><br/>
			Password(*): <input type="password" id="contrasena" name="contrasena"><br/>
			<input type="submit" id="enviar" name="enviar" value="Enviar">
		</form>

		<?php

			if (isset($_POST['email'])){

				include "ParametrosDB.php";

				$email = $_POST['email'];
				$contrasena = $_POST['contrasena'];

				$contrasena = hash('md5', $contrasena);

				$mysql = mysqli_connect($server,$user,$pass,$basededatos);
				if (!$mysql){
					echo ("Fallo al conectar a MySQL: " . mysqli_connect_error());
					echo ("<br/><a href='Login.php'> Volver a iniciar sesión </a>");
				}
				else{
					$usuarios = mysqli_query($mysql, "SELECT * FROM usuarios WHERE email = '$email' && password = '$contrasena'");
					$cont = mysqli_num_rows($usuarios);
					if($cont==1){
						echo"<script> alert ('BIENVENIDO AL SISTEMA:". $email . "'); </script>";
						echo ("Login correcto<p><a href='../SW18-Lab4/layout.php?email=$email'>Vea sus opciones</a>");
					}
					else {
						echo ("Par&aacute;metros de login incorrectos<p><a href='Login.php'>Puede intentarlo de nuevo</a>");
					}
					mysqli_close($mysql);
				}
			}
		?>
	</div>
	
    </section>
	<footer class='main' id='f1'>
		<a href='https://github.com/Enya0/WS18C/tree/master/SW18-Lab4'>Link GITHUB</a>
	</footer>
</div>
</body>
</html>
<?php
session_start();

require_once "config.php";
if (isset($_SESSION['access_token'])) {
    header('Location: layout.php');
    exit();
}
$redirectURL = "http://localhost/SW18/SW18-Final/fb-callback.php";
$permissions = ['email'];
$loginURL = $helper->getLoginUrl($redirectURL, $permissions);
?>

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
		if(isset($_SESSION['email'])){
			echo '<script language="javascript">alert("Ya estás logueado");</script>';
	    	header('Location: layout.php');
		}else{
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
		<form id='flogin' name='flogin' action="Login.php" method="post" enctype="multipart/form-data">
			Dirección de correo(*): <input type="text" id="email" name="email"><br/>
			Password(*): <input type="password" id="contrasena" name="contrasena"><br/>
			<input type="submit" id="enviar" name="enviar" value="Enviar">
			<input type="button" onclick="window.location = '<?php echo $loginURL ?>';" value="Entrar con Facebook" class="btn btn-primary"><br/><br/>
			<small><span><a href='ModificarPassword.php'>¿Has olvidado tu password?</a></span></small>
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
					$row = mysqli_fetch_row($usuarios);
					$cont = mysqli_num_rows($usuarios);
					if($cont==1){
						$_SESSION['nombre'] = $row[1];
						$_SESSION['email'] = $email;
						$_SESSION['foto'] = $row[3];
						if(strcmp($email, "admin@ehu.es") == 0 || strcmp($email, "vadillo@ehu.es") == 0){
							$_SESSION['rol'] = "administrador";
						}else{
							$_SESSION['rol'] = "alumno";
						}
						echo "<script language='javascript'> alert('BIENVENID@ AL SISTEMA: ".$email."'); window.location.assign('layout.php')</script>";
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
		<a href='https://github.com/Enya0/WS18C/tree/master/SW18-Final'>Link GITHUB</a>
	</footer>
</div>
</body>
</html>
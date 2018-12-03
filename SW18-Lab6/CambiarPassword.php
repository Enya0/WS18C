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
				<span><a href='creditos.php'>Creditos</a></span>
			</nav>";
		}
	?>
    <section class="main" id="s1">
		<div>
			<form id='fpassword' name='fpassword' action="CambiarPassword.php" method="post" enctype="multipart/form-data">
				Email: <input type="text" id="email" name="email"><br/>
				Nueva contraseña(*): <input type="password" id="nuevaPass" name="nuevaPass"><br/>
				<small><input type="submit" id="actualizarPass" name="actualizarPass" value="Actualizar contraseña""></small>
			</form>

			<?php

				if (isset($_POST['nuevaPass'])){

					//include "ParametrosDB.php";

					$email = $_POST['email'];
					$nuevaContra = $_POST['nuevaPass'];

					$nuevaContra = hash('md5', $nuevaContra);

					$mysql = mysqli_connect($server,$user,$pass,$basededatos);
					if (!$mysql){
						echo ("Fallo al conectar a MySQL: " . mysqli_connect_error());
						echo ("<br/><a href='CambiarPassword.php'> Volver a intentarlo</a>");
					}
					else{
						$result = "UPDATE usuarios SET password = '$nuevaContra' WHERE email = '$email' ";

						if (mysqli_query($mysql, $result)){
							echo ("Enhorabuena, su password ha sido actualizado");
						}else{
							echo ("Ha habido un error<p><a href='CambiarPassword.php'>Puede intentarlo de nuevo</a>");
						}
						
						mysqli_close($mysql);
					}
				}
			?>
		</div>
    </section>
	<footer class='main' id='f1'>
		<a href='https://github.com/Enya0/WS18C/tree/master/SW18-Lab5'>Link GITHUB</a>
	</footer>
  </div>
</body>
</html>

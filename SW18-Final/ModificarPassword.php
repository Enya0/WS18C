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
			<form id='fpassword' name='fpassword' action="ModificarPassword.php" method="post" enctype="multipart/form-data">
				Email: <input type="text" id="email" name="email"><br/>
				<small><input type="submit" id="actualizarPass" name="actualizarPass" value="Actualizar contraseña""></small>
			</form>

			<?php

				if (isset($_POST['email'])){

					$email = $_POST['email'];

					$mysql = mysqli_connect($server,$user,$pass,$basededatos);
					if (!$mysql){
						echo ("Fallo al conectar a MySQL: " . mysqli_connect_error());
						echo ("<br/><a href='ModificarPassword.php'> Volver a intentarlo</a>");
					}
					else{

						$sql = "SELECT * FROM usuarios WHERE email = '$email' ";

						$result = mysqli_query($mysql, $sql);

						if ($result){
							$row = mysqli_fetch_row($result);
						}
						
						if($row[0] == $email){
							$to = $email;
							$subject = "Recupera tu contraseña";

							$codigo = rand(10000,99999);

							session_start();

							$_SESSION['code'] = $codigo;
							$_SESSION['email'] = $email;

							$message = "
							<html>
							<head>
							<title>Recupera tu contraseña</title>
							</head>
							<body>
							<h3>Sigue estos pasos para recuperar tu contraseña:</h3>
							<ol>
								<li>Entra en el link que te indicamos a continuación</li>
								<li>Introduce el código proporcionado y la nueva contraseña</li>
								<li>Si todo va bien, la página te lo notificará y habrás cambiado tu contraseña</li>
							</ol>
							<h3>Link a la página de recuperación:</h3>
							<h2><a href='http://enya-sw15.hol.es/SW18-Final/RecuperarPassword.php?email=".$email."' id='layout'>Aquí</a></h2>
							<h3>Código de recuperación:</h3>
							<h2>".$codigo."</h2>
							</body>
							</html>
							";

							$headers = "MIME-Version: 1.0" . "\r\n";
							$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

							$headers .= 'From: <QUIZ>' . "\r\n";

							mail($to,$subject,$message,$headers);

							echo "El email se ha enviado correctamente.";

						}else{
							echo ("El email introducido no existe");
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

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
		session_start();
		if(isset($_SESSION['email'])){
			if($_SESSION['rol'] == "alumno")
			{
				$email = $_SESSION['email'];
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
					<span><a href='layout.php'>Inicio</a></span>
					<span><a href='GestionPreguntas.php'>Gestionar Preguntas</a></span>
					<span><a href='RevisarPregunta.php'>Revisar Pregunta</a></span>
					<span><a href='creditos.php'>Creditos</a></span>
				</nav>";	
			}else if($_SESSION['rol'] == "administrador"){
				$email = $_SESSION['email'];
				echo "<header class='main' id='h1'>
					<span class='right'><a href='Logout.php'>Logout </a>$email</span>
					<h2>Quiz: el juego de las preguntas</h2>
				</header>
				<nav class='main' id='n1' role='navigation'>
					<span><a href='layout.php'>Inicio</a></span>
					<span><a href='GestionarCuentas.php'>Gestionar cuentas</a></span>
				</nav>";
			}
		}
		else{
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
	Aqui se visualizan las preguntas y los creditos ...
	</div>
    </section>
	<footer class='main' id='f1'>
		<a href='https://github.com/Enya0/WS18C/tree/master/SW18-Lab5'>Link GITHUB</a>
	</footer>
</div>
</body>
</html>

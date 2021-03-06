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
	<header class='main' id='h1'>
	<?php
		if(isset($_GET['email'])){
			$email = $_GET['email'];
			echo "<header class='main' id='h1'>
				<span class='right'><a href='Logout.php'>Logout </a>$email</span>
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
		<?php 
		include "ParametrosDB.php";

		$email = $_POST['email'];
		$enunciado = $_POST['enunciado'];
		$respCorrecta = $_POST['respCorrecta'];
		$respInc1 = $_POST['respInc1'];
		$respInc2 = $_POST['respInc2'];
		$respInc3 = $_POST['respInc3'];
		$complejidad = $_POST['complejidad'];
		$tema = $_POST['tema'];

		//Creamos la conexión
		$mysql = mysqli_connect($server,$user,$pass,$basededatos);
		if (!$mysql){
			echo ("Fallo al conectar a MySQL: " . mysqli_connect_error());
			echo ("<br/><a href='pregunta.html'> Volver al formulario </a>");
		}
		else{
			$sql = "INSERT INTO preguntas(email, enunciado, respCorrecta, respInc1, respInc2, respInc3, complejidad, tema) VALUES('$email', '$enunciado', '$respCorrecta', '$respInc1', '$respInc2', '$respInc3', '$complejidad', '$tema')";


			if (!mysqli_query($mysql ,$sql))
			{
				echo('Error: ' . mysqli_error($mysql));
				echo "<br/><a href='pregunta.html'> Volver al formulario </a>";
			}
			else{
				echo "La pregunta se ha insertado correctamente";
				echo "<br/><a href='VerPreguntas.php'> Ver preguntas </a>";
			}
			//Cerramos la conexión
			mysqli_close($mysql);
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

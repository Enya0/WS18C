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
		<span class="right"><a href="registro">Registrarse</a></span>
      		<span class="right"><a href="login">Login</a></span>
      		<span class="right" style="display:none;"><a href="/logout">Logout</a></span>
		<h2>Quiz: el juego de las preguntas</h2>
    </header>
	<nav class='main' id='n1' role='navigation'>
		<span><a href='layout.html'>Inicio</a></span>
		<span><a href='pregunta.html'>Insertar Pregunta</a></span>
		<span><a href='creditos.html'>Creditos</a></span>
	</nav>
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
			if($_FILES["imagen"]["type"]){
				//W3 recomienda usar file_get_contents para pasar el contenido de un archivo a string
				//Supongo que así es más fácil meter la imagen en la base de datos
				//https://www.w3schools.com/php/func_filesystem_file_get_contents.asp
				//Además, intentaba coger la imagen utilizando solamente esa función pero me salía un error
				//que indicaba que no se podían coger los datos correctamente para introducirlos en la sentencia SQL.
				//Por lo tanto, he buscado una función que me ayude a solucionar ese problema y he encontrado "mysqli_real_escape_string"
				//En la página: http://php.net/manual/es/mysqli.real-escape-string.php
				//Dicen: "Esta función se usa para crear una cadena SQL legal que se puede usar en una sentencia SQL"
				//Usando esta función he arreglado el problema que tenía para coger la imagen y meterla en la base de datos
				$imagen = mysqli_real_escape_string($mysql, file_get_contents($_FILES["imagen"]["tmp_name"]));
				$sql = "INSERT INTO preguntas(email, enunciado, respCorrecta, respInc1, respInc2, respInc3, complejidad, tema, imagen) VALUES('$email', '$enunciado', '$respCorrecta', '$respInc1', '$respInc2', '$respInc3', '$complejidad', '$tema', '$imagen')";
			}
			else{
				$sql = "INSERT INTO preguntas(email, enunciado, respCorrecta, respInc1, respInc2, respInc3, complejidad, tema) VALUES('$email', '$enunciado', '$respCorrecta', '$respInc1', '$respInc2', '$respInc3', '$complejidad', '$tema')";
			}


			if (!mysqli_query($mysql ,$sql))
			{
				echo('Error: ' . mysqli_error($mysql));
				echo "<br/><a href='pregunta.html'> Volver al formulario </a>";
			}
			else{
				echo "La pregunta se ha insertado correctamente";
				echo "<br/><a href='VerPreguntasConFoto.php'> Ver preguntas </a>";
			}
			//Cerramos la conexión
			mysqli_close($mysql);
		}
		?>
	</div>

    </section>
	<footer class='main' id='f1'>
		<a href='https://github.com/Enya0/WS18C/tree/master/SW18-Lab2B'>Link GITHUB</a>
	</footer>
</div>
</body>
</html>

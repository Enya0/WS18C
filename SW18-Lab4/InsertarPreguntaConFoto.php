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
		<?php 

			if(isset($_POST['email'])){

				include "ParametrosDB.php";

				$email = $_POST['email'];
				$enunciado = $_POST['enunciado'];
				$respCorrecta = $_POST['respCorrecta'];
				$respInc1 = $_POST['respInc1'];
				$respInc2 = $_POST['respInc2'];
				$respInc3 = $_POST['respInc3'];
				$complejidad = $_POST['complejidad'];
				$tema = $_POST['tema'];


				function comprobarEmail($str){
					$matches = null;
					return (1 === preg_match('/[a-zA-Z]+[0-9]{3}@ikasle\.ehu\.eus/', $str, $matches));
				}
				function comprobarEnunciado($str){
					return (strlen($str)>=10);
				}
				function comprobarComplejidad($str){
					$matches = null;
					return (1 === preg_match('/[0-5]/', $str, $matches));
				}
				function emailExiste($str,$conexion){
					$existe = mysqli_query($conexion, "SELECT * FROM usuarios WHERE email='$str'");
					return (mysqli_num_rows($existe) != 0);
				}
				function camposRellenados($conexion){
					return (comprobarEmail($_POST['email']) && isset($_POST['enunciado']) && comprobarEnunciado($_POST['enunciado']) && isset($_POST['respCorrecta']) && isset($_POST['respInc1']) && isset($_POST['respInc2']) && isset($_POST['respInc3']) && isset($_POST['complejidad']) && comprobarComplejidad($_POST['complejidad']) && isset($_POST['tema']) && emailExiste($_POST['email'], $conexion));
				}

				function insertaPregunta($tema, $email, $enunciado, $respCorrecta, $respInc1, $respInc2, $respInc3){					
					$ficheroPreguntas=simplexml_load_file("xml/preguntas.xml");
					
					if(!$ficheroPreguntas){
						return false;
					}
					
					$preg = $ficheroPreguntas->addChild('assessmentItem');

					$preg->addAttribute('subject', $tema);
					$preg->addAttribute('author', $email);

					$itemBody = $preg->addChild('itemBody');
					$itemBody->addChild('p', $enunciado);

					$correctResponse = $preg->addChild('correctResponse');
					$correctResponse->addChild('value', $respCorrecta);

					$incorrectResponses = $preg->addChild('incorrectResponses');
					$incorrectResponses->addChild('value', $respInc1);
					$incorrectResponses->addChild('value', $respInc2);
					$incorrectResponses->addChild('value', $respInc3);

					return $ficheroPreguntas->asXML('xml/preguntas.xml');
				}

				//Creamos la conexión
				$mysql = mysqli_connect($server,$user,$pass,$basededatos);
				if (!$mysql){
					echo ("Fallo al conectar a MySQL: " . mysqli_connect_error());
					echo ("<br/><a href='pregunta.html'> Volver al formulario </a>");
				}
				else{
					if(camposRellenados($mysql)){
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
							//Ejemplos también en: https://www.solvetic.com/tutoriales/article/2481-almacenar-archivos-en-campos-blob-con-php-y-mysql/
							$imagen = mysqli_real_escape_string($mysql, file_get_contents($_FILES["imagen"]["tmp_name"]));
							$sql = "INSERT INTO preguntas(email, enunciado, respCorrecta, respInc1, respInc2, respInc3, complejidad, tema, imagen) VALUES('$email', '$enunciado', '$respCorrecta', '$respInc1', '$respInc2', '$respInc3', '$complejidad', '$tema', '$imagen')";
						}
						else{
							$sql = "INSERT INTO preguntas(email, enunciado, respCorrecta, respInc1, respInc2, respInc3, complejidad, tema) VALUES('$email', '$enunciado', '$respCorrecta', '$respInc1', '$respInc2', '$respInc3', '$complejidad', '$tema')";
						}

						if (!mysqli_query($mysql ,$sql)){
							echo('Error: ' . mysqli_error($mysql));
							echo "<br/><a href='pregunta.html'> Volver al formulario </a>";
						}
						else{
							echo "La pregunta se ha insertado correctamente";
							echo "<br/><a href='VerPreguntasConFoto.php?email=$email'> Ver preguntas </a>";
						}

						if(insertaPregunta($tema, $email, $enunciado, $respCorrecta, $respInc1, $respInc2, $respInc3)){
							echo "La pregunta se ha insertado correctamente en la base de datos";
							echo "<br/><a href='VerPreguntasXML.php?email=$email'> Ver preguntas </a>";
						}else{
							echo "Ha habido un error al introducir la pregunta en la base de datos";
						}

						//Cerramos la conexión
						mysqli_close($mysql);

					}else{
						echo "Rellene todos los campos, por favor";
					}
				}
			}
			
		?>
	</div>

    </section>
	<footer class='main' id='f1'>
		<a href='https://github.com/Enya0/WS18C/tree/master/SW18-LabPrevioSeguridad'>Link GITHUB</a>
	</footer>
</div>
</body>
</html>

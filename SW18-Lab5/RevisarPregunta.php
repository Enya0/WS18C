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
			echo "<header class='main' id='h1'>
				<span class='right'><a href='Logout.php'>Logout </a>$email</span>
				<h2>Quiz: el juego de las preguntas</h2>
			</header>
			<nav class='main' id='n1' role='navigation'>
				<span><a href='layout.php?email=$email'>Inicio</a></span>
				<span><a href='GestionPreguntas.php?email=$email'>Gestionar Preguntas</a></span>
				<span><a href='RevisarPregunta.php?email=$email'>Revisar Pregunta</a></span>
				<span><a href='creditos.php?email=$email'>Creditos</a></span>
			</nav>";
		}
	?>
    <section class="main" id="s1">
		<div>
			<form id='fpreguntas' name='fpreguntas' action="RevisarPregunta.php?email=<?php echo $email ?>" method="post" enctype="multipart/form-data">
				Id(*): <input type="text" id="id" name="id">
				<small><input type="button" id="revisarpregunta" name="revisarpregunta" value="Obtener pregunta" onclick="revisarPregunta()"></small><br/>
				Autor: <input type="text" readonly="true" style="background-color: #D3D3D3" id="email" name="email""><br/>
				Enunciado de la pregunta: <input type="text" readonly="true" style="background-color: #D3D3D3" id="enunciado" name="enunciado"><br/>
				Respuesta correcta: <input type="text" readonly="true" style="background-color: #D3D3D3" id="respCorrecta" name="respCorrecta"><br/>
			</form>
		</div>
    </section>
	<footer class='main' id='f1'>
		<a href='https://github.com/Enya0/WS18C/tree/master/SW18-Lab5'>Link GITHUB</a>
	</footer>
  </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript">
	xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
	        var response = JSON.parse(xmlhttp.responseText);
	        
	        document.getElementById("email").value = response.autor;
	        document.getElementById("enunciado").value = response.enunciado;
	        document.getElementById("respCorrecta").value = response.respuesta;     
		}
	}

    function revisarPregunta() {
        if ($('#id').val().length != 0) {
            xmlhttp.open("GET", "CogerPregunta.php?id=" + $('#id').val(), true);
            xmlhttp.send();
        }
    }
</script>
</body>
</html>

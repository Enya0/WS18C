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
    
	<div id="pregDiv" style="overflow:scroll; height:250px; width:95%;">
		<table id="preguntas" name="preguntas" border>
			<?php
			$result = mysqli_query($mysql, "SELECT * FROM preguntas");

			echo "<tr>";
			echo "<th>Email</th>";
			echo "<th>Enunciado</th>";
			echo "<th>Respuesta correcta</th>";
			echo "<th>Respuesa incorrecta 1</th>";
			echo "<th>Respuesa incorrecta 2</th>";
			echo "<th>Respuesa incorrecta 3</th>";
			echo "<th>Nivel de complejidad</th>";
			echo "<th>Tema</th>";
			echo "<th>Imagen</th>";
			echo "</tr>";

			while($row = mysqli_fetch_row($result)){
				echo "<tr>";
				echo "<td>".$row[1]."</td>";
				echo "<td>".$row[2]."</td>";
				echo "<td>".$row[3]."</td>";
				echo "<td>".$row[4]."</td>";
				echo "<td>".$row[5]."</td>";
				echo "<td>".$row[6]."</td>";
				echo "<td>".$row[7]."</td>";
				echo "<td>".$row[8]."</td>";
				echo "</tr>";
			}
			?>
		</table>
	</div>

    </section>
	<footer class='main' id='f1'>
		<a href='https://github.com/Enya0/WS18C/tree/master/SW18-Final'>Link GITHUB</a>
	</footer>
</div>
</body>
</html>

<?php
//Cerramos la conexión
mysqli_close($mysql);
?>
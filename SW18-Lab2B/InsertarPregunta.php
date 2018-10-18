<<?php 
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
	die ("Fallo al conectar a MySQL: " . mysqli_connect_error());
}

$sql = "INSERT INTO Preguntas(email, enunciado, respCorrecta, respInc1, respInc2, respInc3, complejidad, tema) VALUES('$email', '$enunciado', '$respCorrecta', '$respInc1', '$respInc2', '$respInc3', '$complejidad', '$tema')";


if (!mysqli_query($mysql ,$sql))
{
	echo('Error: ' . mysqli_error($mysql));
	echo "<p> <a href='pregunta.html'> Volver al formulario </a>";
}
else{
	echo "La pregunta se ha insertado correctamente";
	echo "<p> <a href='VerPreguntas.php'> Ver preguntas </a>";
}
//Cerramos la conexión
mysqli_close($mysql);
?>
<?php
include "ParametrosDB.php";

//Creamos la conexión
$mysql = mysqli_connect($server,$user,$pass,$basededatos);
if (!$mysql){
	die ("Fallo al conectar a MySQL: " . mysqli_connect_error());
}

if (isset($_GET['email'])){
	$email = $_GET['email'];
	$result = "DELETE FROM usuarios WHERE email='".$email."'";
	//echo $result;
	if (mysqli_query($mysql, $result)){
		//echo ("Gestión realizada con éxito");
		header('Location: GestionarCuentas.php');
	}else{
		echo ("Ha habido un error<p><a href='GestionarCuentas.php'>Puede intentarlo de nuevo</a>");
		//header('Location: GestionarCuentas.php');
	}
}

?>
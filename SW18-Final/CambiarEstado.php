<?php
include "ParametrosDB.php";

//Creamos la conexión
$mysql = mysqli_connect($server,$user,$pass,$basededatos);
if (!$mysql){
	die ("Fallo al conectar a MySQL: " . mysqli_connect_error());
}
if (isset($_GET['email'])){
	$email = $_GET['email'];
	$estate = mysqli_query($mysql, "SELECT estado FROM usuarios WHERE email = '".$email."'");
	$resultado = mysqli_fetch_row($estate);
	if ($resultado[0] == 'activo'){
		$result = "UPDATE usuarios SET estado = 'bloqueado' WHERE email = '".$email."'";
		if (mysqli_query($mysql, $result)){
			//echo("Cambio realizado con éxito");
			header('Location: GestionarCuentas.php');
		}else{
			echo("Ha habido un error<p><a href='GestionarCuentas.php'>Puede intentarlo de nuevo</a>");
			//header('Location: GestionarCuentas.php');
		}
	}else if ($resultado[0] == 'bloqueado'){
		$result = "UPDATE usuarios SET estado = 'activo' WHERE email = '".$email."'";
		if (mysqli_query($mysql, $result)){
			//echo("Cambio realizado con éxito");
			header('Location: GestionarCuentas.php');
		}else{
			echo("Ha habido un error<p><a href='GestionarCuentas.php'>Puede intentarlo de nuevo</a>");
			//header('Location: GestionarCuentas.php');
		}
	}
}
mysqli_close($mysql);
?>


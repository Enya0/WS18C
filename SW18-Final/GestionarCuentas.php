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
			if($_SESSION['rol'] == "administrador")
			{
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
		if(!isset($_SESSION['email'])){
			echo '<script language="javascript">alert("Inicia sesión para acceder a este contenido");</script>';
			header('Location: Login.php');
		} 
		if (!($_SESSION['rol']=="administrador")) {
	    	echo '<script language="javascript">alert("Este contenido es solo para administradores");</script>';
	    	header('Location: layout.php');
		}
	?>
    <section class="main" id="s1">
    
	<div id="usuDiv" style="overflow:scroll; height:650px; width:97%;" align="center">
		<table id="usuarios" name="usuarios" border>
			<?php

				$result = mysqli_query($mysql, "SELECT * FROM usuarios");

				echo "<tr>";
				echo "<th>Email</th>";
				echo "<th>Nombre y apellidos</th>";
				echo "<th>Contraseña</th>";
				echo "<th>Estado</th>";
				echo "<th>Imagen</th>";
				echo "</tr>";

				while($row = mysqli_fetch_row($result)){
					if ($row[0] != "admin@ehu.es" && $row[0] != "vadillo@ehu.es"){
						echo "<tr>";
						echo "<td style='width:25%;' align='center'>".$row[0]."</td>";
						echo "<td style='width:15%;' align='center'>".$row[1]."</td>";
						echo "<td align='center'>".$row[2]."</td>";
						echo "<td style='width:10%;' align='center'>";
						echo "<input button type='button' class='button' id='block' name='block' value='Bloquear/Desbloquear' onclick=cambiarEstado('".$row[0]."');><br><br>";
						echo $row[4]. "<br><br>";
						echo "<input button type='button' class='button' id='borra' name='borra' value='Eliminar' onclick=eliminarUsuario('".$row[0]."');>";
						echo "</td>";
						echo "<td align='center'>";
						if ($row[3]!= ""){
							echo "<img src='data:image/jpg;base64,".base64_encode($row[3]). "' width='100'/>"; 
						}
						echo "</td>";
						echo "</tr>";
					}
				}
			?>
		</table>
	</div>

    </section>
	<footer class='main' id='f1'>
		<a href='https://github.com/Enya0/WS18C/tree/master/SW18-Final'>Link GITHUB</a>
	</footer>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript">

	function cambiarEstado(usuario){
		//$estate = mysqli_query($mysql, "SELECT estado FROM usuarios WHERE email = $usuario");
		if (confirm("¿Seguro que quieres bloquear/desbloquear esta cuenta?")){
			document.location = 'CambiarEstado.php?email='+usuario;
		}else{
			header('Location: GestionarCuentas.php');
		}
	}

	function eliminarUsuario(usuario){
		if (confirm("¿Seguro que quieres eliminar esta cuenta?")){
			document.location = 'BorrarUsuario.php?email='+usuario;
		}else{
			header('Location: GestionarCuentas.php');
		}
	}

</script>

</body>
</html>

<?php
//Cerramos la conexión
mysqli_close($mysql);
?>
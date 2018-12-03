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
			<form id="fObtenerDatos" name="fObtenerDatos" action="ObtenerDatos.php" method="post">
				Email:<input type="text"  size="50" id="email" name="email" required/><br>
				Nombre: <input type="text" size="50" id="nombre" name="nombre" disabled/><br>
				Apellidos: <input type="text" size="50" id="apellidos" name="apellidos" disabled/><br>
				Teléfono: <input type="text" size="20" id="telefono" name="telefono" disabled/><br>
				<input type="button" id="obtenerDatos" name="obtenerDatos" value="Buscar datos del usuario">
				<input type="reset" id="borrar" name="borrar" value="Borrar">
			</form>
		</div>
    </section>
	<footer class='main' id='f1'>
		<a href='https://github.com/Enya0/WS18C/tree/master/SW18-Lab5'>Link GITHUB</a>
	</footer>
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<script type="text/javascript">
		$("#obtenerDatos").click(function(){
			if ($("#email").val() != ""){
				//Siguiendo la ayuda dada en el enunciado del laboratorio
				$.get('xml/usuarios.xml', function(d){
	 				var listaUsuarios = $(d).find('usuario'); //En este caso busco el usuario con todos sus datos, no solo el correo
	 					encontrado = false;
		 				for (var i = 0; i<listaUsuarios.length; i++){
		 					//Busco el usuario cuyo email sea el introducido
		 					if (listaUsuarios[i].children[0].textContent == $('#email').val()) {
		 						encontrado = true;
		 						$("#email").val(listaUsuarios[i].children[0].textContent);
		 						$("#nombre").val(listaUsuarios[i].children[1].textContent);
			 					$("#apellidos").val(listaUsuarios[i].children[2].textContent + " " + listaUsuarios[i].children[3].textContent);
			 					$("#telefono").val(listaUsuarios[i].children[4].textContent);
		 					}
		 				}
		 				if (!encontrado) {
	 						alert("El correo introducido no pertenece a ningún usuario existente, inténtelo con otro");
	 					}				
	 			});
			}
		 	else{
		 		alert("Introduzca un correo, por favor");
		 	}
	 	});
	 </script>
</body>
</html>
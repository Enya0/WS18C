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
		<form id='fpreguntas' name='fpreguntas' action="InsertarPreguntaConFoto.php?email=<?php echo $email ?>" method="post" enctype="multipart/form-data">
			Dirección de correo(*): <input type="text" readonly="true" style="background-color: #D3D3D3" id="email" name="email" value="<?php echo $email ?>"><br/>
			Enunciado de la pregunta(*): <input type="text" id="enunciado" name="enunciado"><br/>
			Respuesta correcta(*): <input type="text" id="respCorrecta" name="respCorrecta"><br/>
			Respuesta incorrecta 1(*): <input type="text" id="respInc1" name="respInc1"><br/>
			Respuesta incorrecta 2(*): <input type="text" id="respInc2" name="respInc2"><br/>
			Respuesta incorrecta 3(*): <input type="text" id="respInc3" name="respInc3"><br/>
			Complejidad de la pregunta [0..5](*): <input type="text" id="complejidad" name="complejidad"><br/>
			Tema de la pregunta(*): <input type="text" id="tema" name="tema"><br/>
			<input type="file" typ="uploadedfile" id="imagen" name="imagen"><br/>
			<input type="submit" id="enviar" name="enviar" value="Enviar solicitud">
		</form>
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<script type="text/javascript">
		$("#fpreguntas").submit(function(){
			var exprEmail =  /[a-zA-Z]+[0-9]{3}@ikasle\.ehu\.eus/
			var exprComp = /[0-5]/

			console.log()
			if ($("#email").val().length == 0){
				alert("Por favor, introduzca un email");
				return false;
			}
			else if (exprEmail.test($("#email").val()) == false){
				alert("El correo introducido no es válido");
				return false;
			}
			else if ($("#enunciado").val().length == 0){
				alert("Por favor, introduzca un enunciado");
				return false;
			}
			else if ($("#enunciado").val().length < 10){
				alert("El enunciado debe tener, al menos, 10 caracteres");
				return false;
			}
			else if ($("#respCorrecta").val().length == 0){
				alert("Por favor, asegúrese de que ha introducido todas las respuestas");
				return false;
			}
			else if ($("#respInc1").val().length == 0){
				alert("Por favor, asegúrese de que ha introducido todas las respuestas");
				return false;
			}
			else if ($("#respInc2").val().length == 0){
				alert("Por favor, asegúrese de que ha introducido todas las respuestas");
				return false;
			}
			else if ($("#respInc3").val().length == 0){
				alert("Por favor, asegúrese de que ha introducido todas las respuestas");
				return false;
			}
			else if ($("#complejidad").val().length == 0){
				alert("Por favor, introduzca un nivel de complejidad");
				return false;
			}
			else if (exprComp.test($("#complejidad").val()) == false){
				alert("La complejidad introducida no es válida");
				return false;
			}
			else if ($("#tema").val().length == 0){
				alert("Por favor, introduzca un tema");
				return false;
			}
		});
		//Referencias utilizadas para saber cómo hacer este apartado:
		//https://www.uno-de-piera.com/previsualizar-imagenes-con-javascript/
		//https://programacion.net/articulo/hacer_preview_de_una_imagen_antes_de_subirla_utilizando_jquery_1710
		$("#imagen").click(function(event){
			$("input[type=file]").change(function(event) {
				readURL(this);
			});
			const readURL = (input) => {
 
		        if (input.files && input.files[0]) {
		            const reader = new FileReader();

		            reader.onload = (e) => {
		            	$("#fpreguntas + img").remove();
		            	$("img").remove();
            			$("#imagen").after('<img src="'+e.target.result+'" width="100" height="75"/>');
		            }
		            reader.readAsDataURL(input.files[0]);
		        }
			}
			if(!$("#quitarImagen").length){
				var input = $("<input/>", {
					type: "button", id: "quitarImagen", name: "Eliminar imagen", value: "Eliminar imagen"
				});
				$("#fpreguntas").append(input);
				$("#quitarImagen").bind("click",function(){
					$("img").remove();
					$("#quitarImagen").remove();
					$("img").css("style", "display:none;");
					$("#imagen").val("");
				});
		    }
		});
	</script>
    </section>
	<footer class='main' id='f1'>
		<a href='https://github.com/Enya0/WS18C/tree/master/SW18-LabPrevioSeguridad'>Link GITHUB</a>
	</footer>
</div>
</body>
</html>

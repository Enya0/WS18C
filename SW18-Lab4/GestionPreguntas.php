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
    	<div><p id="usuariosConectados"></p></div>
    	<p id="numeroPreguntas"></p>
		<div>
			<form id='fpreguntas' name='fpreguntas' action="InsertarPreguntaConFoto.php?email=<?php echo $email ?>" method="post" enctype="multipart/form-data">
				<input type="button" id="verpreguntas" name="verpreguntas" value="Ver preguntas" onclick="verPreguntas();">
				<input type="button" id="insertarpregunta" name="insertarpregunta" value="Insertar pregunta" onclick="insertarPregunta();"><br/><br/>
				Email(*): <input type="text" readonly="true" style="background-color: #D3D3D3" id="email" name="email" value="<?php echo $email ?>"><br/>
				Enunciado de la pregunta(*): <input type="text" id="enunciado" name="enunciado"><br/>
				Respuesta correcta(*): <input type="text" id="respCorrecta" name="respCorrecta"><br/>
				Respuesta incorrecta 1(*): <input type="text" id="respInc1" name="respInc1"><br/>
				Respuesta incorrecta 2(*): <input type="text" id="respInc2" name="respInc2"><br/>
				Respuesta incorrecta 3(*): <input type="text" id="respInc3" name="respInc3"><br/>
				Complejidad de la pregunta [0..5](*): <input type="text" id="complejidad" name="complejidad"><br/>
				Tema de la pregunta(*): <input type="text" id="tema" name="tema"><br/>
				<input type="file" typ="uploadedfile" id="imagen" name="imagen">
			</form>
		</div>
		<div id='datos' style="overflow: scroll; height: 80px;" align="center"></div>
    </section>
	<footer class='main' id='f1'>
		<a href='https://github.com/Enya0/WS18C/tree/master/SW18-Lab4'>Link GITHUB</a>
	</footer>
  </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript">

	function insertarPregunta(){

		var preg = $("#fpreguntas").get(0);
		console.log($("#fpreguntas").get(0));

		$.ajax({
            url: 'InsertarPreguntaConFoto.php',
            type: 'POST',
            data: new FormData(preg),
            dataType: 'json',
            mimeType: 'multipart/form-data',
            processData:false,
            contentType: false,
            success: function (tabla) {
                verPreguntas();
            },
            error: function (data) {
                verPreguntas();
            }
        });
        $('#fpreguntas')[0].reset();
        $("img").remove();
        $("#quitarImagen").remove();
	}

	xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function(){
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
				document.getElementById("datos").innerHTML = xmlhttp.responseText;
			}
	}
	function verPreguntas(){
		xmlhttp.open("GET", "VerPreguntasXML.php?email=<?php echo $email ?>");
		xmlhttp.send();		
	}

    function cuantasPreguntas(){
    	var pregsTotales = 0;
    	var pregsMias = 0;
    	$.ajax({
    		type: "GET",
    		url: "xml/preguntas.xml",
    		cache: false,
    		dataType: "xml",
    		success: function(xml){
    			$(xml).find('assessmentItem').each(function(){
    				pregsTotales++;
    				if($('#email').val() === $(this).attr('author')){
    					pregsMias++;
    				}
    			})
    			$('#numeroPreguntas').text('Mis preguntas/Todas las preguntas: ' + pregsMias + '/' + pregsTotales);
    		}
    	});
    }
    cuantasPreguntas();
    setInterval(cuantasPreguntas(),20000);

    xmlhttpUsuarios = new XMLHttpRequest();
	xmlhttpUsuarios.onreadystatechange = function(){
		if (xmlhttpUsuarios.readyState == 4 && xmlhttpUsuarios.status == 200){
				document.getElementById("usuariosConectados").innerHTML = "Número de usuarios editando preguntas: " + xmlhttpUsuarios.responseText;
			}
	}

	function cuantosUsuarios(){
		xmlhttpUsuarios.open("GET", "xml/contador.xml");
		xmlhttpUsuarios.send();	

	}

	cuantosUsuarios();
	setInterval(cuantosUsuarios(),20000);

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
</body>
</html>

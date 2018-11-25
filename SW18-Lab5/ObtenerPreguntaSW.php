<?php
    require_once('nusoap-1-master/lib/nusoap.php');

    function ObtenerPregunta($id){
    	include "ParametrosDB.php";
    	$mysql = mysqli_connect($server,$user,$pass,$basededatos);
		if (!$mysql){
			return array("autor" => "Fallo al conectar a MySQL: " . mysqli_connect_error(), "enunciado" => "", "respuesta" => "");
		}
		$result = mysqli_query($mysql, "SELECT email, enunciado, respCorrecta FROM preguntas WHERE id = '$id'");  
		$encontrada = false;
		while($row = mysqli_fetch_array($result)) {
			$pregunta = array("autor" => $row['email'], "enunciado" => $row['enunciado'], "respuesta" => $row['respCorrecta']);
			$encontrada = true;
		}
		mysqli_close($mysql);
		if (!$encontrada){
			$pregunta = array("autor" => "", "enunciado" => "", "respuesta" => "");
		}
		return $pregunta;
    }

    $server = new soap_server();
    $server->configureWSDL("ObtenerPreguntaSW", "urn:ObtenerPreguntaSW");
    $server->register("ObtenerPregunta",
        array("id" => "xsd:int"),
        array("autor" => "xsd:string", "enunciado" => "xsd:string", "respuesta" => "xsd:string"),
        "urn:ObtenerPreguntaSW",
        "urn:ObtenerPreguntaSW#ObtenerPregunta",
        "rpc",
        "encoded",
        "Devuelve la pregunta correspondiente al id dado");

    if ( !isset( $HTTP_RAW_POST_DATA ) ) $HTTP_RAW_POST_DATA =file_get_contents( 'php://input' );
    $server->service($HTTP_RAW_POST_DATA);
?>
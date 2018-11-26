<?php
	require_once('nusoap-0.9.5/lib/nusoap.php');
	$soapclient = new nusoap_client('http://magnasis.com/enya/SW18-Lab5/ObtenerPreguntaSW.php?wsdl', true);
	if (isset($_GET['id'])){
		$cliente = $soapclient->call('ObtenerPregunta', array('id' => $_GET['id']));

		echo json_encode($cliente);
		//echo $cliente['autor'];
		//echo $cliente['enunciado'];
		//echo $cliente['respuesta'];
		//echo '<h2>Request</h2><pre>' . htmlspecialchars($soapclient->request, ENT_QUOTES) . '</pre>';
		//echo '<h2>Response</h2><pre>' . htmlspecialchars($soapclient->response, ENT_QUOTES) . '</pre>';
		//echo '<h2>Debug</h2>';
		//echo '<pre>' . htmlspecialchars($soapclient->debug_str, ENT_QUOTES) . '</pre>';
	}
?>
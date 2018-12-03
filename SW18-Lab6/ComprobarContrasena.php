<?php
	require_once('nusoap-0.9.5/lib/nusoap.php');
	$soapclientPass = new nusoap_client('http://enya-sw15.hol.es/SW18-Lab6/ComprobarPassword.php?wsdl', true);
	//$soapclientPass = new nusoap_client('http://localhost/SW18/SW18-Lab5/ComprobarPassword.php?wsdl', true);
	$ticket = 1010;

	if (isset($_GET['contrasena'])){
		echo $soapclientPass->call('comprobarPassword',array('passw'=>$_GET['contrasena'], 'ticket'=>$ticket));
		//echo '<h2>Request</h2><pre>' . htmlspecialchars($soapclientPass->request, ENT_QUOTES) . '</pre>';
		//echo '<h2>Response</h2><pre>' . htmlspecialchars($soapclientPass->response, ENT_QUOTES) . '</pre>';
		//echo '<h2>Debug</h2>';
		//echo '<pre>' . htmlspecialchars($soapclientPass->debug_str, ENT_QUOTES) . '</pre>';
	}		
?>
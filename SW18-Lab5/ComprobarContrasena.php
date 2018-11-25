<?php
	require_once('nusoap-1-master/lib/nusoap.php');
	$soapclientPass = new nusoap_client( 'http://magnasis.com/enya/SW18-Lab5/ComprobarPassword.php?wsdl', true);
	$ticket = 1010;

	if (isset($_GET['contrasena'])){
		echo $soapclientPass->call('comprobarPassword',array('passw'=>$_GET['contrasena'], 'ticket'=>$ticket));
	}		
?>
<?php
	require_once('nusoap-0.9.5/lib/nusoap.php');
	$soapclient = new nusoap_client( 'http://ehusw.es/jav/ServiciosWeb/comprobarmatricula.php?wsdl', true);
	if (isset($_GET['email'])){
		echo $soapclient->call('comprobar',array( 'x'=>$_GET['email']));
	}
?>
<?php
	require_once('nusoap-1-master/lib/nusoap.php');
	$soapclient = new nusoap_client( 'http://ehusw.es/jav/ServiciosWeb/comprobarmatricula.php?wsdl', true);
	if (isset($_GET['email'])){
		echo $soapclient->call('comprobar',array( 'x'=>$_GET['email']));
	}
?>
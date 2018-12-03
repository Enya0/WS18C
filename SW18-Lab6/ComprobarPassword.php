<?php
    require_once('nusoap-0.9.5/lib/nusoap.php');
    //require_once('nusoap-0.9.5/lib/class.wsdlcache.php');

    function comprobarPassword($passw, $ticket) {
        if ($ticket == 1010){
            $fichero = "toppasswords.txt";
            $file = fopen($fichero, "r");
            while(!feof($file)){
                $contenido = fgets($file);
                if(strstr($contenido, $passw)){
                    return "INVALIDA";
                }
            }
            return "VALIDA";
        }
    }
    $server = new soap_server();
    $server->configureWSDL("ComprobarPassword", "urn:ComprobarPassword");
    $server->register("comprobarPassword",
        array("passw" => "xsd:string", "ticket" => "xsd:int"),
        array("response" => "xsd:string"),
        "urn:ComprobarPassword",
        "urn:ComprobarPassword#comprobarPassword",
        "rpc",
        "encoded",
        "Comprueba la validez de una contrasena");

    if ( !isset( $HTTP_RAW_POST_DATA ) ) $HTTP_RAW_POST_DATA =file_get_contents( 'php://input' );
    $server->service($HTTP_RAW_POST_DATA);
?>
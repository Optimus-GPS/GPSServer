<?php

function envioSukarne($loc,$user,$pass,$temp_value,$type,$economico,$placa){
	

	$parametros=array(); //parametros de la llamada
	$parametros['placa']		= (empty($placa) ? 0 : $placa);
	$parametros['economico']	= $economico;
	$parametros['timestamp']	= $loc['fixtime']/1000;
	$parametros['latitude']		= (float)$loc['lat'];
	$parametros['longitude']	= (float)$loc['lng'];
	$parametros['height']		= (int)$loc['altitude'];
	$parametros['speed']		= (int)$loc['speed'];
	$parametros['course']		= ((int)$loc['angle'] == 0 ? 319 : (int)$loc['angle']);
	$parametros['sats']			= (int)"0";
	$parametros['event']		= (int)"0";
	$parametros['temp'] 		= (float)$temp_value;
	$parametros['unitType']		= (int)$type;
						

	$file2 = fopen("log_SUKARNE_".date("d-m-Y",time()).".txt", "a");
	$wsdl = "http://apps.cttmx.com/ws_integration/service.php?wsdl";
	// if ($loc['imei'] == "868789021991194"){		
		// $parametros['course'] = 319;
	// }	
	
	$client = new SoapClient($wsdl,array('exceptions' => 0));

	$result = $client->insertUnits($user,$pass,array($parametros));
	fwrite($file2, print_r($parametros,true));
	fwrite($file2, print_r($result,true));
	
	if (is_soap_fault($result)) {
		
		$error = trigger_error("SOAP Fault: (faultcode: {$result->faultcode}, faultstring: {$result->faultstring}) Unidad: ".$economico, E_USER_ERROR);
		fwrite($file2, print_r($error,true));
		fclose($file2);
	}

	
	fclose($file2);
}
					
?>
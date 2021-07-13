<?php

class CustomerWalmart
{
		var $id;          //    Identidicador       A0
		var $name;        //    Nombre              A0
		//var $id = 'A12';                    //    Identidicador       A0
		//var $name = 'Guadalupe';            //    Nombre              A0
}

class EventWalmart
{
		function __construct($comp){
			$this->customer = $comp;
		}

		var $code;        // ** Código              [0, 911, 912]
		var $date;        // ** Fecha/Hora          2020-02-20T13:15:22
		var $latitude;    // ** Latitud             28.2882
		var $longitude;   // ** Longitud            -105.5069
		var $asset;       // ** Placa               123ABC      

		var $serialNumber;//    Número              A0
		var $direction;   //    Dirección           A0
		var $speed;       // ** Velocidad           170      
		var $altitude;    //    Altitud             99
		var $customer;    //    OBJETO

		var $shipment;    // ** Shipment            A0
		var $odometer;    //    Odómetro            99
		var $ignition;    //    Ignición            True/False
		var $battery;     //    Batería             99
		var $course;      //    Curso               ['N ', 'NE', 'E', 'SE', 'S', 'SO', 'O', 'NO']
}


function castDateSQL_T($date){
	return str_replace(' ', 'T', trim($date));
}

function envioWalmart($loc,$user,$pass){
	
	$file = fopen("log_Walmart.txt", "w");
	
	$wsdl = 'http://gps.rcontrol.com.mx/Tracking/wcf/RCService.svc?wsdl';
	// $user = 'wm_10066_OPTIMUS';
	// $pass = 'DZTo/!046urqB/!3';	
	
	// fwrite($file, print_r($user,true));
	fwrite($file, print_r($loc,true));

	$soap = new SoapClient($wsdl, array('classmap' => array('Event' => 'Event', 'Customer' => 'Customer')));

	$response = $soap->GetUserToken( array('userId' => $user, 'password' => $pass)) ;
	$token = $response->GetUserTokenResult->token;

	


		$CustomerType = new CustomerWalmart();
		
		$CustomerType->id = '66319';
		$CustomerType->name = '10066';

		
		$EventType = new EventWalmart($CustomerType);
		
		$EventType->code        = '0';
		$EventType->date        = castDateSQL_T($loc['dt_tracker']);
		$EventType->latitude    = (double)$loc['lat'];
		$EventType->longitude   = (double)$loc['lng'];
		$EventType->asset       = (empty($loc_ws['plate_number']) ? 0 : $loc_ws['plate_number']);
		$EventType->serialNumber= $loc['imei']; 		
		$EventType->direction   = '';       			
		$EventType->speed       = (int)$loc['speed'];   
		$EventType->altitude    = (int)$loc['altitude'];
		$EventType->shipment    = '12A';              	
		$EventType->odometer    = '0';
		$EventType->ignition    = '0';            		
		$EventType->battery     = '87';                	
		$EventType->course      = $loc['angle'];

		$events = array();
		$events[0] = $EventType;


		$response = $soap->GPSAssetTracking(array('token' => $token, 'events' => $events));
		
		fwrite($file, print_r($events,true));
		fwrite($file, print_r(json_encode($response),true));

	  echo json_encode($response);

}

// $dummyLoc = array(
	// 'imei' => '868789022104953',
	// 'protocol' => 'gl200',
	// 'dt_server' => '2020-08-17 19:56:25',
	// 'dt_tracker' => '2020-08-17T19:54:32',
	// 'lat' => '20.125278',
	// 'lng' => '-98.11349',
	// 'altitude' => 0,
	// 'angle' => 0,
	// 'speed' => 0,
	// 'loc_valid' => 1,
	// 'params' => array
		// (
			// 'hdop' => 1,
			// 'batteryLevel' => 86,
			// 'ignition' => 0,
			// 'input' => 0,
			// 'output' => 0,
			// 'type' => 'FRI',
			// 'distance' => 0,
			// 'totalDistance' => '5358133.37',
			// 'motion' => '',
			// 'hours' => '364990000'
		// ),

	// 'event' => '',
	// 'net_protocol' => '',
	// 'ip' => '',
	// 'port' => ''
// );

   // envioWalmart($dummyLoc, 'wm_10066_OPTIMUS', 'DZTo/!046urqB/!3');




// ?>
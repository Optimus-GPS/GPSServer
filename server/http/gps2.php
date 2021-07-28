<?
ob_start();
echo "OK";
header("Connection: close");
header("Content-length: " . (string) ob_get_length());
ob_end_flush();
if (!isset($_GET["deviceid"])) {
	die;
}

chdir('../');
include('s_insert.php');


$loc = array();

$mil = $_GET["fixtime"];
$seconds = $mil / 1000;
$fecha1 = date("Y-m-d H:i:s", $seconds);

$valid = $_GET["valid"];
if ($valid == true || $valid == 'true') {
	$valid = 1;
} else {
	$valid = 0;
}
	
$evento = "";
$result = "";
$date = date("Y-m-d H:i:s");
$fecha_server = strtotime ( '-0 hour' , strtotime ($date) ) ; 
$fecha_server = date ( 'Y-m-j H:i:s' , $fecha_server);
$speed = floor($_GET["speed"]);
$attributes = str_replace("\\", "", $_GET["attributes"]);
$eventos = json_decode($attributes,true);
				if (isset($eventos['alarm']))
                {
                    $result .= $eventos['alarm'].', ';
                }
      $evento = substr($result, 0, -2);


$attributes = paramsToArray($attributes);
//$attributes = str_replace('}"', '}',$attributes);  		 

$loc['imei'] = $_GET["deviceid"];
$loc['protocol'] = $_GET["protocol"];
$loc['dt_server'] = $fecha_server;
$loc['dt_tracker'] = $fecha1;
$loc['lat'] = (float) sprintf('%0.6f', $_GET["latitude"]);
$loc['lng'] = (float) sprintf('%0.6f', $_GET["longitude"]);
$loc['altitude'] = floor($_GET["altitude"]);
$loc['angle'] = floor($_GET["course"]);
$loc['speed'] = $speed * 1.852;
$loc['loc_valid'] = $valid;
$loc['params'] = $attributes;
$loc['event'] = $evento;
$loc['net_protocol'] = '';
$loc['ip'] = '';
$loc['port'] = '';
// $loc['positionid'] = $_GET['positionid'];

if (($loc['lat'] == 0) || ($loc['lng'] == 0)) {
	$valid = 0;
}else if (($loc['lat'] == '0') || ($loc['lng'] == '0')){
	$valid = 0;
}
	

//$loc['params'] = "json_encode($loc['params'])";
//Direccion
//$longitud = $loc['lng'];
//$latitud = $loc['lat'] ;
//$search = $latitud.','.$longitud;		
	//	$result = '';
		
               
		        //http://locationiq.org/v1/reverse.php?&key=8de10067a9f5aa243103&email='.$gsValues['EMAIL'].'&format=json&lat='.$lat.'&lon='.$lng.'&zoom=18&addressdetails=1
				
				//$url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$search.'&oe=utf-8&key=AIzaSyATzjD2bp7qZ5a48RvDcZownwBOm1x8BIk';
			  // $url = 'http://locationiq.org/v1/reverse.php?&key=8de10067a9f5aa243103&email=jagdonoso@gmail.com&format=json&lat=-34.569336&lon=-58.742039&zoom=18&addressdetails=1';
		//$data = @file_get_contents($url);
	//	$jsondata = json_decode($data,true);
		//		if(is_array($jsondata) && $jsondata['status']=="OK")
		//{
			//$result = $jsondata['results'][0]['formatted_address'];
		//}
		//$calles = json_encode($result);
//$loc['direccion'] = $calles;	
		//Fin direccion
//Registrar en alicorp #################################################################################
// $q = "SELECT * FROM gs_objects WHERE `imei`='".$loc['imei']."' AND `alicorp`='true'";
// $r = mysqli_query($ms, $q);
// $row = mysqli_fetch_array($r);

// if (count($row) > 0) {
	// $res_alicorp = "";

	// try {
		// $url = "http://alicorp.gpsgoldcar.com/tracks/agent-tracks/";

		// //$current_date
		// $date_format = date("Y-m-d H:i:s");
		// $satellites = (@$attributes['sat']) ? $attributes['sat']: 0;

		// $post_vars = [[
			// "avl"			=> $row['name'],
			// "timestamp"		=> $date_format,
			// "latitude"		=> $loc['lat'] . "",
			// "longitude"		=> $loc['lng'] . "",
			// "altitude"		=> $loc['altitude'],
			// "speed"			=> intval($loc['speed']),
			// "course"		=> $loc['angle'] . "",
			// "satellites"	=> $satellites,
		// ]];

		// //print_r(json_encode($post_vars));
		// //return;

		// $ch = curl_init();
		// curl_setopt($ch, CURLOPT_URL, $url);
		// curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
		// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		// curl_setopt($ch, CURLOPT_POST, 1);
		// curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_vars));
		// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		// curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		// curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 25);

		// $response = curl_exec($ch);
		// $info = curl_getinfo($ch);
		// $err = curl_error($ch);
		// curl_close($ch);

		// $res_alicorp = PHP_EOL . $info['http_code'] . " | " . print_r($post_vars, true) . " | " . $response . PHP_EOL;
	// } catch (Exception $exc) {
		// $res_alicorp = $exc->getMessage();
	// }

	// $file = fopen("alicorp.txt", "a");
	// fwrite($file, $res_alicorp);
	// fclose($file);
// }

$file = fopen("Archivo_GS_Log.txt", "w");
fwrite($file, print_r($loc, true));
fclose($file);

insert_db_loc($loc);
if (@$loc['loc_valid'] == 1)
{
	insert_db_loc($loc);	
}
else if (@$loc['loc_valid'] == 0)
{
	insert_db_noloc($loc);
}
if ($loc['protocol']== "calamp"){
	if (($loc['lat']== "0") || ($loc['lat']== 0)){

		$file = fopen("archivo.txt", "w");
		fwrite($file, print_r($_GET, true));
		fwrite($file, print_r($loc, true));
		// fwrite($file, print_r($loc2, true));
		fclose($file);
	}
}

//mysqli_close($ms);
die;

?>
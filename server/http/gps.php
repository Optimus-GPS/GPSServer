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



if ($_GET["deviceid"] == "3133013871"){
	$file = fopen("archivo_forward.txt", "w");
	fwrite($file, print_r($_GET, true));
	fclose($file);
}
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
$loc['fixtime'] = $_GET["fixtime"];
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

$loc['positionid'] = $_GET['positionid'];

$var22 = "http://95.111.244.100/server/http/gps2.php?" . "deviceid=" . $loc["imei"] ."&protocol=".$_GET["protocol"]."&attributes=".$_GET["attributes"]."&fixtime=".$_GET["fixtime"]."&longitude=".$_GET["longitude"]."&latitude=".$_GET["latitude"]."&altitude=".$_GET["fixtime"]."&speed=".$_GET["speed"]."&course=".$_GET["course"]."&valid=".$_GET["valid"];
// $file = fopen("archivo1.txt", "w");
fwrite($file, print_r($var22, true));
fwrite($file, print_r($loc2, true));
fclose($file);

if (($loc['lat'] == 0) || ($loc['lng'] == 0)) {
	$valid = 0;
}else if (($loc['lat'] == '0') || ($loc['lng'] == '0')){
	$valid = 0;
}

insert_db_loc($loc);
if (@$loc['loc_valid'] == 1)
{
	insert_db_loc($loc);	
}
else if (@$loc['loc_valid'] == 0)
{
	insert_db_noloc($loc);
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://optimusrastreogps.net/server/http/gps2.php?" . "deviceid=" . $_GET["deviceid"] ."&protocol=".$_GET["protocol"]."&attributes=".$_GET["attributes"]."&fixtime=".$_GET["fixtime"]."&longitude=".$_GET["longitude"]."&latitude=".$_GET["latitude"]."&altitude=".$_GET["fixtime"]."&speed=".$_GET["speed"]."&course=".$_GET["course"]."&valid=".$_GET["valid"]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
curl_close($ch);


mysqli_close($ms);
die;

?>

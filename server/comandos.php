<?php
ini_set('display_errors', 1);

set_time_limit(0);

require 'vendor/autoload.php';
include ('s_init.php');
include ('s_events.php');

$url = 'http://localhost:8082/api/commands/send';
//$url = 'http://localhost:8082/api/commands/send';

$file = 'log_commands_'.date("d-m-Y",time()).'.txt';
$q = "SELECT *, td.id AS device_id FROM gs_object_cmd_exec gc, gs_objects go, tc_devices td WHERE gc.imei=go.imei AND go.traccar_id=td.id AND gc.status='0' ORDER BY cmd_id ASC";
$r = mysqli_query($ms, $q);


while ($row = mysqli_fetch_array($r)) {

    $txt = date("d/m/Y h:i:s a", time()) . " | " . $row['cmd_id'] . " | " . $row['imei'] . " | " . $row['type'] . " | " . $row['cmd'] . " | " . $row['traccar_id'];
    //echo $txt . "<br>";
    write_file($file, $txt);
    
    $client = new \GuzzleHttp\Client();

    $data = [
                'body' => json_encode([
                    //'deviceId' => 2,
                    'deviceId' => intval($row['device_id']),
                    'type' => 'custom',
                    'attributes' => [
                        'data' => $row['cmd']
                    ]
                ]),
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode("admin:admin"),
                ]
            ];

    // start request
    $promise = $client->postAsync($url, $data)->then(
        function ($response) {
            return $response;
        }, function ($exception) {
            return $exception->getMessage();
        }
    );

    // wait for request to finish and display its response
    $response = $promise->wait();

    //print_r( json_decode($response->getBody()->getContents(), true) );
	//echo "######" . $row['id'];
	//print_r($response);
	$txt = print_r($response, true);
	write_file($file, $txt);
	
	if (is_string($response)) {
		echo $txt;
		continue;
	}
	
	$txt = $response->getBody()->getContents() . " | " . $response->getStatusCode();
	write_file($file, $txt);
    
    if ( $response->getStatusCode() == '200' ) { // || $response->getStatusCode() == '202' ) {
        $q2 = "UPDATE gs_object_cmd_exec SET status='1' WHERE cmd_id='".$row["cmd_id"]."'";
        $r2 = mysqli_query($ms, $q2);    
    }
}

function write_file($file, $txt) {
    file_put_contents($file, $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
};

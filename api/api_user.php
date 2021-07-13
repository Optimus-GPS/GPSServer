<?
    if (@$api_access != true) { die; }
        
    // split command and params
    //$cmd = explode(',', $cmd);
	$cmd = urldecode($cmd);
	$cmd = stripslashes($cmd);
	$cmd = str_getcsv($cmd, ",", '"');
	$command = @$cmd[0];
	$command = strtoupper($command);
	
	if ($command == 'USER_GET_MARKERS')
    {
		// command validation
        if (count($cmd) < 1) { die; }		
		
		$q = "SELECT * FROM `gs_user_markers` WHERE `user_id`='".$user_id."' ORDER BY `marker_name` ASC";
		$r = mysqli_query($ms, $q);
		
		$result = array();
		
		while($row=mysqli_fetch_array($r))
		{
			$marker_id = $row['marker_id'];			
			$result[$marker_id] = array(	'name' => $row['marker_name'],
											'desc' => $row['marker_desc'],
											'icon' => $row['marker_icon'],
											'visible' => $row['marker_visible'],
											'lat' => $row['marker_lat'],
											'lng' => $row['marker_lng']
											);
		}
		
		header('Content-type: application/json');
        echo json_encode($result); 
	}
	
	if ($command == 'USER_GET_ROUTES')
    {
		// command validation
        if (count($cmd) < 1) { die; }		
		
		$q = "SELECT * FROM `gs_user_routes` WHERE `user_id`='".$user_id."' ORDER BY `route_name` ASC";
		$r = mysqli_query($ms, $q);
		
		$result = array();
		
		while($row=mysqli_fetch_array($r))
		{
			$route_id = $row['route_id'];			
			$result[$route_id] = array(	'name' => $row['route_name'],
										'color' => $row['route_color'],
										'visible' => $row['route_visible'],
										'name_visible' => $row['route_name_visible'],
										'deviation' => $row['route_deviation'],
										'points' => $row['route_points']
										);
		}
		
		header('Content-type: application/json');
        echo json_encode($result); 
	}
	
	if ($command == 'USER_GET_ZONES')
    {
		// command validation
        if (count($cmd) < 1) { die; }		
		
		$q = "SELECT * FROM `gs_user_zones` WHERE `user_id`='".$user_id."' ORDER BY `zone_name` ASC";
		$r = mysqli_query($ms, $q);
		
		$result = array();
		
		while($row=mysqli_fetch_array($r))
		{
			$zone_id = $row['zone_id'];
			$result[$zone_id] = array(	'name' => $row['zone_name'],
										'color' => $row['zone_color'],
										'visible' => $row['zone_visible'],
										'name_visible' => $row['zone_name_visible'],
										'area' => $row['zone_area'],
										'vertices' => $row['zone_vertices']
										);
		}
		
		header('Content-type: application/json');
        echo json_encode($result); 
	}
	
	if ($command == 'USER_GET_OBJECTS')
    {
		// command validation
        if (count($cmd) < 1) { die; }
		
		$q = "SELECT * FROM `gs_user_objects` WHERE `user_id`='".$user_id."'";
		$r = mysqli_query($ms, $q);
		
		$result = array();
		
		while($row = mysqli_fetch_array($r))
		{
			$imei = $row['imei'];
			
			$q2 = "SELECT * FROM `gs_objects` WHERE `imei`='".$imei."'";
			$r2 = mysqli_query($ms, $q2);
			
			$row2 = mysqli_fetch_array($r2);
			
			if ($row2)
			{
				$q3 = "SELECT * FROM `gs_object_custom_fields` WHERE `imei`='".$row2['imei']."' ORDER BY name ASC";
				$r3 = mysqli_query($ms, $q3);
				
				$custom_fields = array();
				
				while ($row3 = mysqli_fetch_array($r3))
				{
					$custom_fields[] = array('name' => $row3['name'], 'value' => $row3['value']);
				}
		
				$result[] = array(	'imei' => $row2['imei'],
									'protocol' => $row2['protocol'],
									'net_protocol' => $row2['net_protocol'],
									'ip' => $row2['ip'],
									'port' => $row2['port'],
									'active' => $row2['active'],
									'object_expire' => $row2['object_expire'],
									'object_expire_dt' => $row2['object_expire_dt'],
									'dt_server' => $row2['dt_server'],
									'dt_tracker' => $row2['dt_tracker'],
									'lat' => $row2['lat'],
									'lng' => $row2['lng'],
									'altitude' => $row2['altitude'],
									'angle' => $row2['angle'],
									'speed' => $row2['speed'],
									'params' => json_decode($row2['params'],true),
									'loc_valid' => $row2['loc_valid'],
									'dt_last_stop' => $row2['dt_last_stop'],
									'dt_last_idle' => $row2['dt_last_idle'],
									'dt_last_move' => $row2['dt_last_move'],
									'name' => $row2['name'],
									'device' => $row2['device'],
									'sim_number' => $row2['sim_number'],
									'model' => $row2['model'],
									'vin' => $row2['vin'],
									'plate_number' => $row2['plate_number'],
									'odometer' => $row2['odometer'],
									'engine_hours' => $row2['engine_hours'],
									'custom_fields' => $custom_fields
									);
			}
		}
		
		header('Content-type: application/json');
        echo json_encode($result); 
	}
	
	if ($command == 'OBJECT_GET_CMDS')
    {
		// command validation
        if (count($cmd) < 2) { die; }
		
		// command parameters
        $imei = strtoupper($cmd[1]);
		
		$q = "SELECT * FROM `gs_object_cmd_exec` WHERE `user_id`='".$user_id."' AND `imei`='".$imei."' AND `status`='0'";
		$r = mysqli_query($ms, $q);
		
		$result = array();
		
		while($row = mysqli_fetch_array($r))
		{
			$result[] = array($row['cmd_id'], $row['type'], $row['cmd']);
			
			$q2 = "UPDATE `gs_object_cmd_exec` SET `status`='1' WHERE `cmd_id`='".$row["cmd_id"]."'";
			$r2 = mysqli_query($ms, $q2);
		}
		
		header('Content-type: application/json');
        echo json_encode($result); 
	}
	
	if ($command == 'OBJECT_CMD_GPRS')
	{
		// command validation
		if (count($cmd) < 5) { die; }
		
		// command parameters
        $imei = strtoupper($cmd[1]);
		$name = $cmd[2];
		$type = $cmd[3];
		$cmd = $cmd[4];
		
		$q = "SELECT * FROM `gs_user_objects` WHERE `user_id`='".$user_id."' AND `imei`='".$imei."'";
		$r = mysqli_query($ms, $q);
		$row = mysqli_fetch_array($r);
		
		if (!$row)
		{
			die();
		}
		
		sendObjectGPRSCommand($user_id, $imei, $name, $type, $cmd);	
	}
	
	if ($command == 'OBJECT_CMD_SMS')
    {
        // command validation
        if (count($cmd) < 4) { die; }
		
		// command parameters
        $imei = strtoupper($cmd[1]);
		$name = $cmd[2];
		$cmd = $cmd[3];
		
		$q = "SELECT * FROM `gs_user_objects` WHERE `user_id`='".$user_id."' AND `imei`='".$imei."'";
		$r = mysqli_query($ms, $q);
		$row = mysqli_fetch_array($r);
		
		if (!$row)
		{
			die();
		}
		
		sendObjectSMSCommand($user_id, $imei, $name, $cmd);
	}
        
    if ($command == 'OBJECT_GET_LOCATIONS')
    {
        // command validation
        if (count($cmd) < 2) { die; }
                
        // command parameters
		if ($cmd[1] == "*")
		{
			$q = "SELECT * FROM `gs_user_objects` WHERE `user_id`='".$user_id ."'";
			$r = mysqli_query($ms, $q);
		}
		else
		{
			$imeis = strtoupper($cmd[1]);
			$imeis = explode(';', $imeis);
			$imeis = implode('","', $imeis);
			$imeis = '"'.$imeis.'"';
			
			$q = "SELECT * FROM `gs_user_objects` WHERE `user_id`='".$user_id ."' AND `imei` IN (".$imeis.")";
			$r = mysqli_query($ms, $q);
		}
		
        $result = array();
                
        while($row = mysqli_fetch_array($r))
		{
            $imei = $row['imei'];
                        
            $q2 = "SELECT * FROM `gs_objects` WHERE `imei`='".$imei."'";
			$r2 = mysqli_query($ms, $q2);
			$row2 = mysqli_fetch_array($r2);
                        
			$result[$imei] = array( 'name' => $row2['name'],
									'dt_server' => $row2['dt_server'],
									'dt_tracker' => $row2['dt_tracker'],
									'lat' => $row2['lat'],
									'lng' => $row2['lng'],
									'altitude' => $row2['altitude'],
									'angle' => $row2['angle'],
									'speed' => $row2['speed'],
									'params' => json_decode($row2['params'],true),
									'loc_valid' => $row2['loc_valid']);        
		}
                
		header('Content-type: application/json');
		echo json_encode($result);
    }
	
	if ($command == 'OBJECT_GET_ROUTE')
        {
		// command validation
        if (count($cmd) < 5) { die; }
                
        // command parameters
        $imei = strtoupper($cmd[1]);
		$dtf = $cmd[2];
		$dtt = $cmd[3];
		$min_stop_duration = $cmd[4];

		loadLanguage('english', 'km,l,c');
		
		$q = "SELECT * FROM `gs_user_objects` WHERE `user_id`='".$user_id."' AND `imei`='".$imei."'";
		$r = mysqli_query($ms, $q);
		$row = mysqli_fetch_array($r);
		
		if (!$row)
		{
			die();
		}
		
		$result = getRoute($user_id, $imei, $dtf, $dtt, $min_stop_duration, true);
		
		$sstops = $result['stops'];
		$stops = array();
		for ($i = 0; $i < count($sstops); ++$i)
		{
			$stops[] = array(	'id_start' => $sstops[$i][0],
								'id_end' => $sstops[$i][1],
								'lat' => $sstops[$i][2],
								'lng' => $sstops[$i][3],
								'altitude' => $sstops[$i][4],
								'angle' => $sstops[$i][5],
								'speed' => 0,
								'dt_start'=> $sstops[$i][6],
								'dt_end' => $sstops[$i][7],
								'duration' => $sstops[$i][8],
								'fuel_consumption' => $sstops[$i][9],
								'fuel_cost' => $sstops[$i][10],
								'engine_idle' => $sstops[$i][11],
								'params' => $sstops[$i][12]);
		}
	
		$sdrives = $result['drives'];
		$drives = array();
		for ($i = 0; $i < count($sdrives); ++$i)
		{
			$drives[] = array(	'id_start_s' => $sdrives[$i][0],
								'id_start' => $sdrives[$i][1],
								'id_end' => $sdrives[$i][2],
								'dt_start_s' => $sdrives[$i][3],
								'dt_start' => $sdrives[$i][4],
								'dt_end' => $sdrives[$i][5],
								'duration' => $sdrives[$i][6],
								'route_length' => $sdrives[$i][7],
								'top_speed' => $sdrives[$i][8],
								'avg_speed'=> $sdrives[$i][9],
								'fuel_consumption' => $sdrives[$i][10],
								'fuel_cost' => $sdrives[$i][11],
								'engine_work' => $sdrives[$i][12],
								'fuel_consumption_per_100km' => $sdrives[$i][13],
								'fuel_consumption_mpg' => $sdrives[$i][14]);
		}
		
		$sevents = $result['events'];
		$events = array();
		for ($i = 0; $i < count($sevents); ++$i)
		{
			$events[] = array(	'event_desc' => $sevents[$i][0],
								'dt_tracker' => $sevents[$i][1],
								'lat' => $sevents[$i][2],
								'lng' => $sevents[$i][3],
								'altitude' => $sevents[$i][4],
								'angle' => $sevents[$i][5],
								'speed' => $sevents[$i][6],
								'params' => $sevents[$i][7]);
		}
		
		$result['stops'] = $stops;
		$result['drives'] = $drives;
		$result['events'] = $events;
		
		header('Content-type: application/json');
        echo json_encode($result); 
	}
	
    if ($command == 'OBJECT_GET_MESSAGES')
    {
        // command validation
        if (count($cmd) < 4) { die; }
                
        // command parameters
        $imei = strtoupper($cmd[1]);
		$dtf = $cmd[2];
		$dtt = $cmd[3];
		
		$q = "SELECT * FROM `gs_user_objects` WHERE `user_id`='".$user_id."' AND `imei`='".$imei."'";
		$r = mysqli_query($ms, $q);
		$row = mysqli_fetch_array($r);
		
		if (!$row)
		{
			die();
		}
		
		$result = array();
		
		$q = "SELECT DISTINCT	dt_tracker,
					lat,
					lng,
					altitude,
					angle,
					speed,
					params
					FROM `gs_object_data_".$imei."` WHERE dt_tracker BETWEEN '".$dtf."' AND '".$dtt."' ORDER BY dt_tracker ASC";
					
		$r = mysqli_query($ms, $q);
		
		while($route_data=mysqli_fetch_array($r))
		{
			$route_data['params'] = json_decode($route_data['params'],true);
			
			$result[] = array(	$route_data['dt_tracker'],
			$route_data['lat'],
			$route_data['lng'],
			$route_data['altitude'],
			$route_data['angle'],
			$route_data['speed'],
			$route_data['params']);
		}
		
		header('Content-type: application/json');
        echo json_encode($result); 
	}
	
	if ($command == 'OBJECT_GET_EVENTS')
    {
		// command validation
		if (count($cmd) < 4) { die; }
		
		// command parameters
		$imei = strtoupper($cmd[1]);
		$dtf = $cmd[2];
		$dtt = $cmd[3];
		
		$q = "SELECT * FROM `gs_user_objects` WHERE `user_id`='".$user_id."' AND `imei`='".$imei."'";
		$r = mysqli_query($ms, $q);
		$row = mysqli_fetch_array($r);
		
		if (!$row)
		{
			die();
		}
		
		$result = array();
		
		$q = "SELECT * FROM `gs_user_events_data` WHERE `user_id`='".$user_id."' AND `imei`='".$imei."' AND dt_tracker BETWEEN '".$dtf."' AND '".$dtt."' ORDER BY dt_tracker ASC";
		$r = mysqli_query($ms, $q);
		
		while($event_data=mysqli_fetch_array($r))
		{
			$event_data['params'] = json_decode($event_data['params'],true);
			
			$result[] = array(	$event_data['type'],
								$event_data['event_desc'],
								$event_data['imei'],
								$event_data['name'],
								$event_data['dt_tracker'],
								$event_data['lat'],
								$event_data['lng'],
								$event_data['altitude'],
								$event_data['angle'],
								$event_data['speed'],
								$event_data['params']);
		}
		
		header('Content-type: application/json');
        echo json_encode($result); 
	}
	
	if ($command == 'OBJECT_GET_LAST_EVENTS')
    {
		// command validation
        if (count($cmd) < 1) { die; }
		
		$result = array();
		
		$q = "SELECT * FROM `gs_user_last_events_data` WHERE `user_id`='".$user_id."' AND dt_server > DATE_SUB(UTC_DATE(), INTERVAL 1 DAY) ORDER BY dt_tracker ASC";
		$r = mysqli_query($ms, $q);
		
		while($event_data=mysqli_fetch_array($r))
		{
			$event_data['params'] = json_decode($event_data['params'],true);
			
			$result[] = array(	$event_data['type'],
								$event_data['event_desc'],
								$event_data['imei'],
								$event_data['name'],
								$event_data['dt_tracker'],
								$event_data['lat'],
								$event_data['lng'],
								$event_data['altitude'],
								$event_data['angle'],
								$event_data['speed'],
								$event_data['params']);
		}
		
		header('Content-type: application/json');
        echo json_encode($result); 
	}
	
	if ($command == 'GET_ADDRESS')
    {
        // command validation
        if (count($cmd) < 3) { die; }
                
        // command parameters
        $lat = $cmd[1];
		$lng = $cmd[2];
		
		$result = '';
		
		if (($lat <> '') && ($lng <> ''))
		{
			$result = geocoderGetAddress($lat, $lng);	
		}
		
		header('Content-Type: text/html; charset=utf-8');
        echo $result; 
	}
?>
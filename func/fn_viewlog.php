<?
        session_start();
        include ('../init.php');
        include ('fn_common.php');
        checkUserSession();
        checkUserCPanelPrivileges();
        header('Content-Type:text/plain');
		
        if ($_GET['log'] == "tracker-server.log"){
			$filename = basename(realpath('C:/Program Files/Traccar/logs/'.$_GET['log']));  
			$path = 'C:/Program Files/Traccar/logs/'.$filename;
					
			if (file_exists($path))
			{
					$ext = pathinfo($path, PATHINFO_EXTENSION);
					
					if ($ext == 'log')
					{
							echo file_get_contents($path);
					}
			}

		}else{
			$filename = basename(realpath($gsValues['PATH_ROOT'].'logs/'.$_GET['log']));  
			$path = $gsValues['PATH_ROOT'].'logs/'.$filename;
					
			if (file_exists($path))
			{
					$ext = pathinfo($path, PATHINFO_EXTENSION);
					
					if ($ext == 'log')
					{
							echo file_get_contents($path);
					}
			} 
		}
?>
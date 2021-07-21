<?php



    $Auth = "FDVjPjleqkZ7kaKfhEqbrh635EyXIworKwkOjQ4Gobg=";
    $ServiceURL = "http://panelrey.iplace.net/WSACCESS/Transportista.svc/posicion";
    $Method = "POST";

    function setPosicion($PosicionRequest) {
        global $ServiceURL;
        global $Method;
        global $Auth;

        try {
            $Headers = array(
                "Content-Type: application/json",
                "Authorization: {$Auth}"
            );

            $result = CallAPI($Method, $ServiceURL, $Headers, $PosicionRequest);

            print_r($result);
        }
        catch (RestFault $e) {
            echo $e->getMessage();
        }
    }

    function CallAPI($method, $url, $headers, $data = false) {
        $curl = curl_init($url);

        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }
        
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0); 
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }
	
	function castDateSQL_UNIX_PR($date){
		return strtotime($date);
	}

	function envioPanelRey($loc,$economico,$placa){
		$file = fopen("log_PanelRey.txt", "a");
		$Posicion = json_encode(array(
			"fecha" => date("Ymd H:i:s", castDateSQL_UNIX_PR($loc["dt_tracker"])),//"20210413 17:30:00", // Format: YYYYMMDD HH:mm:SS
			"tracto" => $economico, // Numero Economico
			"latitud" => $loc["lat"], // Decimal(11,8) Ejemplos: 26.234567, -45.9876523 
			"longitud" => $loc["lng"], // Decimal(11,8) Ejemplos: 13.7826351, -101.783625
			"velocidad" => $loc["speed"], // Integer
			"direccion" => $loc["angle"], // Entero expresando grados (0-359)
			"evento" => null, // Si se informa un evento este campo deberá contener el código entero del evento 
			"valorEvento" => null // Solo se proporciona en ciertos eventos
		));

    setPosicion($Posicion);
	}
?>
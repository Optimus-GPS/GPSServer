<?
        function sendWebhookQueue($webhook_url)
        {
                global $ms;
		
		$q = "INSERT INTO `gs_webhook_queue` 	(`dt_webhook`,
							`webhook_url`)
							VALUES
							('".gmdate("Y-m-d H:i:s")."',
							'".$webhook_url."')";
		$r = mysqli_query($ms, $q);
                
                if ($r)
                {
                        return true;
                }
                else
                {
                        return false;
                }
        }
        
        function sendWebhook($webhook_url)
        {
                global $ms;
                
                if ($webhook_url != '')
                {
                        $context = stream_context_create(array(
                                        'http' => array(
                                            'timeout' => 3
                                        )
                                ));
                        
                        $result = @file_get_contents($webhook_url, false, $context);
                                
                        return true;
                }
                else
                {
                        return false;
                }
        }
?>
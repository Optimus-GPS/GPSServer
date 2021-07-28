<?
        // ############################################################
        // All listed setting can be changed only by editing this file
        // Other settings can be changed from CPanel/Manage server
        // ############################################################
        
        $gsValues['VERSION_ID'] = 3160;
        $gsValues['VERSION'] = '3.16';
        
        $gsValues['HTTP_MODE'] = 'http'; // options: http/https
        
        // lock admin to IP addresses, example $gsValues['ADMIN_IP'] = '127.0.0.1,222.222.222.222,333.333.333.333';
        $gsValues['ADMIN_IP'] = '';
        
        // log out admin user if IP changes during active session, provides additional security from session stealing
        $gsValues['ADMIN_IP_SESSION_CHECK'] = false; // options: false/true
        
        $gsValues['SERVER_IP'] = '${GS_SERVER_IP}'; // used only as information in CPanel
        
        // multi server login
        $gsValues['MULTI_SERVER_LOGIN'] = false; // options: false/true
        $gsValues['MULTI_SERVER_LIST'] = array('' => '');

        $gsValues['OBJECT_LIMIT'] = 0; // options: 0 means no limit, number sets limit
        $gsValues['LOCATION_FILTER'] = true; // options: false/true
        $gsValues['CURL'] = false; // options: false/true
        
        // path to root of web application
        // if application is installed not in root folder of web server, then folder name must be added, for example we install it in track folder: $_SERVER['DOCUMENT_ROOT'].'/track';
        // very often web servers have no $_SERVER['DOCUMENT_ROOT'] set at all, then direct path should be used, for example c:/wamp/www or any other leading to www or public_html folder
        $gsValues['PATH_ROOT'] = $_SERVER['DOCUMENT_ROOT'];
        // url to root of web application, example: $gsValues['URL_ROOT'] = 'YOUR_DOMAIN/track';
        $gsValues['URL_ROOT'] = 'http://localhost/';
                
        // hardware key, should be same as in GPS-Server.exe
        $gsValues['HW_KEY'] = '0000';
        
        // connection to MySQL database
        $gsValues['DB_HOSTNAME'] = '35.163.63.113'; // database host
        $gsValues['DB_PORT']     = '3306'; // database port
        $gsValues['DB_NAME']	 = 'gpsdb'; // database name
        $gsValues['DB_USERNAME'] = 'dbadmin'; // database user name
        $gsValues['DB_PASSWORD'] = 'W6M$>6hB'; // database password
?>

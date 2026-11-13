<?php

/*********************************************/
//1. Prepairing to start
//  Allow url fopen in php config (php.ini)
//      ; Whether to allow fopen to open URLs (like http:// or ftp://) as files.
//      ; http://php.net/allow-url-fopen
//      allow_url_fopen=On 
//  Or allow curl in php config (php.ini)
//      extension=curl

//  Allow creating new 'systems' folder and saving new file in index.php folder (when config XY_SYSTEMFILES_SYN = 1)

/*********************************************/
//2. Configuration. Change to fit your requirements.
require_once("config.php");

/*********************************************/
//3. Check custom start function
//  xy_start function is defined in index.php
//
//  Sample index.php file:
//      <?php
//      function xy_start() {
//          //Do yours
//      }
//
//      require_once("xy.php");
//      
if (!function_exists('xy_start'))
{
    die('Function xy_start does not exists. Please create this function index.php before include/require "xy.php" file.');
}

if (!function_exists('json_validate')) {   
    function json_validate(string $string): bool {
        json_decode($string);

        return json_last_error() === JSON_ERROR_NONE;
    }
}

/*********************************************/
//4. Download code for XY client
//  Find protocol (http or https)
$protocol = "";
$domain = $_SERVER['SERVER_NAME'];

if (!((isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || 
$_SERVER['HTTPS'] == 1)) ||  
(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&   
$_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')))
{
    $protocol = "http";
}
else 
{
    $protocol = "https";
}

if (empty($protocol))
{
    die("Could not find protocol!");
}

//  Fetch xy start code from the system.
$port = strtolower($protocol) == "https" ? XY_SYSTEM_HTTPS_PORT : XY_SYSTEM_HTTP_PORT;

//  Build the system uri. If client runs as https, call system as https.
define("XY_SYSTEM_URI", $protocol."://".XY_SYSTEM_DOMAIN.(empty($port) ? "" : ":".$port)."/".XY_SYSTEM_PATH);

//  Check allow open file from url.
$allow_url_fopen = ini_get('allow_url_fopen');

$system_dir = __DIR__.DIRECTORY_SEPARATOR."systems";

//Catch all warnings, errors,...
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    // error was suppressed with the @-operator
    if (0 === error_reporting()) {
        return false;
    }
    
    throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
});

if (!file_exists($system_dir) || !is_dir($system_dir))
{
    if (!mkdir($system_dir, 0777, true)) {
        die('Failed to create systems directory. Please allow permission to create directory or manually create "systems" directory and allow writing new file to the "systems" directory.');
    }
}

$xy_config_file = $system_dir."/xy.config.php";
$xy_start_file = $system_dir."/xy.start.php";
$xy_code_checksum = "";

$fileContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
);  

if (file_exists($xy_config_file))
{
    require_once($xy_config_file);
}

if (file_exists($xy_start_file))
{
    $xy_code_checksum = md5(file_get_contents($xy_start_file, false, stream_context_create($fileContextOptions)));
}

$client_xy_code_version = defined("XY_CODE_VERSION") ? floatval(XY_CODE_VERSION) : 0;

if (!file_exists($xy_start_file) || (defined("XY_SYSTEMFILES_SYN") && XY_SYSTEMFILES_SYN == 1))
{
    //  Get the client code from the system
    //  If php.ini support opening file from url, use this feature
    try {
        if ($allow_url_fopen)
        {
            $remote_system = file_get_contents(XY_SYSTEM_URI."xy_start/?checksum=".$xy_code_checksum."&client_xy_code_version=".$client_xy_code_version, false, stream_context_create($fileContextOptions));

            $remote_json = json_decode('{ "sourcecode" : "", "xy_code_version": 0, "force_sync_xy_code": false }');
            if (json_validate($remote_system))
            {
                $remote_json = json_decode($remote_system, true);

                $remote_system = $remote_json['sourcecode'];
            }
            
            if ($remote_system != '/*NO_CHANGE*/') {
                try {
                    if(!file_exists($xy_start_file) || @is_writable($xy_start_file)) {
                        file_put_contents($xy_start_file, $remote_system);

                        $server_xy_code_version = isset($remote_json['xy_code_version']) ? $remote_json['xy_code_version'] : 0;
                        $config_str = "<?php \n\r define(\"XY_CODE_VERSION\", ".$server_xy_code_version."); \n\r ?>";

                        file_put_contents($xy_config_file, $config_str);
                    }
                    else {
                        if (isset($remote_json['force_sync_xy_code']) && $remote_json['force_sync_xy_code'])
                        {
                            xy_error_display('Please allow modify the "systems" folder for IUSR/IIS_IUSRS (for php running on Windows IIS) or php user (1)!'); 
                        }
                        else {
                            if (file_exists($xy_start_file) && defined("XY_SYSTEMFILES_SYN_IGNORE_ERROR") && XY_SYSTEMFILES_SYN_IGNORE_ERROR == 1)
                            {
                                //Do nothings. Keep existed files
                            }
                            else {
                                xy_error_display('Please allow modify the "systems" folder for IUSR/IIS_IUSRS (for php running on Windows IIS) or php user (2)!');
                            }
                        }
                    }                    
                }
                catch (\Throwable $e_role) {
                    if (file_exists($xy_start_file) && defined("XY_SYSTEMFILES_SYN_IGNORE_ERROR") && XY_SYSTEMFILES_SYN_IGNORE_ERROR == 1)
                    {
                        //Do nothings. Keep existed files
                    }
                    else {
                        xy_error_display('Please allow modify the "systems" folder for IUSR/IIS_IUSRS (for php running on Windows IIS) or php user (3)!', $e_role);
                    }
                }
            }
        }
        //  Otherwise, use curl
        else {
            //2. Include functions from the master system
            $curlSession = curl_init();
            curl_setopt($curlSession, CURLOPT_URL, XY_SYSTEM_URI."xy_start/?checksum=".$xy_code_checksum."&client_xy_code_version=".$client_xy_code_version);
            curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curlSession, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curlSession, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($curlSession, CURLOPT_FOLLOWLOCATION, true);
		    curl_setopt($curlSession, CURLOPT_POSTREDIR, 3);

            $remote_system = curl_exec($curlSession);
            unset($curlSession);

            $remote_json = json_decode('{ "sourcecode" : "", "xy_code_version": 0, "force_sync_xy_code": false }');
            if (json_validate($remote_system))
            {
                $remote_json = json_decode($remote_system, true);

                $remote_system = $remote_json['sourcecode'];
            }

            if ($remote_system != '/*NO_CHANGE*/') {
                try {
                    if(!file_exists($xy_start_file) || @is_writable($xy_start_file)) {
                        file_put_contents($xy_start_file, $remote_system);

                        $server_xy_code_version = isset($remote_json['xy_code_version']) ? $remote_json['xy_code_version'] : 0;
                        $config_str = "<?php \n\r define(\"XY_CODE_VERSION\", ".$server_xy_code_version."); \n\r ?>";

                        file_put_contents($xy_config_file, $config_str);
                    }
                    else {
                        if (isset($remote_json['force_sync_xy_code']) && $remote_json['force_sync_xy_code'])
                        {
                            xy_error_display('Please allow modify the "systems" folder for IUSR/IIS_IUSRS (for php running on Windows IIS) or php user (1)!');
                        }
                        else {
                            if (file_exists($xy_start_file) && defined("XY_SYSTEMFILES_SYN_IGNORE_ERROR") && XY_SYSTEMFILES_SYN_IGNORE_ERROR == 1)
                            {
                                //Do nothings. Keep existed files
                            }
                            else {
                                xy_error_display('Please allow modify the "systems" folder for IUSR/IIS_IUSRS (for php running on Windows IIS) or php user (2)!');
                            }
                        }
                    }                
                }
                catch (\Throwable $e_role) {
                    if (file_exists($xy_start_file) && defined("XY_SYSTEMFILES_SYN_IGNORE_ERROR") && XY_SYSTEMFILES_SYN_IGNORE_ERROR == 1)
                    {
                        //Do nothings. Keep existed files
                    }
                    else {
                        xy_error_display('Please allow modify the "systems" folder for IUSR/IIS_IUSRS (for php running on Windows IIS) or php user (3)!', $e_role);
                    }
                }
            }
        }
    }
    catch(\Throwable $e) {
        echo 'Could not connect to get system code. Please config XY_SYSTEM_URI correcttly!';

        if (defined('XY_DEBUG') && XY_DEBUG == 1)
        {
            if ($e != null) {
                echo "<br/>".$e->getMessage();
                echo "<br/>".$e->getTraceAsString();
            }
        }

        restore_error_handler();

        exit();
    } 
}

/*********************************************/
//5. Include start code
if (file_exists($xy_start_file))
{
    require_once($xy_start_file);
}

?>
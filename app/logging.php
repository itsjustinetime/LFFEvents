<?php

function writeLog() {
	
$year = date('Y');
$month = date('M');

 if (!file_exists('logs')) {
            mkdir('logs', 0755);			
 }
 
$logFile = $year."_".$month.'.log';

$r = array();

// Date & Time
$r['datetime'] = date('Y-m-d H:i:s');

// IP
$r['ip'] = $_SERVER['REMOTE_ADDR'];

// Port
//$r['port'] = $_SERVER["REMOTE_PORT"];

// URI
$r['uri'] = $_SERVER['REQUEST_URI'];

// Browser
$r['agent'] = isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : "";
$r['lffID'] = isset($_COOKIE['lffID']) ? $_COOKIE['lffID'] : "";
// Referer
$r['referer'] = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "";

// Log Report
$log = "";
$log = $r['datetime']."|".$r['ip']."|".$r['lffID']."\n";

// Log File
file_put_contents('logs/'.$logFile, $log, FILE_APPEND);

}

?>
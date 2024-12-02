<?php
include_once 'configuration.php';
//include_once '../functions/functions.php';
//start_session();
$timeDate = date("Y-m-d H:i:s");

$error=''; // Variable To Store Error Message
if (isset($_POST['submit'])) {
								if (empty($_POST['passcode'])){
																$error = "Passcode is not valid. Sorry.";
															  }
								else { $passcode=strtoupper($_POST['passcode']); }
}

if ($passcodeEnable) {
	
		$passcode = stripslashes($passcode);
		$passdata=[];
		if (!empty(glob($PATH_CONTENT . 'lff-events/passcodes/*.json'))) {
			foreach (glob($PATH_CONTENT . 'lff-events/passcodes/*.json') as $key => $file) {
				$data = json_decode(file_get_contents($file));
				$passcodevalue=$data->passcodevalue;
				$passcodeexpires=str_replace("T"," ", $data->passcodeexpires);
				if ($passcodevalue == $passcode && $passcodeexpires > $timeDate) { // passcode is good
					setcookie("lffkey", $passcode, time()+ $maxCookieAge); // set the user's cookie so they stay logged in
					setcookie("lffID",uniqid(), time() + $maxCookieAge); // set a unique ID in a cookie
					header("location: list.php"); // redirect em to the main page	
					break;
				} else
					if ($passcodevalue == $passcode && $passcodeexpires < $timeDate) { // passcode expired
						setcookie("lffkey", "", time() - 3600);
						header("location: expired.php");
					}
				else { // passcode not recognised
						header("location: index.php");
					}
			}
		}

} else { header("location:list.php"); }


?>




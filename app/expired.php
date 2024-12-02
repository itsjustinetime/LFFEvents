<?php

include_once '../configuration.php';

//Get today's date
$timedate = date("Y-m-d");
$pageDate = new DateTimeImmutable($timedate);
// delete the LFFKEY cookie
if (isset($_COOKIE['lffkey'])) {
    unset($_COOKIE['lffkey']);
    setcookie('lffkey', '', time() - 3600, '/'); // empty value and old timestamp
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">
<meta name="description" content="">
<meta name="author" content="">
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />

<?php include_once('favicons.php'); ?>
<title>Leeds First Friday</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
<link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<link href="style.css" rel="stylesheet">
</head>
<body>
	<div class="shadow" id="shadow"></div>
	<div class="headertop" id="heading">
		<img class="applogo" src="images/lff_logo_2023.png" />
		<div class="titletext cursive"></div>
	</div>
<div class="safetypage" id="main">
	<div class="container offerbox">
		<div class="eventdate">
			<div class="venuename" style="text-align: center;">Sorry. Your passcode has expired!</div>
			<div class="venuename" style="text-align: center;">Please Login with the latest passcode</div>
			<div class="infodesc center">New passcodes are announced on LFF's Discord, Facebook, Chix & Shine Group seven days before each Leeds First Friday.</div>
		</div>	
		</div>
<div class="center"><div class="outer logout">
		<div class="container offerbox">
			<div class="vcenter">
				<a class="loginbtn" href="logout.php">login</a>
			</div>
		</div>
		
	</div>
</div>





</div>

<?php $footer = file_get_contents("footer.php");  echo $footer; ?>
</body>
</html>
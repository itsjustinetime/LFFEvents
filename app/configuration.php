<?php

date_default_timezone_set('Europe/London');
// Your name or company name
$company_name = "Leeds First Friday App";
$domainName = "https://www.leedsfirstfriday.com/web/app";
// where this is installed, with trailing slash
// only used in the generation of QR codes for now
$baseURL = $domainName."/web/app/";
$imageBaseDir="../app/images/app_images/";
// full URL to your logo - recommended 840px wide
$logoURL = "images/lfflogo.png";

$PATH_CONTENT='../bl-content/';

// Over hours - how long an event 'day' can overrun by (to allow logins to be made after midnight). Default 4 hours
$over_hours=5;

// If set to 1, events will expire by default
$willexpire=1;

$show_cards=1;

// MaxUserAge - users older than this (in days) will be purged from the database
$maxUserAge=360;

// To enable passcodes, set this to 1
$passcodeEnable=1;

// Max cookie age on user pages
//              S     M    H    D
$maxCookieAge = 60 * 60 * 24 * 365;

// don't edit anything below here
$record = "";
?>

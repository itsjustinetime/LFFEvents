<?php
include_once 'configuration.php';
//error_log( "path:".$PATH_CONTENT);

include_once 'logging.php';
//include_once '../appadmin/adminfuncs.php';
$passcodepath = '';
//$datapath='lffdata.json';

$improveMode=0;
//Get today's date
$timeDate = date("Y-m-d H:i:s");
if ($passcodeEnable==1) {
	if(isset($_COOKIE['lffkey'])){
		$passcode = $_COOKIE['lffkey'];
	} else $passcode = "BOOBOO";
		
		$passcode = stripslashes($passcode);
		$passdata=[];
		if (!empty(glob($PATH_CONTENT . '/lff-events/passcodes/*.json'))) {
        foreach (glob($PATH_CONTENT . '/lff-events/passcodes/*.json') as $key => $file) {
            $data = json_decode(file_get_contents($file));
			$passcodevalue=$data->passcodevalue;
			$passcodeexpires=str_replace("T"," ", $data->passcodeexpires);
			if ($passcodevalue == $passcode && $passcodeexpires > $timeDate) { // passcode is good
				break;
			
			} else
				if ($passcodevalue == $passcode && $passcodeexpires < $timedate) { // passcode expired
					setcookie("lffkey", "", time() - 3600);
					header("location: expired.php");
				}
			else { // passcode not recognised
					header("location: index.php");
			}
		}
		}
 //writeLog();
}

if (isset($_COOKIE['lffID'])) {
	$lffID = $_COOKIE['lffID'];
	$lffID = stripslashes($lffID);
	writeLog();
}  else { setcookie("lffID",uniqid(), time() + $maxCookieAge); }
?>

<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">
<meta name="description" content="">
<meta name="author" content="">
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="max-age=0" />

<?php include_once('favicons.php'); ?>
<title>Leeds First Friday</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
<link href="css/style.css?random=<?php echo uniqid(); ?>" rel="stylesheet">
<link href="css/safetystyle.css" rel="stylesheet">
<script type="text/javascript" src="js/qrcode.min.js"></script>
</head>
<body>
<div id="appbody" class="mainbodyclass">

<div class="nextlff"><?php ?></div>
<div class="headernew" id="heading">
	<img class="headlogo" src="images/blue0-header-lff-logo.webp" id="headlogo"/> 
	<div class="monthtext" id="hashtag"></div>
</div>
<div class="headerdate" id="headerdate"></div>
 <div class="mainsubtitle"><?php echo '';?></div>
 <div id="testdate"></div>
 <div class="nextlff"><span class="nextlffbold" id="lffnow"></span></div>
 <div class="nextlff"><span class="nextlffbold" id="nextlff"><?php if (!$improveMode) echo "Next: "; else echo "Sorry, we are currently doing essential maintenance. Some features may be broken. We apologise for any inconvenience<br>"; ?></span>...</div>
 <div class="pages">
	 <div class="page1 appcontent" id="page1">
		<div class="loading l1">Events will appear here shortly..</div>
	 </div>
	 <div class="page2 appcontent" id="page2">
		<div class="loading l2">Venue highlights will be here soon..</div>
	 </div>
	 <div class="page3 appcontent" id="page3">
		<div class="loading l3" id="placeslist">The venues list is almost here...</div>
		<div class="loading 14" id="serviceslist"></div>
	 </div>
 </div>
</div>
<div class="closeevent" id="closehide">
	<div class="btnclose fa-solid fa-chevron-left" id="closebtn" aria-label="Close"></div>
</div>

<div class="closemap" id="mapclose" style="z-index:99999; position:fixed; left:4vw; top: 15px; color:white; font-size:3vh; display:none;">
	<div class="fa-solid fa-chevron-left" id="closebtn" aria-label="Close Map"></div>
</div>

<?php echo file_get_contents('footer.php'); ?>
</div> <!-- end of #appbody? -->
<div id="fullscreencard"></div>
<div id="fullscreensafety"></div>
<div id="fadeout"></div>
<div class="safetyp" id="safetyp">
	<div class="loading spl"></div>
</div>
<div id="eventtemp" style="display:none;"><?php include_once("templates/event.php"); ?></div>
<div id="highlighttemp" style="display:none;"><?php include_once("templates/highlight.php"); ?></div>
<div id="placestemp" style="display:none;"><?php include_once("templates/venue.php"); ?></div>
<div id="servicestemp" style="display:none;"><?php include_once("templates/services.php"); ?></div>
<?php include_once("templates/categories.php"); ?>
<script>

function lastSunday(month, year) {
  var d = new Date();
  var lastDayOfMonth = new Date(Date.UTC(year || d.getFullYear(), month+1, 0));
  var day = lastDayOfMonth.getDay();
  return new Date(Date.UTC(lastDayOfMonth.getFullYear(), lastDayOfMonth.getMonth(), lastDayOfMonth.getDate() - day));
}

function isBST(date) {
  var d = date || new Date();
  var starts = lastSunday(2, d.getFullYear());
  starts.setHours(1);
  var ends = lastSunday(9, d.getFullYear());
  ends.setHours(1);
  return d.getTime() >= starts.getTime() && d.getTime() < ends.getTime();
}
var faClock='<i class="fa-regular fa-clock"></i> ';
var imagePath='../bl-content/lff-events/images/';
var eventhtml="";
var theDate=new Date();
var hours=theDate.getHours();
var minutes=theDate.getMinutes();
var month=theDate.getMonth()+1;
var year=theDate.getFullYear();
var days=theDate.getDate();
var dateTime=year+"-"+month+"-"+days+" "+hours+":"+minutes+":00";
var lastEvent=0;
var data;
var events;
var offers;
var places;
var services;
var curDate;
var listing;
var safety;
var baseDir='/'+window.location.pathname.split("/");
var testMode=0;
var debugmode=0;
if (!testMode) {  window.setTimeout(updateDate(),2); window.setInterval(updateDate,1000); }

document.body.addEventListener("load", getData());
window.setInterval(async () => { await getData()}, 60*60*1000);
if (!testMode) { window.setInterval(updateEventList,10000); } else { window.setInterval(updateEventList,1000); }
window.setInterval(updateOfferList, 10000);
window.setInterval(updateLFFTimer,1000);

if (testMode) {
	curDate=new Date("2024-12-07 03:59:45").getTime()/1000;
	window.setInterval(function() { curDate=curDate+1; }, 1000);
}

eventTemplate=document.getElementById("eventtemp").innerHTML;
highlightTemplate=document.getElementById("highlighttemp").innerHTML;
placesTemplate=document.getElementById("placestemp").innerHTML;
servicesTemplate=document.getElementById("servicestemp").innerHTML;

async function getFileContents(url) {
  const response = await fetch(url);
  if (!response.ok) throw new Error(response.status);
  const contents = await response.text();
  return contents;
}

getFileContents('templates/safety.php')
  .then(res => {
    safetyTemplate=res;
	document.getElementById('safetyp').innerHTML=safetyTemplate;
	setupSafetyListeners();
  })
  .catch(error => console.error(error));

function updateLFFTimer() {
	if (debugmode) { console.log("updateLFFTimer"); }
	if (!Array.isArray(events) || events.length < 1) { return; }
	if (events.length == 1) { document.getElementById("nextlff").nextSibling.data="";	}
	var i=0;
	for (  i=0; i < events.length; i++) {
		const event=events[i];
		var thisDate=event.eventstart.split("T")[0];
		var thisTime=event.eventstart.split("T")[1];
		var eventStartTime=event.eventstart;
		var eventEndTime=event.eventend;
		var eventShowFrom=event.eventshowfrom;
		var eventShowUntil=event.eventshowuntil;
		var eventStartTime=getSeconds(eventStartTime);
		var eventEndTime=getSeconds(eventEndTime);
		var eventShowFrom=getSeconds(eventShowFrom);
		var eventShowUntil=getSeconds(eventShowUntil);	
		if (curDate < eventStartTime) { break; }
	}
	if (i == events.length) { 
		return; 
	}  // do this because there are no future events in the list
	var nextTime = new Date(events[i].eventstart).getTime()/1000; 
	var timeDiff=Math.floor(nextTime - curDate);
	var timer=getMhd(timeDiff);
}

function updateSafety() {
	document.getElementById('safetyp').innerHTML=safety;
}

function updateHashtag() {
	//return;
	var futureTime=60*60*24*2 + (60*60*12); // 2 days + 12 hours
	var i=0;
	found=[];
	for (i=0; i<listing.length; i++) {
		if (listing[i].eventtitle.includes("LFF")) { 
		found.push([listing[i].eventtitle,listing[i].eventstart]);
		}
	}

	for (i=0; i<found.length; i++) {
		if (getSeconds(found[i][1])+(futureTime) >= curDate) {break;}
	}
	theString=found[i][0].split(":")[0].replace(" LFF","");
	document.getElementById("hashtag").innerHTML=theString;
	document.querySelector('.headerdate').innerText=getTitleDate(found[i][1]);
	titleText();
}

function updateDate() {
	curDate=new Date();
	curDate=curDate.getTime()/1000;	
}

function getDateSecs(){
	var date=new Date();
	var mysecs=Math.floor(date.getTime()/1000);
	return mysecs;
}

function getMhd(seconds) {
	var d = Math.floor(seconds / (3600*24));
	var h = Math.floor(seconds % (3600*24) / 3600);
	var m = Math.floor(seconds % 3600 / 60);
	var s = Math.floor(seconds % 60);
	var dDisplay = d > 0 ? d + "d " : "";
	var hDisplay = h > 0 ? h + "h " : "";
	var mDisplay = m > 0 ? m + "m " : "";
	var sDisplay = s > 0 ? s + "s " : "";
	return dDisplay + hDisplay + mDisplay + sDisplay;
}

function getTitleDate(date) {
	var theDate=new Date(date);
	var days=theDate.getUTCDate();
	var month=theDate.getUTCMonth()+1;
	var year=theDate.getUTCFullYear().toString().substr(2,2);
	if (days < 10) { days="0"+days ;}
	if (month < 10) {month="0"+month; }
	dateTime=days+"."+month+"."+year;
	return dateTime;
}

function getMyDate() {
	var theDate=new Date();
	var hours=theDate.getUTCHours();
	var minutes=theDate.getUTCMinutes();
	var month=theDate.getUTCMonth()+1;
	var year=theDate.getUTCFullYear();
	var days=theDate.getUTCDate();
	var timeDate=year+"-"+month+"-"+days+" "+hours+":"+minutes+":-00";
	if (days < 10) { days="0"+days ;}
	if (month < 10) {month="0"+month; }
	if (hours < 10) {hours="0"+hours; }
	if (minutes < 10) { minutes="0"+minutes; }
	return dateTime;
}

function getSeconds(datetime) {
	seconds=Math.floor(new Date(datetime).getTime()/1000);
	return seconds;
}

function dynamicSort(properties) {
    return function(a, b) {
        for (let i = 0; i < properties.length; i++) {
            let prop = properties[i];
            if (a[prop] < b[prop]) return -1;
            if (a[prop] > b[prop]) return 1;
        }
        return 0;
    }
}
async function getData() {
	var response = await fetch('lffeventdata.json'); //+'?nocache='+new Date().getTime());
	response = await response.text();
    const jsonData = JSON.parse(response);
	events=jsonData.events;
	//events=events.sort(dynamicSort(['eventstart','eventpriority']));
	
	offers=jsonData.highlights;
	places=jsonData.places;
	services=jsonData.services;
	//const sortedData = data.sort(dynamicSort(['name', 'age']));
	places=places.sort(dynamicSort(['venuecategory','venuepriority']));
	
	//let responseE = await fetch('getevents.php');
	//let responseE = await fetch('events.json'+'?nocache='+new Date().getTime());
	//responseE = await responseE.text();
	//listing = JSON.parse(responseE);
	listing=events;
	updateEvents();
	updateHighlights();
	updatePlaces();
	updateServices();
	updateEventList();
	updateHashtag();
	addMapListener();
}

function replaceMe(template, data) {
	template=template.replaceAll('imgsrc','src');
  const pattern = /{\s*(\w+?)\s*}/g; // {property}
  return template.replace(pattern, (_, token) => data[token] || '');
}

function isDayTime(inputDate) {
	var date=new Date(inputDate);
	var hour=parseInt(date.getUTCHours());
	if (isBST(date)) {hour=hour+1;}
	if (hour < 18) {return true;} else return false;
	
}

function niceTime(inputDate){
	var date=new Date(inputDate);
	var hour=parseInt(date.getUTCHours());
	if (isBST(date)) {hour=hour+1;}
	var minutes=parseInt(date.getUTCMinutes());
	var myTime="";
	var meridian="am";
    if (hour == 12) {meridian="pm";}
	if (hour > 12) {meridian="pm"; hour=hour-12;}
	if (minutes > 0) {myTime=hour+":"+minutes+meridian; } else {myTime=hour+meridian; }
	return myTime;
}

function niceDate(inputDate,lm, ld) {
	var months;
	var days;
	if (lm) { months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]; }
	else { months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"]; }
	if (ld) { days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"]; }
	else { days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"]; }
	thisDate=new Date(inputDate);

	var month=months[thisDate.getUTCMonth()];
	var dayname=days[thisDate.getUTCDay()];  
	var day=thisDate.getDate();

	var dateth;
	if (day==1|| day==21 || day==31) {dateth="st";} else	if (day==2) {dateth="nd";} else if (day==3) {dateth="rd";} else dateth="th";
	var dateYear=thisDate.getUTCFullYear();
	var today=new Date();
	var thisYear=today.getUTCFullYear();
	var thisMonth=today.getUTCMonth();
	var thisDay=today.getUTCDate();
    var thisHour=today.getUTCHours();
	var thisMinute=today.getUTCMinutes();
	var dateString;
	if (isBST(today)) { thisHour=thisHour+1; }
	if (thisYear == dateYear) { dateString=dayname+" "+day+dateth+" "+month; }
	if (thisYear != dateYear) { dateString=dayname+" "+day+dateth+" "+month+" "+dateYear; }
	if (thisYear == dateYear && thisMonth == month && thisDay == day) {dateString="Today"; }
	if (thisYear == dateYear && thisMonth == month && thisDay == (day+1)) {dateString="Tomorrow x"+niceTime(thisDate); }
	return dateString;	
}

function niceDateTime(inputDateTime) {
	var dateString;
	var input=new Date(inputDateTime);
	var secs=Math.floor(input.getTime()/1000);
	var hours=input.getUTCHours();
	
	if (isBST(input)) { hours=hours+1; }
	var minutes=input.getUTCMinutes();
	var meridien="am";
	if (hours == 12) {meridien="pm";}
	if (hours > 12) {hours=(hours-12); meridien="pm";}
	if (minutes <10) {minutes="0"+minutes;}
	var hourString=hours+":"+minutes+meridien;
	
	hourString=hourString.replace(":00", "");
	dateString=niceDate(input)+" "+hourString;
	
	//var curDate=new Date();
	var timenow =new Date(curDate*1000);
	var nowSecs=Math.round(timenow.getTime()/1000);
	var timeDiff = secs-nowSecs;
	if ((timeDiff < 60*60*36) && timeDiff > 60*60*23) {dateString="Tomorrow "+hourString;}
	var daysDiff=Math.floor(timeDiff/60/60/24);  // secs in day = 60sec * 60 min * 24h
	var hoursDiff=(timeDiff/60/60); // hours in day = 60sec * 60min
	var minsDiff=Math.round(timeDiff/60); 
	if (hoursDiff <6) {
		var d = Math.floor(timeDiff / (3600*24));
		var h = Math.floor(timeDiff % (3600*24) / 3600);
		var m = Math.floor(timeDiff % 3600 / 60);
		var s = Math.floor(timeDiff % 60);
		var dDisplay = d > 0 ? d + (d == 1 ? " day, " : " days, ") : "";
		var hDisplay = h > 0 ? h + (h == 1 ? " hour " : " hours ") : "";
		var mDisplay = m > 0 ? m + (m == 1 ? " minute " : " minutes ") : "";
		if (m ==0) { dateString=''; } else 	dateString=' in '+dDisplay+hDisplay+mDisplay;
	}
	dateString=dateString.replace("in 0 minutes"," NOW");
	return dateString;	
}

function removeNow() {  
	var i=0;
	for (  i=0; i < events.length; i++) {
			var eventId=events[i].eventid;
			var eventPlace=events[i]['eventvenue'].replace(" ", "_");
			var htmlId=eventId+eventPlace;
			var eventStartTimeString=events[i]['eventstart'];
			var eventEndTimeString=events[i]['eventend'];
			const element=document.getElementById("start"+htmlId);
			if (element) {
			document.getElementById("start"+htmlId).firstElementChild.innerHTML=faClock+niceTime(eventStartTimeString)+' - '+niceTime(eventEndTimeString);
			document.getElementById("start"+htmlId).classList.remove('now_on');
			document.getElementById("start"+htmlId).firstElementChild.classList.remove('now_on');
			//document.getElementById("now"+htmlId).style.display="none";
			}
		}
}

function updateNow() {
	removeNow();
	var i=0;
	var myDate=new Date(curDate*1000);
	var month=(myDate.getMonth()); month++;
	if (month < 10) {month="0"+month;}
	var day=myDate.getDate();
	if (day < 10) {day="0"+day;}
	var hour=myDate.getHours();
	if (hour <10) {hour="0"+hour;}
	var minutes=myDate.getMinutes();
	if (minutes<10) {minutes="0"+minutes;}
	var seconds=myDate.getSeconds();
	if (seconds<10) {seconds="0"+seconds;}
	if (testMode) { document.getElementById("testdate").innerHTML=month+"-"+day+" "+hour+":"+minutes+":"+seconds; }
	for (  i=0; i < events.length; i++) {
		var thisDate=events[i].eventstart.split("T")[0];
		var thisTime=events[i].eventstart.split("T")[1];
		var eventStartTime=events[i].eventstart;
		var eventStartTimeString=events[i].eventstart;
		var eventEndTimeString=events[i].eventend;
		//var eventShowFrom=events[i].eventshowfrom;
		//var eventShowUntil=events[i].eventshowuntil;
		var eventStartTime=getSeconds(eventStartTime);
		var eventEndTime=getSeconds(eventEndTimeString);
		//var eventShowFrom=getSeconds(eventShowFrom);
		//var eventShowUntil=getSeconds(eventShowUntil);
		var eventId=events[i].eventid;
		var eventPlace=events[i].eventvenue.replace(" ", "_");
		var htmlId=eventId+eventPlace;
		//if (curDate > eventStartTime + 3600) { break; }
		if (curDate < eventStartTime) {
			var eventId=events[i].eventid;
			var timeDiff = eventStartTime - curDate;
			if ((timeDiff / 60) <50) {   
										var theMins = Math.round(timeDiff/60);
										//document.getElementById("start"+htmlId).firstElementChild.innerHTML='in '+theMins+" mins";
									}
				var mins = timeDiff/60;
				if (mins < 60) {
					var hr=Math.floor(mins/60);
					var min=mins-(hr*60);
					var string="in "+hr+"hr "+Math.round(min)+" min";
					if (hr>1) {string=string.replace("hr","hrs"); }
					string=string.replace("0hr","");
					string=string.replace("hrs 0min","hrs");
					if (min >1) {string=string+"s";}
					string=string+" - "+niceTime(eventEndTimeString);
					//document.getElementById("start"+htmlId).firstElementChild.innerHTML=string;
				}
		}
		
		if ((curDate >= eventStartTime) && (curDate < eventEndTime)) {
			document.getElementById("start"+htmlId).firstElementChild.innerHTML=faClock+'NOW - '+niceTime(eventEndTimeString);
			document.getElementById("start"+htmlId).firstElementChild.classList.add('now_on');
		}
	}
}

function updateUpcoming() {
if (debugmode) { console.log("updateUpcoming"); }
	updateNow();
	var i=0;
	if (events.length < 1) {
		document.getElementById("nextlff").innerHTML=""; document.getElementById("nextlff").nextSibling.data="";
		return;
	}
	for (  i=0; i < events.length; i++) {
		const event=events[i];
		var thisDate=event.eventstart.split("T")[0];
		var thisTime=event.eventstart.split("T")[1];
		var eventStartTime=event.eventstart;
		var eventEndTime=event.eventend;
		var eventStartTime=getSeconds(eventStartTime);
		var eventEndTime=getSeconds(eventEndTime);
		if (curDate < eventStartTime) { break; }
	}
	// no more events in the list, oh noes!
	if (i == events.length) { 
	 document.getElementById("nextlff").innerHTML=""; document.getElementById("nextlff").nextSibling.data="";
	 return;
	}
	var eventId=events[i]['eventid'];
	var eventPlace=events[i].eventvenue.replace(" ", "_");
	var eventtitle = events[i].eventtitle.replace(/LFF\ .*/,"LFF");
	var venuename = events[i].eventvenue;
	var eventDate = niceDateTime(events[i].eventstart); 
	if (curDate < eventStartTime) {
		document.getElementById("nextlff").nextSibling.data=eventtitle+" at "+venuename+" "+eventDate;
}  else { document.getElementById("nextlff").innerHTML=""; document.getElementById("nextlff").nextSibling.data=""; }
}

function updateEventList() {
    
    if (!Array.isArray(events) || events.length < 1) { return; }

	for ( var i=0; i < events.length; i++) {
		const event=events[i];
		var eventStartTime=event.eventstart;
		var eventEndTime=event.eventend;

		var eventId=event.eventid;
		//var eventPlace;
		//if (event.eventvenue) { eventPlace=event.eventvenue.replace(" ", "_"); } else eventPlace=" ";
		var eventID=eventId+event.eventvenue.replace(" ","_");
	
		eventStartTime=getSeconds(eventStartTime);
		eventEndTime=getSeconds(eventEndTime);
		if (!document.getElementById(eventID))  { continue; }
		if (curDate > eventEndTime) {
			removeItem(eventID);
			newevents = events.splice(i,1);
		} 
	}
	updateUpcoming();
}

function updateOfferList() {
	if (debugmode) { console.log("updateOfferList"); }
	if (!Array.isArray(offers) || offers.length < 1) { return; }
	for ( var i=0; i < offers.length; i++) {
		const offer=offers[i];
		var offerStartDate=offer.highlightstart;
		var offerEndDate=offer.highlightend;
		var offerId=offer.highlightid;
		var offerPlace=offer.highlightvenue.replace(" ", "_");
		var offerID=offerId+offerPlace+"offer";
		offerStartDate=getSeconds(offerStartDate);
		offerEndDate=getSeconds(offerEndDate);
		if (!document.getElementById(offerID))  {continue; }
		if (curDate > offerEndDate) {
			removeOffer(offerID);
			neweoffers = offers.splice(i,1);
		}
	}
}

function removeOffer(item) {
	var removeEl = document.getElementById(item).parentElement;
	var heightPx=removeEl.scrollHeight;
	removeEl.style.transform="translateX(105%)";
	removeEl.style.maxHeight=heightPx+"px";
	setTimeout(function() {removeEl.style.maxHeight="0px";},200);
	setTimeout(function() {removeEl.remove(); },300);	
}

function removeItem(item) {
	var parentEl = document.getElementById(item).parentElement;
	
	if (document.getElementById(item).parentElement.childElementCount < 3) {
		item=document.getElementById(item).parentElement.id;
		document.getElementById(item).nextElementSibling.remove();
	}
	var heightPx=document.getElementById(item).scrollHeight;
	document.getElementById(item).style.transform="translateX(105%)";
	document.getElementById(item).style.maxHeight=heightPx+"px";
	setTimeout(function() {document.getElementById(item).style.maxHeight="0px";},200);
	setTimeout(function() {document.getElementById(item).remove(); },300);
	if (parentEl.childElementCount < 2) setTimeout(function() {parentEl.remove(); },500);
}

function isFirstFriday(date) {
	lffDate=new Date(date);
	var thisDay = date.split("-")[2];
	if (lffDate.getUTCDay() === 5 && Math.ceil(thisDay/7)){
        return true
    } else return false;
}

const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

function updateEvents() {
	if (debugmode) { console.log("updateEvents"); }
	if (!Array.isArray(events) || events.length < 1) {  document.getElementById("page1").innerHTML="<div class='pagedesc'>Oh dear!  No events could be loaded :'(</div>"; return;} 
	
	if (events[0].eventtitle=="NODATA") { document.getElementById("page1").innerHTML="<div class='pagedesc'>Error!  No events could be loaded :'(</div>"; return;}
	
	var page1HTML='<div class="pagedesc">Events</div>';
	var lastDate="0";
	for ( var i=0; i < events.length; i++) {
		const event=events[i];
		var curTemplate=eventTemplate;
		event.eventstart=event.eventstart.replace("T"," ");
		var thisDate=event.eventstart.split(" ")[0];
		var thisTime=event.eventstart.split(" ")[1];
		var thisMonth = new Date (thisDate).getMonth();
		var eventStartTime=event.eventstart;
		var eventEndTime=event.eventend;
		
		eventStartTime=getSeconds(eventStartTime);
		eventEndTime=getSeconds(eventEndTime);
		
		//if (eventEndTime < curDate ) { continue; }  // FIX THIS!!
		if ((thisDate != lastDate) && lastDate !=0) { page1HTML=page1HTML+ '</div><div class="eventdivider"></div>';}
		if ((thisDate != lastDate) && isFirstFriday(thisDate) ) {
			page1HTML=page1HTML+'<div class="eventdate" id="id'+thisDate+'"><div class="eventdatetext">LFF '+months[thisMonth]+" - "+niceDate(thisDate,1,1)+'</div>';
		} else
		if ((thisDate != lastDate) ) { page1HTML=page1HTML+'<div class="eventdate" id="id'+thisDate+'"><div class="eventdatetext">'+niceDate(thisDate,1,1)+'</div>'; }
		
		var pAddress=event.venueaddress;
		var coords=event.venuegps;
	
		if (coords === "NULL" || coords ==="") { coords=pAddress; }
		if (event.eventctalink === null || event.eventctalink ==="") { curTemplate=curTemplate.replace('class="cta"','class="cta hide"'); }
		if (event.eventimage === null ) { event.eventimage="none.webp"; }
		if (event.eventfull == 'on' ) {event.eventdescription=""; }
		
		const data = {
			 venuetag: event.eventid+event.eventvenue.replace(" ", "_"),
			 eventstart: event.eventstart,
			 eventend: event.eventend,
			 eventstarttime: niceTime(event.eventstart),
			 eventendtime: niceTime(event.eventend),
			 eventshowfrom: event.eventshowfrom,
			 eventshowuntil: event.eventshowuntil,
			 eventtitle: event.eventtitle,
			 eventsubtitle: event.eventsubtitle,
			 eventdescription: event.eventdescription,
			 eventvenue: event.eventvenue,
			 eventid: event.eventid,
			 eventrecur: event.eventrecur,
			 eventimage: imagePath+event.eventimage,
			 eventimagesmall: imagePath+event.eventimage.replace(".","_tn."),
			 eventctatext: event.eventctatext,
			 eventctaurl: event.eventctaurl,
			 venueaddress: event.venueaddress,
			 venuecoords: coords
		};
	    if (event.eventfull == 'on' && getSeconds(event.eventstart)-curDate<60*60*24*30) {		
			curTemplate=curTemplate.replace('evfull" style="display:none;"','evfull"'); 
			}
		curTemplate=replaceMe(curTemplate,data);
		//if (isDayTime(event.eventstart)) {   curTemplate=curTemplate.replace('class="eventcontainer eventexpand"','class="eventcontainer eventexpand eventdaytime"'); }
		if (data['eventrecur']=='on' && data['eventdescription']=='') { 
			curTemplate=curTemplate.replace('class="collapse"', 'style="display:none;"');
			//curTemplate=curTemplate.replace('class="tap4more"', 'style="display:none;"');
		}

		lastDate = thisDate;
		page1HTML=page1HTML+curTemplate;
		document.getElementById("page1").innerHTML=page1HTML;
		getEventCollapsables();
	}
}

function updateHighlights() {
	if (debugmode) { console.log("updateHighlights"); }
	if (!Array.isArray(offers) || offers.length < 1) {  document.getElementById("page2").innerHTML="<div class='pagedesc'>Error!  No highlights could be loaded :'(</div>"; return;} 
	if (offers[0].highlighttitle=="NODATA") { console.log(offers[0]); document.getElementById("page2").innerHTML="<div class='pagedesc'>Error!  No highlights could be loaded :'(</div>"; return;}
	var page2HTML='<div class="pagedesc">Venue Highlights</div>';
	for (var i=0; i < offers.length; i++) {
		var curTemplate=highlightTemplate;
		const offer=offers[i];
		var thisDate=offer.highlightstart.split("T")[0];
		var offerEnds=offer.highlightend.split("T")[0];
		var offerEndDateTime=new Date(offerEnds).getTime()/1000;
		if (offerEndDateTime < curDate) { continue; }
		var pAddress=offer.venueaddress;
		var coords=offer.venuegps;
		if (coords === "NULL" || coords==="") { coords=pAddress; }
		const data = {
					 venuetag: offer.highlightid+offer.highlightvenue.replace(" ", "_")+'offer',
					 highlightstartdate: offer.highlightstart,
					 highlightenddate: offer.highlightend,
					 highlighttitle: offer.highlighttitle,
					 highlightsubtitle: offer.highlightsubtitle,
					 highlightdescription: offer.highlightdescription,
					 highlightoffercode: offer.highlightoffercode,
					 highlightvenue: offer.highlightvenue,
					 venueimage: imagePath+offer.venueimage,
					 venueimagesmall: imagePath+offer.venueimage.replace(".","_tn."),
					 venueaddress: offer.venueaddress,
					 venuecoords: coords
		}
		if (offer.highlightoffercode === null || offer.highlightoffercode==="") {curTemplate=curTemplate.replace("fsoffercode","fsoffercode hide");}
		curTemplate=replaceMe(curTemplate,data);
		if (offer.highlightdescription === null || offer.highlightdescription==="" || offer.highlightdescription.length < 2) { curTemplate=curTemplate.replace('<div class="infodesc"></div>','');  }
		page2HTML=page2HTML+curTemplate;
		document.getElementById("page2").innerHTML=page2HTML;
		getOfferCollapsables();
	}
}

function updatePlaces() {
	if (debugmode) { console.log("updatePlaces"); }
	if (!Array.isArray(places) || places.length < 1) {  document.getElementById("page3").innerHTML="<div class='pagedesc'>Error!  No venue data could be loaded :'(</div>"; return;} 
	if (places[0].venuename=="NODATA" ) { document.getElementById('page3').innerHTML="<div class='pagedesc'>Error! No places could be loaded :'(</div>";}
	var page3HTML='<div class="pagedesc">Explore</div><div class="placesouter">';
	var lastType=0;
	var type=0;
	var placeType;
	for (var i=0; i < places.length; i++) {
		var place=places[i];
		var offerString="";
		var curTemplate=placesTemplate;
		placeType= place.venuecategory;
		var placeId=place.venueid;
		// find offer from placeId
		for (var off=0; off < offers.length; off++ ) { 
		// omit venue pass. Because of Fibre QR codes grrr
			if ((offers[off]['highlightvenue'] == place.venuename) && (offers[off]['highlightcategory'] != 'venuepass') ) {    
			offerString='<div class="placeoffer"><div class="fsoffertitle">'+	offers[off]['highlighttitle'] 
				+'</div><div class="fsoffersubtitle">' +	offers[off]['highlightsubtitle'] +'</div>'+'<div class="fsofferdesc">'+
				offers[off]['highlightdesc'] +'</div>';
				if ( offers[off]['highlightinfotext'] ) { 
					offerString=offerString+'<div class="fsoffercode">Code: ' + offers[off]['highlightinfotext'] + '</div>';
				}
				offerString=offerString+"</div>";
			}
		}	
		if (placeType != lastType){

			if (lastType!='') { page3HTML=page3HTML+'<div class="placespacer"></div></div>'; }
			page3HTML=page3HTML+'<div class="placetype">'+placeType+'</div><div class="placesdivider">'; 
		}
		var venuerecShow="none";
		if (place.venuerecommended=="on") { venuerecShow="block";}
		var pAddress=place.venueaddress;
		var coords=place.venuegps;
		if (coords === "NULL" || coords==="") { coords=pAddress; }
		const data = { 
					 venuerecshow: venuerecShow,
					 venuetag: place.venueid+place.venuename.replace(" ", "_")+'explore',
					 venuename: place.venuename,
					 venuedescription: place.venuedescription,
					 venueimage: imagePath+place.venueimage,
					 venueimagesmall: imagePath+place.venueimage.replace(".","_tn."),
					 venueaddress: pAddress,
					 venuecoords: coords,
					 OFFER: offerString,
					 venuefacebook: place.venuefacebook,
					 venueinstagram: place.venueinstagram
				}
		
		curTemplate=replaceMe(curTemplate,data);
		if (place.venuefacebook) { curTemplate = curTemplate.replace('vfb" style="display:none;"','vfb" style="display:block;"'); }
		if (place.venueinstagram) { curTemplate = curTemplate.replace('vig" style="display:none;"','vig" style="display:block;"'); }
		page3HTML=page3HTML+curTemplate;
		document.getElementById("placeslist").innerHTML=page3HTML;
		lastType=placeType;
	}
	getPlaceCollapsables();
}

function updateServices() {
	if (debugmode) { console.log("updateServices"); }
	var servicesHTML='<div class="pagedesc">Services</div><div class="placesouter"><div class="serviceDisclaimer">Please note any bookings for services with any of the below providers are entirely the users&apos; responsibility.</div>';
	var lastType=0;
	var type=0;
	var serviceType;
	for (var i=0; i < services.length; i++) {
		var service=services[i];
		var curTemplate=servicesTemplate;
		serviceType=service.servicecategory;
		var serviceId=service.serviceid;
		if (serviceType != lastType){
			
			if (lastType!=0) { servicesHTML=servicesHTML+'</div>'; }
			servicesHTML=servicesHTML+'<div class="placetype">'+serviceType+'</div><div class="placesdivider"></div>'; 
		}
		var serviceRecc;
		if (service.servicerecommended=="1") {serviceRecc="block";} else serviceRecc="none";
		var sfbicon;
		var sinsticon;
		var smsgricon;
		var swebicon="none";
		var sphoneicon;
		var saddress_show;
		var service_tel;
		if (service.servicefacebook.length > 5) {sfbicon="block"; } else sfbicon="none";
		if (service.serviceinstagram.length > 5) {sinsticon="block"; } else sinsticon="none";
		if (service.servicemessenger.length > 5) {smsgricon="block"; } else smsgricon="none";
		if (service.servicetel) {sphoneicon="block"; } else sphoneicon="none";
		if (service.servicewebsite.length > 5 ) {swebicon="block"; } else swebicon="none";
		if (service.serviceaddress.length > 5 ) {saddress_show="block"; } else saddress_show="none";
		var sAddress=service.serviceaddress;
		var coords=service.servicegps;
		if (coords === null || coords==="") { coords=sAddress; }
		const data = {
					 sfbicon: sfbicon,
					 swebicon: swebicon,
					 sinsticon: sinsticon,
					 smsgricon: smsgricon,
					 sphoneicon: sphoneicon,
					 servicetel: service.servicetel,
					 service_recc: service.servicerecommended,
					 saddress_show: saddress_show,
					 servicetag: service.service_id+service.servicename.replace(" ", "_")+'service',
					 service_name: service.servicename,
					 service_desc: service.servicedescription,
					 service_facebook: service.servicefacebook,
					 service_msgr: service.servicemessenger,
					 service_insta: service.serviceinstagram,
					 service_website: service.servicewebsite,
					 service_image: imagePath+service.serviceimage,
					 service_imagesmall: imagePath+service.serviceimage.replace(".","_tn."),
					 service_address: sAddress,
					 service_coords: coords,
				}
		curTemplate=replaceMe(curTemplate,data);
		servicesHTML=servicesHTML+curTemplate;
		
		lastType=serviceType;
	}
	//existingHTML=document.getElementById("page3").innerHTML;
	//console.log(existingHTML+servicesHTML);
	document.getElementById('serviceslist').innerHTML=servicesHTML;
	//getServiceCollapsables();
}

var rect;
var id;
var scrollPosition=0;

function getEventCollapsables() { 
	const eventcollapsables = document.querySelectorAll('.eventexpand');
	//const offercollapsables = document.querySelectorAll('.offerexpand'); 
	eventcollapsables.forEach(docollapse => { 
		docollapse.addEventListener('click', function handleCollapse(event) {
			theElement=document.getElementById("coll_"+this.id);
			tap4more=document.getElementById("t4m_"+this.id);
			if (theElement.style.maxHeight) { 
				theElement.style.maxHeight=null; 
				//tap4more.style.maxHeight='20px'; 
				} else {
				const expanded=document.querySelectorAll('.collapse');
				for (let i=0; i < expanded.length; i++) { expanded[i].style.maxHeight=null; }
				theElement.style.maxHeight=theElement.scrollHeight + "px";
				//tap4more.style.maxHeight='0';
				//tap4more.style='display:none;';
			}
		});
	});
}

function getOfferCollapsables() { 
	const offercollapsables = document.querySelectorAll('.offerexpand'); 
	offercollapsables.forEach(docollapse => { 
		docollapse.addEventListener('click', function handleCollapse(event) {
			theElement=document.getElementById("highlightcollapse"+this.id);
			
			if (theElement.style.maxHeight) { theElement.style.maxHeight=null; } else {
				const expanded=document.querySelectorAll('.collapse');
				for (let i=0; i < expanded.length; i++) { expanded[i].style.maxHeight=null; }
				theElement.style.maxHeight=theElement.scrollHeight + "px";
			}
		});
	});
}

function getPlaceCollapsables() { 
	const venuecollapses = document.querySelectorAll('.venueexpand');
	// the following function displays the explore fullscreen modal
	venuecollapses.forEach(vencollapse => {vencollapse.addEventListener('click', function handleVenCollapse(event) {
		//console.log("venuetap");
		scrollPosition=window.scrollY;
		document.getElementById('appbody').classList.add('scrollstop'); 
		var myId=this.id;
		var fsId="fs"+myId;
		const fsclone = document.getElementById(fsId).cloneNode(true);
		var fullscreen=document.getElementById("fullscreencard");
		fullscreen.appendChild(fsclone);
		fullscreen.style="diaplay:block";
		fullscreen.firstElementChild.id="fullsc";
		fullscreen.style.transform="translateY(-100vh)";
		document.getElementById("fullsc").style.display="block";
		setTimeout(function() { fullscreen.style="display:block"; },2);	
		setTimeout(function() {	fullscreen.style.transform="translateY(0)"; },20);
		setTimeout(function() { window.scrollTo(0,0); }, 100); // Not sure we need this scrolling
		});
	});
}

function destroyFullscreen() {
	document.getElementById('appbody').classList.remove('scrollstop');
	var thisId=document.getElementById('fullscreencard');
	thisId.style.transform="translateY(200vh)";
	thisId.style.height="0";
	setTimeout(function() { thisId.innerHTML=""; },300);  // destroy the clone
	setTimeout(function() { window.scroll(0,scrollPosition); },20);	 // Not sure we need this scrolling */
}

const venuexpands = document.querySelectorAll('.venuecollapse'); 
const scrollables = document.querySelectorAll('.placesdivider');

// the following function hides the explore fullscreen modal & everything associated with that
document.getElementById('fullscreencard').addEventListener('click', function hideFullscreen(event) {
	destroyFullscreen();
});

function checkVisibilityJ(e) {
    return !!( e.offsetWidth || e.offsetHeight || e.getClientRects().length );
}

function doPage(pageNo) {
	const pages = document.querySelectorAll('.appcontent');	
    if (checkVisibilityJ(pages[0])) currentPage=0; 
    if (checkVisibilityJ(pages[1])) currentPage=1;
    if (checkVisibilityJ(pages[2])) currentPage=2;
	// if (checkVisibilityJ(pages[3])) currentPage=3;
	// if (checkVisibilityJ(pages[4])) currentPage=4;
	hideSafety();

	if (pageNo > currentPage) {
		for (let i=0; i < pages.length; i++ ) { pages[i].style.transform="translateX(150%)"; }
		pages[currentPage].style.transform="translateX(-150%)";
		pages[pageNo].style.display="block";
		setTimeout(function() {	pages[currentPage].style.display="none"; },260);
		setTimeout(function() {	pages[pageNo].style.transform="translateX(0)";	},10);
	}
	
	if (pageNo < currentPage) {
		for (let i=0; i < pages.length; i++ ) { pages[i].style.transform="translateX(-150%)"; }
		pages[currentPage].style.transform="translateX(150%)";
		pages[pageNo].style.display="block";
		setTimeout(function() {	pages[currentPage].style.display="none"; },260);
		setTimeout(function() {	pages[pageNo].style.transform="translateX(0)";	},10);	
	}
	for (let i=0; i<buttons.length; i++) { buttons[i].classList.remove('menuitemactive'); }
	buttons[pageNo].classList.add('menuitemactive');
}

const buttons = document.querySelectorAll('.menubutton');

buttons.forEach(button => { 
							button.addEventListener('click', function handleButton(event) {
																							destroyFullscreen();
																							id=this.id;
																							id=id.replace('btn','');
																							id=id.replace('page','')-1;
																							doPage(id);
																							}
													);
});

button = document.getElementById("closehide");

document.getElementById("closebtn").addEventListener('click', function closeClick(){
	document.getElementById("closehide").style.Zindex="0";
	document.getElementById("closehide").style.display="none";
	document.getElementById("main").style.transform='translate(0,0)';
	document.getElementById("heading").style.transform='translate(0,0)';
	document.getElementById("footmenu").style.transform='translate(0,0)';
	document.getElementById('fsclone').style.transform="translate(100%,0)";
	document.getElementById('closebtn').style.opacity="0";
	setTimeout(function() {	document.getElementById('fullscreencard').innerHTML="";	},500);
});

function addMapListener() {
	document.getElementById("maplink").addEventListener('click', function mapClick(){
		if 
	 (/(iPad|iPhone|iPod)/g.test(navigator.userAgent))   { 
			document.getElementById("maplink").outerHTML=document.getElementById("maplink").outerHTML.replace("https://","maps://");
	 }

	});
}

function hideSafety() {
	const popup=document.getElementById("s_popup");
	if (document.getElementById("safetypagebtn").classList.contains("menuitemactive")) {
		const scrollY = document.getElementById('appbody').style.top;
		document.getElementById('appbody').style.top = '';
		document.getElementById('appbody').style.position = '';
		window.scrollTo(0, parseInt(scrollY || '0') * -1);
		//document.getElementById("menucover").style.display="none";
		document.getElementById("fadeout").style.opacity="0";
		//get active page & make its menu link highlighted again
		var pageNo;
		 const pages = document.querySelectorAll('.appcontent');	
			if (checkVisibilityJ(pages[0])) pageNo=1; 
			if (checkVisibilityJ(pages[1])) pageNo=2;
			if (checkVisibilityJ(pages[2])) pageNo=3;
			document.getElementById("page"+pageNo+"btn").classList.add("menuitemactive");
		// disappear the popup
		popup.style.transform="translateY(152%)";
		document.getElementById("safetypagebtn").classList.remove("menuitemactive");
		setTimeout(function() {
								document.getElementById("fadeout").style.display="none"; 
								popup.style.display="none";
							  },250
				  );	
	}
}

function setupSafetyListeners() {
document.getElementById("safetypagebtn").addEventListener('click', function safetyClick() {
	const popup=document.getElementById("s_popup");
	if (document.getElementById("safetypagebtn").classList.contains("menuitemactive")) {
				hideSafety();
				return;
	}

	document.getElementById('appbody').style.top = `-${window.scrollY}px`;
	document.getElementById('appbody').style.position = 'fixed';

	const buttons = document.querySelectorAll('.menubutton');
	buttons.forEach(button => { button.classList.remove("menuitemactive"); });
	document.getElementById("safetypagebtn").classList.add("menuitemactive");
	
	popup.style.display="block";
	document.getElementById("fadeout").style.display="block";
	setTimeout(function() { 
							var scrollAmount = window.innerHeight-document.getElementById("s_popup").scrollHeight-document.getElementById("footmenu").scrollHeight;
							popup.style.transform="translateY("+scrollAmount+"px)";
							//popup.style.transform="translateY(40%)"; 
							document.getElementById("fadeout").style.opacity="1";
						  },50
			   );
});

document.getElementById('safespaces').addEventListener('click', function hideFullscreen(event) {
	var thisId=document.getElementById('safespaces');
	thisId.style.transform="translateY(200vh)";
	setTimeout(function() { thisId.style.display="none"; },250);
	setTimeout(function() { window.scroll(0,scrollPosition); },20);	 // Not sure we need this scrolling
});

document.getElementById('personalsafety').addEventListener('click', function hideFullscreen(event) {
	var thisId=document.getElementById('personalsafety');
	thisId.style.transform="translateY(200vh)";
	setTimeout(function() { thisId.style.display="none"; },250);
	setTimeout(function() { window.scroll(0,scrollPosition); },20);	 // Not sure we need this scrolling
});

document.getElementById('intervention').addEventListener('click', function hideFullscreen(event) {
	var thisId=document.getElementById('intervention');
	thisId.style.transform="translateY(200vh)";
	setTimeout(function() { thisId.style.display="none"; } ,250);
	setTimeout(function() { window.scroll(0,scrollPosition); },20);	 // Not sure we need this scrolling
});

document.getElementById('reporting').addEventListener('click', function hideFullscreen(event) {
	var thisId=document.getElementById('reporting');
	thisId.style.transform="translateY(200vh)";
	setTimeout(function() { thisId.style.display="none"; },250);
	setTimeout(function() { window.scroll(0,scrollPosition); },20);	 // Not sure we need this scrolling
});

// add listener for the safe spaces button
document.getElementById("safespacebutton").addEventListener('click', function psClick() {
	scrollPosition=window.scrollY;
	var myId=this.id;
	var fullscreen=document.getElementById("safespaces");	
	setTimeout(function() { fullscreen.style="display:block"; },2);	
	setTimeout(function() {	fullscreen.style.transform="translateY(0)"; },50);
	setTimeout(function() { window.scrollTo(0,0); }, 100); // Not sure we need this scrolling
});


// add listener for the personal safety button
document.getElementById("psbutton").addEventListener('click', function psClick() {
	scrollPosition=window.scrollY;
	var myId=this.id;
	var fullscreen=document.getElementById("personalsafety");	
	setTimeout(function() { fullscreen.style="display:block"; },2);	
	setTimeout(function() {	fullscreen.style.transform="translateY(0)"; },50);
	setTimeout(function() { window.scrollTo(0,0); }, 100); // Not sure we need this scrolling
});

// add listener for the intervention button
document.getElementById("othersbutton").addEventListener('click', function intClick() {
	scrollPosition=window.scrollY;
	var myId=this.id;
	var fullscreen=document.getElementById("intervention");	
	setTimeout(function() { fullscreen.style="display:block"; },2);	
	setTimeout(function() {	fullscreen.style.transform="translateY(0)"; },50);
	setTimeout(function() { window.scrollTo(0,0); }, 100); // Not sure we need this scrolling
});


// add listener for the reporting button
document.getElementById("reportingbutton").addEventListener('click', function intClick() {
	scrollPosition=window.scrollY;
	var myId=this.id;
	var fullscreen=document.getElementById("reporting");	
	setTimeout(function() { fullscreen.style="display:block"; },2);	
	setTimeout(function() {	fullscreen.style.transform="translateY(0)"; },50);
	setTimeout(function() { window.scrollTo(0,0); }, 100); // Not sure we need this scrolling
});

var coll = document.getElementsByClassName("scollapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.firstElementChild;
	if (content.classList.value.includes("s_icon")) { content=this.firstElementChild.nextElementSibling; }
    if (content.style.maxHeight){
      content.style.maxHeight = null;
    } else {
		const expanded=document.querySelectorAll('.safetypara');
		
	for (let x=0; x < expanded.length; x++) { expanded[x].style.maxHeight=null; }
      content.style.maxHeight = (content.scrollHeight+400) + "px";
	  content.parentElement.style.opacity=1;
	  content.style.opacity = 1;
    }
  });
}

var coll2 = document.getElementsByClassName("scollapsible2");
for (i = 0; i < coll2.length; i++) {
  coll2[i].addEventListener("click", function(e) {
	  e.stopPropagation();
    this.classList.toggle("active");
    var content = this.nextElementSibling;
	var content = this.firstElementChild;
	if (content.classList.value.includes("s_icon")) { content=this.firstElementChild.nextElementSibling; }
    if (content.style.maxHeight){
      content.style.maxHeight = null;
    } else {
		const expanded=document.querySelectorAll('.safetymore');
	for (let x=0; x < expanded.length; x++) { expanded[x].style.maxHeight=null; }
      content.style.maxHeight = (content.scrollHeight+400) + "px";
    }
  });
}

var coll3 = document.getElementsByClassName("scollapsible3");

for (i = 0; i < coll3.length; i++) {

  coll3[i].addEventListener("click", function(e) {
	const exp=document.querySelectorAll('.safetypara');
	
	  e.stopPropagation();
	
		var content = this.firstElementChild.nextElementSibling;
	
		if (content.style.maxHeight){
									content.style.maxHeight = null;
		} else {
		const expanded=document.querySelectorAll('.safetymore2');
		for (let x=0; x < exp.length; x++) {exp[x].style.maxHeight=null; }
		for (let x=0; x < expanded.length; x++) { expanded[x].style.maxHeight=null; }
		content.style.maxHeight = (content.scrollHeight+400) + "px";
    }
	
  });
}
};
/*
setTimeout(function() { 
	document.getElementById("bigmapbutton").addEventListener('click', function intClick() {
		document.getElementById("mapclose").style.display="block";
			var bigmap=document.getElementById("bigmap");	
			viewport = document.querySelector("meta[name=viewport]");
			viewport.setAttribute('content', 'initial-scale=0.2, minimum-scale=0.1, maximum-scale=3.0, user-scalable=1');
			//document.body.style.maxWidth="600%";
			document.body.style.overflowX="scroll";
			bigmap.style.display="block";
	});
	}
,250);	

document.getElementById("mapclose").addEventListener('click', function intClick() {
	viewport = document.querySelector("meta[name=viewport]");
	viewport.setAttribute('content', 'width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no');
	document.getElementById("bigmap").style.display="none";
	document.getElementById("mapclose").style.display="none";
});

*/


function titleText() {
	var targetText = document.querySelector('.monthtext');
	var targetImg = document.querySelector('.headlogo');
	
	var vw = Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0);
	
	if (vw > 1024) {vw=vw*0.8; }
	//console.log("width:"+vw);
	targetText.innerText.replace("#LFF","");
	var monthText = targetText.innerText;
	var fontScale;
	var imgScale;
	var dateInset;
	
	switch (monthText) {
			case "January":
				fontScale=1.4; imgScale=.7;
			break;
			case "February":
				fontScale=1.48; imgScale=.7;
			break;
			case "March":
				fontScale=1.2; imgScale=.7; dateInset=20;
			break;
			case "April":
				fontScale=1.55; imgScale=.7; dateInset=20;
			break;
			case "May":
				fontScale=.95; imgScale=.7;
			break;
			case "June":
				fontScale=1.2; imgScale=.7;
			break;
			case "July":
				fontScale=1.35; imgScale=.72;
			break;
			case "August":
				fontScale=1.3; imgScale=.72; dateInset=13;
			break;
			case "September":
				fontScale=1.4; imgScale=.7;
			break;
			case "October":
				fontScale=1.4; imgScale=.72;
			break;
			case "November":
				fontScale=1.32; imgScale=.72;
			break;
			case "December":
				fontScale=1.32; imgScale=.72;
			break;
			case "LeedsPride":
				fontScale=1.53; imgScale=.72; dateInset=60;
			break;
	}
	var numChars = monthText.length;
	var fontSize = (vw / numChars)*fontScale;
	targetText.style="font-size:"+fontSize+"px";
	targetImg.style.height=fontSize*imgScale;
	targetText.style.marginTop="-"+fontSize*.29+"px";
	document.querySelector('.headerdate').style="right:"+dateInset+"vw";
}

</script>

</body>
</html>

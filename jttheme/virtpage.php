<?php

$venues=[];
$events=[];

foreach (glob (PATH_CONTENT.'lff-events/venues/*.json') as $key => $file ) 
	{
		$data=json_decode(file_get_contents($file),true);
		$venues[]=$data;
	}

function findFile($id) {

	foreach (glob (PATH_CONTENT.'lff-events/*', GLOB_ONLYDIR) as $folder)
		{
			if (!str_contains($folder,'json'))
			{
				foreach (glob ($folder.'/*.json') as $file) 
					{
						$contents = file_get_contents($file);
						if (strpos($contents, $id)) { return $file; }
					}
			}	
	}
	
}

function findVenueImage($name) {
	global $venues;
	foreach ($venues as $venue) {
		if ($venue['venuename'] == $name) { return $venue['venueimage']; }
	}

}

function getData($filename) {
$data = json_decode(file_get_contents($filename),true);
return $data;	
}

function showHighlight($id) {
	
$highlightID=$_GET['highlight'];

$highlight=findFile($highlightID);
$data = getData($highlight);
$html = '<section style="display:flex;">';
$html .= '<div class="pageleft">';
$html .= '<img style="width:35vw;" src="'.HTML_PATH_ROOT.'/bl-content/lff-events/images/'.findVenueImage($data['highlightvenue']).'" />';
$html .= '</div>';
$html .= '<div class="pageright">';
$html .= '<h4>'.$data['highlighttitle'].'</h4>';
$html .= '<h5>'.$data['highlightsubtitle'].'</h5>';
$html .= '<p>'.$data['highlightdescription'].'</p>';
$html .= '</div>';
$html .= '</section>';
echo '<br><br>';
echo $html;
}

function showVenue($id) {
	$venueID = $_GET['venue'];
	$venue=findFile($venueID);
	$data=getData($venue);
	echo '<br><br>';
	$html = '<section style="display:flex;">';
	$html .= '<div class="pageleft">';
	$html .= '<img style="width:35vw;" src="'.HTML_PATH_ROOT.'/bl-content/lff-events/images/'.$data['venueimage'].'" />';
	$html .= '</div>';
	$html .= '<div class="pageright" style="margin-left:2.5vw;">';
	$html .= '<h4>'.$data['venuename'].'</h4>';
	$html .= '<p>'.$data['venuedescription'].'</p>';
	$html .= '<p>'.$data['venueaddress'].'</p>';
	$html .= '</div';
	$html .= '</section>';
	echo $html;
}

function showEvent($id) {
	$eventID = $_GET['event'];
	$event=findFile($eventID);
	$data=getData($event);
	$html = '<section style="display:flex;">';
	$html .= '<div class="pageleft">';
	$html .= '<img style="width:35vw;" src="'.HTML_PATH_ROOT.'/bl-content/lff-events/images/'.$data['eventimage'].'" />';
	$html .= '</div>';
	$html .= '<div class="pageright" style="margin-left:2.5vw;">';
	$html .= '<h4>'.$data['eventtitle'].'</h4>';
	$html .= '<h4>'. date("l jS F Y g:i a",strtotime($data['eventstart'])).'</h4>';
	$html .= '<h5>'.$data['eventsubtitle'].'</h5>';
	$html .= '<p>'.$data['eventdescription'].'</p>';
	$html .= '<a href="'.$data['eventctaurl'].'">'.$data['eventctatext'].'</a>';
	$html .= '</div>';
	$html .= '</section>';
	echo '<br><br>';
	echo $html;
}

switch (key($_GET)) {
	case 'highlight':
		showHighlight($_GET['highlight']);
		break;
	
	case 'venue':
		showVenue($_GET['venue']);
		break;

	case 'event':
		showEvent($_GET['event']);
		break;
}
?>

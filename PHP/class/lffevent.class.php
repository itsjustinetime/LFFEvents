<?php

class LFFJSON
{
	public function updateJSON() {
	$dateTime=str_replace(" ","T",date("Y-m-d H:i",strtotime($date. ' + 1 days')));

	function findData($array,$column,$searchcol,$search) {
			foreach ($array as $item) {
				if ($item[$searchcol] == $search) {return $item[$column]; }
			}
	}

		// Get venue data
		$venuejsondata=[];
		$venuedata=[];

		if (!empty(glob(PATH_CONTENT . 'lff-events/venues/*.json'))) {
				foreach (glob(PATH_CONTENT . 'lff-events/venues/*.json') as $key => $file) {
					$data = json_decode(file_get_contents($file),true);
					if (!array_key_exists('venuegps',$data)) { $data['venuegps']=''; }
					if ($data['venueshow'] == 'on') { $venuejsondata[]=$data; } // we only want venues set to show to appear in the official venues list
					$venuedata[]=$data;
				}
		}
		array_multisort(array_column($venuejsondata, 'venuecategory'), SORT_ASC, array_column($venuejsondata, 'venuepriority'), SORT_ASC, $venuejsondata);
		// Get event data
		$eventdata=[];

		if (!empty(glob(PATH_CONTENT . 'lff-events/events/*.json'))) {
				foreach (glob(PATH_CONTENT . 'lff-events/events/*.json') as $key => $file) {
					$data = json_decode(file_get_contents($file),true);
					$data['venueaddress']=findData($venuedata,'venueaddress','venuename',$data['eventvenue']);
					$data['venuegps']=findData($venuedata,'venuegps','venuename',$data['eventvenue']);
					if ($data['eventshow'] == 'on'  && $data['eventstart'] > $dateTime) { $eventdata[]=$data; }
				}
		}

		$recCount=0;

		// find recurring events in list
		$recurring=[];
		foreach ($eventdata as $event) {

			if ( $event['eventrecur']== 'on' && !str_contains($event['eventtitle'],"LFF ")) {
				array_push($recurring,$event); //$recurring[$recCount]=$event;
				$recCount++;
			}
		}

		$LFFCount=0;
		$LFFs=[];
		// find LFF events in list
		foreach ($eventdata as $event) {
			$dayofweek = date('w', strtotime(explode("T", $event['eventstart'])[0]));
			echo $dayofweek.'<br>'.$event['eventtitle'];
			if ( str_contains($event['eventtitle']," LFF") && $dayofweek==5 ) {
				array_push($LFFs,$event);
				$LFFCount++;
			}
		}
		echo "lffcount:".$LFFCount;
		$count=0;
		if (sizeof($LFFs) > 0) {
			$count++;
			$lffcount=0;
			foreach ($LFFs as $LFF) {
							if ($LFF['eventstart'] > $dateTime ) {
				$LFFdate=explode("T",$LFF['eventstart'])[0];
				$LFFenddate=explode("T",$LFF['eventend'])[0];
				foreach ($recurring as $recur) {
					$recDate = explode("T",$recur['eventstart'])[0];
					if ($recDate == $LFFdate) { continue; }
					$recur['eventid']='recur'.uniqid();
					$recTime=explode("T",$recur['eventstart'])[1];
					$recEndTime=explode("T",$recur['eventend'])[1];
					$recur['eventstart']=$LFFdate."T".$recTime;
					$recur['eventend']=$LFFenddate."T".$recEndTime;
					$recur['eventfull']=0;

					if ($recur['eventtitle']=="Pints N Straws") {
						$recur['eventvenue']='TBC';
						$recur['venuegps']='53.795297164527895, -1.5425020441783828';
						$recur['eventdescription']='';
					}
					if (str_contains($recur['eventtitle'],"Pints N")) { $recur['eventtitle']="Pints N Straws"; }

					array_push($eventdata,$recur);
					$count++;
				}
				$lffcount++;
			}
			}
		}

		array_multisort(array_column($eventdata, 'eventstart'), SORT_ASC, array_column($eventdata, 'eventpriority'), SORT_ASC, $eventdata);

		// Get highlights data
		$highlightsdata=[];

		if (!empty(glob(PATH_CONTENT . 'lff-events/highlights/*.json'))) {
				foreach (glob(PATH_CONTENT . 'lff-events/highlights/*.json') as $key => $file) {
					$data = json_decode(file_get_contents($file),true);
					$data['venueimage']=findData($venuedata,'venueimage','venuename',$data['highlightvenue']);
					$data['venueaddress']=findData($venuedata,'venueaddress','venuename',$data['highlightvenue']);
					$data['venuegps']=findData($venuedata,'venuegps','venuename',$data['highlightvenue']);
					if ($data['highlightshow'] == 'on' ) { $highlightdata[]=$data; }
				}
		}

		array_multisort(array_column($highlightdata, 'highlightcategory'), SORT_DESC, array_column($highlightdata, 'highlightpriority'), SORT_ASC, $highlightdata);
		
		// Get services data
		$servicesdata=[];

		if (!empty(glob(PATH_CONTENT . 'lff-events/services/*.json'))) {
				foreach (glob(PATH_CONTENT . 'lff-events/services/*.json') as $key => $file) {
					$data = json_decode(file_get_contents($file),true);
					if ($data['serviceshow'] == 'on') {	$servicesdata[]=$data; }
				}
		}

		$eventJson=json_encode($eventdata);
		if (json_last_error()) $eventJson=json_encode("['eventtitle']='NODATA'");
		$highlightJson=json_encode($highlightdata);
		if (json_last_error()) $highlightJson=json_encode("['highlighttitle']='NODATA'");
		$placesJson=json_encode($venuejsondata);
		if (json_last_error()) $placesJson=json_encode("['placename']='NODATA'");
		$servicesJson=json_encode($servicesdata);

		if (json_last_error()) $servicesJson=json_encode("['servicename']='NODATA'");

		$jsonString='{ "events": '.$eventJson.','.' "highlights": '.$highlightJson.','.' "places": '.$placesJson.', "services": '.$servicesJson. ' }';

		$folder = PATH_CONTENT . 'lff-events/json/';
		if (!file_exists($folder)) {
            		mkdir($folder, 0755);
            		file_put_contents($folder . '.htaccess', 'Allow from all');
        	}

		$path = $folder.'lffeventdata.json';
		$fp = fopen($path, 'w');
		fwrite($fp, $jsonString);
		fclose($fp);

		$eventJson = json_encode($eventdata);
		if (json_last_error()) $eventJson=json_encode("['eventid']='0'; $events[0]['eventtitle']='NODATA'");
		$path = $folder.'events.json';
		$fpe = fopen($path, 'w');
		fwrite($fpe, $eventJson);
		fclose($fpe);
		}
}

class LFFImage
{
    public $imagefilename;
	public $imagecategory;

	public const IMAGE_HANDLERS = [
		IMAGETYPE_WEBP => [
			'load' => 'imagecreatefromwebp',
			'save' => 'imagewebp',
			'quality' => 49
		],
		IMAGETYPE_JPEG => [
			'load' => 'imagecreatefromjpeg',
			'save' => 'imagejpeg',
			'quality' => 60
		],
		IMAGETYPE_PNG => [
			'load' => 'imagecreatefrompng',
			'save' => 'imagepng',
			'quality' => -1
		],
		IMAGETYPE_GIF => [
			'load' => 'imagecreatefromgif',
			'save' => 'imagegif'
		]
	];

	public function imageRename($src,$dest,$jsonfileID,$imagecat) {
		
		error_log("Renaming source:".$src." dest:".$dest. " ".$jsonfileID);
		$folder = PATH_CONTENT . 'lff-events/images/';
        $image = [];
		$image['imagefilename']=$dest.'.webp';
		$image['imagecategory']=$imagecat;
		
		$tn600Name = $src.'_600tn.webp';
		$tnName = $src.'_tn.webp';
		$Name = $src.'.webp';
		
		$filename = strtolower( $image['imagefilename'] );
        // Get timedate
        $timedate = date("Y_m_d_G_I_s");
        $nameWithDate = $filename . $timedate;
		
			if (file_exists($folder . $jsonfileID . '.json')) {
                unlink($folder . $jsonfileID . '.json');
            };

            file_put_contents($folder . $nameWithDate . '.json', json_encode($image));
		//rename($folder.$jsonfileID.'json',$folder.$dest.'.json');
		rename($folder.$tn600Name,$folder.$dest."_600tn.webp");
		rename($folder.$tnName,$folder.$dest."_tn.webp");
		rename($folder.$src.'.webp',$folder.$dest.'.webp');
	}
	
	public function createWebp($src, $dest, $targetWidth, $targetHeight = null) {
		$type = exif_imagetype($src);

		// if no valid type or no handler found -> exit
		if (!$type || !LFFImage::IMAGE_HANDLERS[$type]) {
			return null;
		}

		// load the image with the correct loader
		$image = call_user_func(LFFImage::IMAGE_HANDLERS[$type]['load'], $src);

		// no image found at supplied location -> exit
		if (!$image) {
			return null;
		}

		// get original image width and height
		$width = imagesx($image);
		$height = imagesy($image);

		// maintain aspect ratio when no height set
		if ($targetHeight == null) {

			// get width to height ratio
			$ratio = $width / $height;

			// if is portrait
			// use ratio to scale height to fit in square
			if ($width > $height) {
				$targetHeight = floor($targetWidth / $ratio);
			}
			// if is landscape
			// use ratio to scale width to fit in square
			else {
				$targetHeight = $targetWidth;
				$targetWidth = floor($targetWidth * $ratio);
			}
		}

		// create duplicate image based on calculated target size
		$thumbnail = imagecreatetruecolor($targetWidth, $targetHeight);

		// set transparency options for GIFs and PNGs
		if ($type == IMAGETYPE_GIF || $type == IMAGETYPE_PNG || $type == IMAGETYPE_WEBP) {

			// make image transparent
			imagecolortransparent(
				$thumbnail,
				imagecolorallocate($thumbnail, 0, 0, 0)
			);

			// additional settings for PNGs
			if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_WEBP) {
				imagealphablending($thumbnail, false);
				imagesavealpha($thumbnail, true);
			}
		}

		// copy entire source image to duplicate image and resize
		imagecopyresampled(
			$thumbnail,
			$image,
			0, 0, 0, 0,
			$targetWidth, $targetHeight,
			$width, $height
		);


		// 3. Save the $thumbnail to disk
		// - call the correct save method
		// - set the correct quality level

		// save the duplicate version of the image to disk
		return call_user_func(
			LFFImage::IMAGE_HANDLERS[IMAGETYPE_WEBP]['save'],
			$thumbnail,
			$dest,
			LFFImage::IMAGE_HANDLERS[IMAGETYPE_WEBP]['quality']
		);
	}

    public function getInfo($imagefilename, $imagecategory)
    {
        $this->imagefilename = $imagefilename;
		$this->imagecategory = $imagecategory;
    }

    public function createFile()
    {
	$allowTypes = array('jpg', 'jpeg', 'png','webp');
	if (empty($_FILES)) {
				$noImage=1;
				//error_log("Cannot create file with empty image");
				//return false;
			}

        $folder = PATH_CONTENT . 'lff-events/images/';
        $image = [];

	$imagefileName = $_FILES['file']['name'];
	if ($noImage) $imagefileName = $_POST['imagefilename'];
	$imagecategory = $_POST['imagecategory'];
	$image['imagefilename'] = explode(".",$imagefileName)[0].'.webp';
	$image['imagecategory'] = $imagecategory;

	$uploadStatus=1;
	$fileName = $_FILES['file']['name'];

	$targetFilePath = $folder.$fileName;
	$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

	if(in_array($fileType, $allowTypes)){ 
		// Upload file to the server 
		if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath))
			{
				$uploadedFile = $fileName;
				$name=explode(".",$uploadedFile)[0];
				$ext=explode(".",$uploadedFile)[1];
				$convertName = $folder.$name.".webp";
				$dbName = $name.".webp";
				$thumbName = $folder.$name."_tn.webp"; //.$fileType;
				$thumbName600 = $folder.$name."_600tn.webp"; //.$fileType;
				LFFImage::createWebp($targetFilePath, $convertName,1024);
				LFFImage::createWebp($targetFilePath, $thumbName, 368);
				LFFImage::createWebp($targetFilePath, $thumbName600, 600);
			}
			else 	{ 
					$uploadStatus = 0; 
					$response['message'] = 'Sorry, there was an error uploading your file.'; 
				} 
        }
		else { 	
				$uploadStatus = 0; 
				$response['message'] = 'Sorry, only '.implode('/', $allowTypes).' files are allowed to upload.'; 
                } 
		if($uploadStatus == 1)
			{ 
			     if ($ext != "webp") unlink ($targetFilePath);
				 $response['status'] = 1; 
                    $response['message'] = 'Submitted successfully!';
					$imagefilename = $convertName;
			}

		//  Now do the JSON file 
        if (!file_exists($folder)) {
            mkdir($folder, 0755);
            file_put_contents($folder . '.htaccess', 'Allow from all');
        }

		$filename = strtolower( $image['imagefilename'] );
        // Get timedate
        $timedate = date("Y_m_d_G_I_s");
        $nameWithDate = $filename . $timedate;

        if (isset($_GET['edit'])) {
            global $SITEURL;
            global $GSADMIN;

            if (file_exists($folder . $_GET['edit'] . '.json')) {
                unlink($folder . $_GET['edit'] . '.json');
            };

            file_put_contents($folder . $_GET['edit'] . '.json', json_encode($image));
            echo ("<meta http-equiv='refresh' content='0;url=" . DOMAIN_ADMIN . "plugin/lffevents?listimages" . $_GET['edit'] . "'>");
        } else {
            global $SITEURL;
            global $GSADMIN;
            file_put_contents($folder . $nameWithDate . '.json', json_encode($image));
            echo ("<meta http-equiv='refresh' content='0;url=" . DOMAIN_ADMIN . "plugin/lffevents?listimages" . $nameWithDate . "'>");
        }
    }
};

class LFFService
{
    public $servicename;
	public $servicecategory;
	public $serviceaddress;
    public $servicedescription;
    public $serviceimage;
    public $servicegps;
	public $servicewebsite;
    public $serviceinstagram;
    public $servicefacebook;
	public $serviceshow;
	public $servicepriority;
	public $servicemessenger;
	public $serviceid;


    public function getInfo($servicename, $servicecategory, $serviceaddress, $servicedescription, $serviceimage, $servicegps, $servicewebsite, $serviceinstagram, $servicefacebook, $serviceshow,$servicepriority,$servicemessenger,$serviceid)
    {
        $this->servicename = $servicename;
		$this->servicedescription = $servicedescription;
		$this->servicecategory = $servicecategory;
		$this->serviceaddress = $serviceaddress;
        $this->serviceimage = $serviceimage;
        $this->servicegps = $servicegps;
        $this->servicewebsite = $servicewebsite;
		$this->serviceinstagram = $serviceinstagram;
        $this->servicefacebook = $servicefacebook;
        $this->serviceshow = $serviceshow;
		$this->servicepriority = $servicepriority;
		$this->servicemessenger = $servicemessenger;
		$this->serviceid = $serviceid;
    }


    public function createFile()
    {
        $folder = PATH_CONTENT . 'lff-events/services/';
        $service = [];
        $service['servicename'] = $this->servicename;
		$service['servicecategory'] = $this->servicecategory;
		$service['serviceaddress'] = $this->serviceaddress;
        $service['servicedescription'] = $this->servicedescription;
        $service['serviceimage'] = $this->serviceimage;
        $service['servicegps'] = $this->servicegps;
		$service['servicewebsite'] = $this->servicewebsite;
		$service['serviceinstagram'] = $this->serviceinstagram;
        $service['servicefacebook'] = $this->servicefacebook;
		$service['serviceshow'] = $this->serviceshow;
		$service['servicepriority'] = $this->servicepriority;
		$service['servicemessenger'] = $this->servicemessenger;
		$service['serviceid'] = $this->serviceid;

        if (!file_exists($folder)) {
            mkdir($folder, 0755);
            file_put_contents($folder . '.htaccess', 'Allow from all');
        }
		$filename = preg_replace( '/[^a-z0-9]+/', '-', strtolower( $this->servicename ) );
        // Get timedate
        $timedate = date("Y_m_d_G_I_s");
        $nameWithDate = $filename . $timedate;

        if (isset($_GET['edit'])) {
            global $SITEURL;
            global $GSADMIN;

            if (file_exists($folder . $_GET['edit'] . '.json')) {
                unlink($folder . $_GET['edit'] . '.json');
            };

            file_put_contents($folder . $_GET['edit'] . '.json', json_encode($service));
            echo ("<meta http-equiv='refresh' content='0;url=" . DOMAIN_ADMIN . "plugin/lffevents?addservice&edit=" . $_GET['edit'] . "'>");
        } else {
            global $SITEURL;
            global $GSADMIN;
            file_put_contents($folder . $nameWithDate . '.json', json_encode($service));
            echo ("<meta http-equiv='refresh' content='0;url=" . DOMAIN_ADMIN . "plugin/lffevents?addservice&edit=" . $hashedTitle . "'>");
        }
    }
};

class LFFVenue
{
    public $venuename;
	public $venuecategory;
	public $venueaddress;
    public $venuedescription;
    public $venueimage;
    public $venuegps;
	public $venuewebsite;
    public $venueinstagram;
    public $venuefacebook;
	public $venueshow;
	public $venuepriority;
	public $venuerecommended;
	public $venueid;

    public function getInfo($venuename, $venuecategory, $venueaddress, $venuedescription, $venueimage, $venuegps, $venuewebsite, $venueinstagram, $venuefacebook, $venueshow,$venuepriority,$venuerecommended,$venueid)
    {
        $this->venuename = $venuename;
		$this->venuecategory = $venuecategory;
		$this->venueaddress = $venueaddress;
		$this->venuedescription = $venuedescription;
        $this->venueimage = $venueimage;
        $this->venuegps = $venuegps;
        $this->venuewebsite = $venuewebsite;
		$this->venueinstagram = $venueinstagram;
        $this->venuefacebook = $venuefacebook;
        $this->venueshow = $venueshow;
		$this->venuepriority = $venuepriority;
		$this->venuerecommended = $venuerecommended;
		$this->venueid = $venueid;
    }

    public function createFile()
    {
        $folder = PATH_CONTENT . 'lff-events/venues/';
        $venue = [];
        $venue['venuename'] = $this->venuename;
		$venue['venuecategory'] = $this->venuecategory;
		$venue['venueaddress'] = $this->venueaddress;
        $venue['venuedescription'] = $this->venuedescription;
        $venue['venueimage'] = $this->venueimage;
        $venue['venuegps'] = $this->venuegps;
		$venue['venuewebsite'] = $this->venuewebsite;
		$venue['venueinstagram'] = $this->venueinstagram;
        $venue['venuefacebook'] = $this->venuefacebook;
		$venue['venueshow'] = $this->venueshow;
		$venue['venuepriority'] = $this->venuepriority;
		$venue['venuerecommended'] = $this->venuerecommended;
		$venue['venueid'] = $this->venueid;
		if (empty($venue['venuegps'])) {$venue['venuegps']='NULL'; }
        if (!file_exists($folder)) {
            mkdir($folder, 0755);
            file_put_contents($folder . '.htaccess', 'Allow from all');
        }


		$filename = preg_replace( '/[^a-z0-9]+/', '-', strtolower( $this->venuename ) );
        // Get timedate
        $timedate = date("Y_m_d_G_I_s");
        $nameWithDate = $filename .'_'. $timedate;

        if (isset($_GET['edit'])) {
            global $SITEURL;
            global $GSADMIN;


            if (file_exists($folder . $_GET['edit'] . '.json')) {
                unlink($folder . $_GET['edit'] . '.json');
            };

            file_put_contents($folder . $_GET['edit'] . '.json', json_encode($venue));
            echo ("<meta http-equiv='refresh' content='0;url=" . DOMAIN_ADMIN . "plugin/lffevents?addvenue&edit=" . $_GET['edit'] . "'>");
        } else {
            global $SITEURL;
            global $GSADMIN;
            file_put_contents($folder . $nameWithDate . '.json', json_encode($venue));
            echo ("<meta http-equiv='refresh' content='0;url=" . DOMAIN_ADMIN . "plugin/lffevents?addvenue&edit=" . $hashedTitle . "'>");
        }
    }
};
class LFFHighlight
{
    public $highlighttitle;
	public $highlightsubtitle;
    public $highlightdescription;
    public $highlightstart;
    public $highlightend;
	public $highlightctatext;
    public $highlightctaurl;
	public $highlightvenue;
	public $highlightshow;
	public $highlightpriority;
	public $highlightoffercode;
	public $highlightcategory;
	public $highlightid;

    public function getInfo($highlighttitle, $highlightsubtitle, $highlightdescription, $highlightstart, $highlightend, $highlightctatext, $highlightctaurl, $highlightvenue, $highlightshow,$highlightpriority,$highlightoffercode,$highlightcategory, $highlightid)
    {
        $this->highlighttitle = $highlighttitle;
		$this->highlightsubtitle = $highlightsubtitle;
        $this->highlightdescription = $highlightdescription;
        $this->highlightstart = $highlightstart;
        $this->highlightend = $highlightend;
		$this->highlightctatext = $highlightctatext;
        $this->highlightctaurl = $highlightctaurl;
		$this->highlightvenue = $highlightvenue;
		$this->highlightshow  = $highlightshow;
		$this->highlightpriority  = $highlightpriority;
		$this->highlightoffercode  = $highlightoffercode;
		$this->highlightcategory  = $highlightcategory;
		$this->highlightid = $highlightid;
    }


    public function createFile()
    {

        $folder = PATH_CONTENT . 'lff-events/highlights/';

        $highlight = [];
        $highlight['highlighttitle'] = $this->highlighttitle;
		$highlight['highlightsubtitle'] = $this->highlightsubtitle;
        $highlight['highlightdescription'] = $this->highlightdescription;
        $highlight['highlightstart'] = $this->highlightstart;
        $highlight['highlightend'] = $this->highlightend;
		$highlight['highlightctatext'] = $this->highlightctatext;
		$highlight['highlightvenue'] = $this->highlightvenue;
		$highlight['highlightshow'] = $this->highlightshow;
		$highlight['highlightpriority'] = $this->highlightpriority;
		$highlight['highlightoffercode'] = $this->highlightoffercode;
		$highlight['highlightcategory'] = $this->highlightcategory;
		$highlight['highlightid'] = $this->highlightid;

        if ($this->highlightctaurl !== '') {
            $highlight['highlightctaurl'] =  $this->highlightctaurl;
        };

        if (!file_exists($folder)) {
            mkdir($folder, 0755);
            file_put_contents($folder . '.htaccess', 'Allow from all');
        }

		$filename = preg_replace( '/[^a-z0-9]+/', '-', strtolower( $this->highlighttitle ) );
        // Get timedate
        $timedate = date("Y_m_d_G_I_s");
        $titleWithDate = $filename .'_'. $timedate;

        if (isset($_GET['edit'])) {
            global $SITEURL;
            global $GSADMIN;

            if (file_exists($folder . $_GET['edit'] . '.json')) {
                unlink($folder . $_GET['edit'] . '.json');
            };

            file_put_contents($folder . $_GET['edit'] . '.json', json_encode($highlight));
            echo ("<meta http-equiv='refresh' content='0;url=" . DOMAIN_ADMIN . "plugin/lffevents?addhighlight&edit=" . $_GET['edit'] . "'>");
        } else {
            global $SITEURL;
            global $GSADMIN;
            file_put_contents($folder . $titleWithDate . '.json', json_encode($highlight));
            echo ("<meta http-equiv='refresh' content='0;url=" . DOMAIN_ADMIN . "plugin/lffevents?addhighlight&edit=" . $titleWithDate . "'>");
        }
    }
};

class LFFPasscode
{
    public $passcodename;
	public $passcodevalue;
	public $passcodeexpires;
	public $passcodeid;
	
    public function getInfo($passcodeid, $passcodename, $passcodevalue, $passcodeexpires)
    {
        $this->passcodename = $passcodename;
		$this->passcodeid = $passcodeid;
        $this->passcodevalue = $passcodevalue;
        $this->passcodeexpires = $passcodeexpires;
    }

    public function createFile()
    {
        $folder = PATH_CONTENT . 'lff-events/passcodes/';
        $passcode = [];
		$passcode['passcodeid'] = $this->passcodeid;
		$passcode['passcodename'] = $this->passcodename;
		$passcode['passcodevalue'] = $this->passcodevalue;
		$passcode['passcodeexpires'] = $this->passcodeexpires;
		
        if (!file_exists($folder)) {
            mkdir($folder, 0755);
            file_put_contents($folder . '.htaccess', 'Allow from all');
        }

		$filename = preg_replace( '/[^a-z0-9]+/', '-', strtolower( $this->passcodename ) );
        // Get timedate
        $timedate = date("Y_m_d_G_I_s");
        $titleWithDate = $filename .'_'. $timedate;

        if (isset($_GET['edit'])) {
            global $SITEURL;
            global $GSADMIN;

            if (file_exists($folder . $_GET['edit'] . '.json')) {
                unlink($folder . $_GET['edit'] . '.json');
            };

            file_put_contents($folder . $_GET['edit'] . '.json', json_encode($passcode));
            echo ("<meta http-equiv='refresh' content='0;url=" . DOMAIN_ADMIN . "plugin/lffevents?addpasscode&edit=" . $_GET['edit'] . "'>");
        } else {
            global $SITEURL;
            global $GSADMIN;
            file_put_contents($folder . $titleWithDate . '.json', json_encode($passcode));
            echo ("<meta http-equiv='refresh' content='0;url=" . DOMAIN_ADMIN . "plugin/lffevents?addpasscode&edit=" . $titleWithDate . "'>");
        }
    }
};

class LFFEvent
{
    public $eventtitle;
	public $eventsubtitle;
    public $eventdescription;
    public $eventstartDate;
    public $eventendDate;
	public $eventctatext;
    public $eventctaurl;
    public $eventfullday;
	public $eventvenue;
	public $eventimage;
	public $eventfull;
	public $eventshow;
	public $eventrecur;
	public $eventpriority;
	public $eventid;
	
    public function getInfo($eventtitle, $eventsubtitle, $eventdescription, $eventstartDate, $eventendDate, $eventctatext, $eventctaurl, $eventfullday, $eventvenue, $eventimage, $eventfull, $eventshow,$eventrecur,$eventpriority,$eventid)
    {
        $this->eventtitle = $eventtitle;
		$this->eventsubtitle = $eventsubtitle;
        $this->eventdescription = $eventdescription;
        $this->eventstartDate = $eventstartDate;
        $this->eventendDate = $eventendDate;
		$this->eventctatext = $eventctatext;
        $this->eventctaurl = $eventctaurl;
        $this->eventfullday = $eventfullday;
		$this->eventvenue = $eventvenue;
		$this->eventimage = $eventimage;
		$this->eventfull = $eventfull;
		$this->eventshow  = $eventshow;
		$this->eventrecur  = $eventrecur;
		$this->eventpriority  = $eventpriority;
		$this->eventid = $eventid;
    }

    public function createFile()
    {
        $folder = PATH_CONTENT . 'lff-events/events/';

        $event = [];
		$event['eventstart'] = $this->eventstartDate;
		$event['eventid'] = $this->eventid;
        $event['eventtitle'] = $this->eventtitle;
		$event['eventsubtitle'] = $this->eventsubtitle;
        $event['eventdescription'] = $this->eventdescription;
        $event['eventend'] = $this->eventendDate;
		$event['eventctatext'] = $this->eventctatext;
		//$event['ctaurl'] = $this->ctaurl;
        $event['eventfullday'] = $this->eventfullday;
		$event['eventvenue'] = $this->eventvenue;
		$event['eventimage'] = $this->eventimage;
		$event['eventshow'] = $this->eventshow;
		$event['eventrecur'] = $this->eventrecur;
		$event['eventfull'] = $this->eventfull;
		$event['eventpriority'] = $this->eventpriority;
		
        if ($this->eventctaurl !== '') {
            $event['eventctaurl'] =  $this->eventctaurl;
        };

        if (!file_exists($folder)) {
            mkdir($folder, 0755);
            file_put_contents($folder . '.htaccess', 'Allow from all');
        }

		$filename = preg_replace( '/[^a-z0-9]+/', '-', strtolower( $this->eventtitle ) );
        // Get timedate
        $timedate = date("Y_m_d_G_I_s");
        $titleWithDate = $filename .'_'. $timedate;

        if (isset($_GET['edit'])) {
            global $SITEURL;
            global $GSADMIN;

            if (file_exists($folder . $_GET['edit'] . '.json')) {
                unlink($folder . $_GET['edit'] . '.json');
            };

            file_put_contents($folder . $_GET['edit'] . '.json', json_encode($event));
            echo ("<meta http-equiv='refresh' content='0;url=" . DOMAIN_ADMIN . "plugin/lffevents?addevent&edit=" . $_GET['edit'] . "'>");
        } else {
            global $SITEURL;
            global $GSADMIN;
            file_put_contents($folder . $titleWithDate . '.json', json_encode($event));
            echo ("<meta http-equiv='refresh' content='0;url=" . DOMAIN_ADMIN . "plugin/lffevents?addevent&edit=" . $titleWithDate . "'>");
        }
    }
};

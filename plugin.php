<?php
class lffEvents extends Plugin
{
    public function init()
    {
        $this->dbFields = array(
            "locale" => "",
            "initialView" => "",
            "firstday" => "",
            "textColor" => "",
            "backgroundColor" => "",
            "backgroundColor" => "",
            "headershow" => "",
			"venuecategories" => "",
			"highlightcategories" => "",
			"imagecategories" => "",
			"servicecategories" => ""
        );
    }

    public function form()
    {
        include($this->phpPath() . 'PHP/view/settings.view.php');
    }

    public function adminController()
    {
        include($this->phpPath() . 'PHP/class/lffevent.class.php');

        if (isset($_POST['create-event'])) {
            $createEvent = new LFFEvent();
            $createEvent->getInfo($_POST['eventtitle'],$_POST['eventsubtitle'], $_POST['eventdescription'], $_POST['eventstart'], $_POST['eventend'],$_POST['eventctatext'],  $_POST['eventctaurl'], $_POST['longevent'],$_POST['eventvenue'],$_POST['eventimage'],$_POST['eventfull'],$_POST['eventshow'],$_POST['eventrecur'],$_POST['eventpriority'],$_POST['eventid']);
            $createEvent->createFile();
			$createJson  = new LFFJSON();
			$createJson->updateJSON();
        }

        if (isset($_GET['deleteevent'])) {
            unlink(PATH_CONTENT . 'lff-events/events/' . $_GET['deleteevent'] . '.json');
			$createJson  = new LFFJSON();
			$createJson->updateJSON();
            echo ("<meta http-equiv='refresh' content='0;url=" . DOMAIN_ADMIN . "plugin/lffevents'>");
        };
		
		if (isset($_POST['create-highlight'])) {
            $createHighlight = new LFFHighlight();
            $createHighlight->getInfo($_POST['highlighttitle'],$_POST['highlightsubtitle'], $_POST['highlightdescription'], $_POST['highlightstart'], $_POST['highlightend'],$_POST['highlightctatext'], $_POST['highlightctaurl'], $_POST['highlightvenue'],$_POST['highlightshow'],$_POST['highlightpriority'],$_POST['highlightoffercode'],$_POST['highlightcategory'], $_POST['highlightid']);
            $createHighlight->createFile();
			$createJson  = new LFFJSON();
			$createJson->updateJSON();
			echo ("<meta http-equiv='refresh' content='0;url=" . DOMAIN_ADMIN . "plugin/lffevents?listhighlights'>");
        }

        if (isset($_GET['deletehighlight'])) {
            unlink(PATH_CONTENT . 'lff-events/highlights/' . $_GET['deletehighlight'] . '.json');
			$createJson  = new LFFJSON();
			$createJson->updateJSON();
            echo ("<meta http-equiv='refresh' content='0;url=" . DOMAIN_ADMIN . "plugin/lffevents?listhighlights'>");
        };
		
		if (isset($_POST['create-venue'])) {
            $createVenue = new LFFVenue();
            $createVenue->getInfo($_POST['venuename'],$_POST['venuecategory'], $_POST['venueaddress'], $_POST['venuedescription'], $_POST['venueimage'],$_POST['venuegps'],  $_POST['venuewebsite'], $_POST['venueinstagram'],$_POST['venuefacebook'],$_POST['venueshow'],$_POST['venuepriority'],$_POST['venuerecommended'],$_POST['venueid']);
            $createVenue->createFile();
			$createJson  = new LFFJSON();
			$createJson->updateJSON();
        }
		
        if (isset($_GET['deletevenue'])) {
            unlink(PATH_CONTENT . 'lff-events/venues/' . $_GET['delete'] . '.json');
			$createJson  = new LFFJSON();
			$createJson->updateJSON();
            echo ("<meta http-equiv='refresh' content='0;url=" . DOMAIN_ADMIN . "plugin/lffevents?listvenues'>");
        };
		
		if (isset($_POST['create-image'])) {
            $createImage = new LFFImage();
			$createImage->createFile();
			echo ("<meta http-equiv='refresh' content='0;url=" . DOMAIN_ADMIN . "plugin/lffevents?listimages'>");
        }
		
		if (isset($_POST['editimage'])) {
			error_log("ZZZZZZZZZZZZZZZZZZ!!!!!!!!!!!!!!!!!!!!!!!!");
			$sourceName = $_POST['sourcefilename'];
			$destName = $_POST['destfilename'];
			$imagecat = $_POST['imagecategory'];
			$jsonfileID = $_GET['edit'];
            $renameImage = new LFFImage();
			$renameImage->imageRename($sourceName,$destName,$jsonfileID,$imagecat);
			echo ("<meta http-equiv='refresh' content='0;url=" . DOMAIN_ADMIN . "plugin/lffevents?listimages'>");
        }
		
		if (isset($_GET['deleteimage'])) {
			error_log("deleting .. ".PATH_CONTENT . 'lff-events/images/'.$_GET['deleteimage']. '.json');
			$basename=explode(".",$_GET['deleteimage'])[0];
			$mainfile = $basename.'.webp';
			$tn600file = $basename.'_600tn.webp';
			$tnfile = $basename.'_tn.webp';
            unlink(PATH_CONTENT . 'lff-events/images/' . $_GET['deleteimage'] . '.json');
			unlink(PATH_CONTENT . 'lff-events/images/' . $tnfile);
			unlink(PATH_CONTENT . 'lff-events/images/' . $tn600file);
			unlink(PATH_CONTENT . 'lff-events/images/' . $mainfile);
			
            echo ("<meta http-equiv='refresh' content='0;url=" . DOMAIN_ADMIN . "plugin/lffevents?listimages'>");
        };
		
		if (isset($_POST['create-service'])) {
            $createService = new LFFService();
            $createService->getInfo($_POST['servicename'],$_POST['servicecategory'],$_POST['serviceaddress'],$_POST['servicedescription'],$_POST['serviceimage'],$_POST['servicegps'],$_POST['servicewebsite'],$_POST['serviceinstagram'],$_POST['servicefacebook'],$_POST['serviceshow'],$_POST['servicepriority'],$_POST['servicemessenger'],$_POST['serviceid']);
            $createService->createFile();
			$createJson  = new LFFJSON();
			$createJson->updateJSON();
        }

		if (isset($_GET['deleteservice'])) {
            unlink(PATH_CONTENT . 'lff-events/services/' . $_GET['deleteservice'] . '.json');
			$createJson  = new LFFJSON();
			$createJson->updateJSON();
            echo ("<meta http-equiv='refresh' content='0;url=" . DOMAIN_ADMIN . "plugin/lffevents?listservices'>");
        };
		
		if (isset($_POST['create-passcode'])) {
            $createPasscode = new LFFPasscode();
            $createPasscode->getInfo($_POST['passcodeid'],$_POST['passcodename'],$_POST['passcodevalue'],$_POST['passcodeexpires']);
			$createPasscode->createFile();
			$createJson  = new LFFJSON();
			$createJson->updateJSON();
        }

		if (isset($_GET['deletepasscode'])) {
            unlink(PATH_CONTENT . 'lff-events/passcodes/' . $_GET['deleteservice'] . '.json');
			$createJson  = new LFFJSON();
			$createJson->updateJSON();
            echo ("<meta http-equiv='refresh' content='0;url=" . DOMAIN_ADMIN . "plugin/lffevents?listpasscodes'>");
        };
    }

    public function adminView()
    {
        // Token for send forms in Bludit
        global $security;
        $tokenCSRF = $security->getTokenCSRF();

        if (isset($_GET['addevent'])) {
            include($this->phpPath() . 'PHP/view/newevent.view.php');
        } elseif (isset($_GET['settings'])) {
            include($this->phpPath() . 'PHP/view/settings.view.php');
        } elseif (isset($_GET['helpevent'])) {
            include($this->phpPath() . 'PHP/view/helpevents.php');
        } elseif (isset($_GET['listvenues'])) {
			include($this->phpPath() . 'PHP/view/venuelist.view.php');
		} elseif (isset($_GET['addvenue'])) {
			include($this->phpPath() .  'PHP/view/newvenue.view.php');
		} elseif (isset($_GET['listimages'])) {
			include($this->phpPath() . 'PHP/view/imagelist.view.php');
		} elseif (isset($_GET['addimage'])) {
			include($this->phpPath() .  'PHP/view/newimage.view.php');
		} elseif (isset($_GET['editimage'])) {
			include($this->phpPath() .  'PHP/view/editimage.view.php');
		} elseif (isset($_GET['listservices'])) {
			include($this->phpPath() . 'PHP/view/servicelist.view.php');
		} elseif (isset($_GET['addservice'])) {
			include($this->phpPath() .  'PHP/view/newservice.view.php');
		}	elseif (isset($_GET['listhighlights'])) {
			include($this->phpPath() . 'PHP/view/highlightlist.view.php');
		} elseif (isset($_GET['addhighlight'])) {
			include($this->phpPath() .  'PHP/view/newhighlight.view.php');
		}  elseif (isset($_GET['addpasscode'])) {
			include($this->phpPath() .  'PHP/view/newpasscode.view.php');
		}  elseif (isset($_GET['listpasscodes'])) {
			include($this->phpPath() .  'PHP/view/passcodelist.view.php');
		}  elseif (isset($_GET['helpeventcreate'])) {
			include($this->phpPath() .  'PHP/view/helpeventscreate.php');
		}  elseif (isset($_GET['helpvenuecreate'])) {
			include($this->phpPath() .  'PHP/view/helpvenuescreate.php');
		}  elseif (isset($_GET['helphighlightcreate'])) {
			include($this->phpPath() .  'PHP/view/helphighlightscreate.php');
		}
		else {
            include($this->phpPath() . 'PHP/view/eventlist.view.php');
        }
    }

 public function adminHead(){
    $html = $this->includeCSS('dropzone.min.css');
    return $html;
  }
  
  public function adminBodyEnd(){
	  $html = $this->includeJS('dropzone.min.js');
	  //var_dump($this);
	  //url: "PHP/uploadimage.php"
	  $html .= '<script></script>';
	  //Dropzone.autoDiscover = false; $("#dropzone").dropzone();	  
	  return $html;
  }
  
    public function adminSidebar()
    {
        $pluginName = Text::lowercase(__CLASS__);
        $url = HTML_PATH_ADMIN_ROOT . 'plugin/' . $pluginName;
		$html = '<hr><div class="navbar-brand">App Admin</div>';
        $html .= '<ul><li><a id="Events" class="nav-link" href="' . $url . '">Events</a></li>';
		$html .= '<li><a id="Highlights" class="nav-link" href="' . $url . '?listhighlights">Highlights</a></li>';
        $html .= '<li><a id="Venues" class="nav-link" href="' . $url . '?listvenues">Venues</a></li>';
		$html .= '<li><a id="Images" class="nav-link" href="' . $url . '?listimages">Images</a></li>';
		$html .= '<li><a id="Services" class="nav-link" href="' . $url . '?listservices">Services</a></li>';
		$html .= '<li><a id="Passcodes" class="nav-link" href="' . $url . '?listpasscodes">Passcodes</a></li>';
		$html .= '<li><a class="nav-link" href="'. DOMAIN_ADMIN .'configure-plugin/lffEvents">Settings ⚙️</a></li>';
        $html .= '</ul>';
	return $html;
    }


    public function siteHead()
    {
    }


    public function showCalendar($ce = '')
    {
       
    }



    public function pageBegin()
    {

    }
}


function showEventCalendar()
{
    $cal = new LFFEvents();
    echo $cal->showCalendar();
}

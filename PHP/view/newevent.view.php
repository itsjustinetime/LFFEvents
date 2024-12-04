<style>
	.btn {
		display: inline-flex;
		padding: 5px 10px;
		border: solid 1px #000;
		background: #000;
		color: #fff !important;
		text-decoration: none !important;
	}

	.current-event-form {
		margin-top: 10px;
		background: #fafafa;
		border: solid 1px #ddd;
		padding: 10px;
	}

	.current-event-form :is(input, select) {
		width: 100%;
		padding: 5px;
		margin: 10px 0;
	}

	.current-event-form select {
		background: #fff;
		border: solid 1px #000;
	}

	.current-event-form textarea {
		width: 100%;
		height: 150px;
		margin-bottom: 10px;
	}

	.current-event-form label {
		padding: 5px 0;
	}

	.submit-event {
		background: #000;
		color: #fff;
		border: solid 1px;
		padding: 10px 15px;
	}

	.no-italics {
		font-style: normal;
	}
	.imagepicker {
		display: flex;
		flex-wrap:wrap;
	}
	.imageitem {
		width:15vw;
		padding:2vw;
	}
	.adminthumb {
		width: 14vw;
	}
	.imgname {
		text-align:center;
	}

 .checkbox-wrapper-22 .switch {
    display: inline-block;
    height: 24px;
    position: relative;
    width: 45px;
  }

  .checkbox-wrapper-22 .switch input {
    display:none;
  }

  .checkbox-wrapper-22 .slider {
    background-color: #ccc;
    bottom: 0;
    cursor: pointer;
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
    transition: .4s;
  }

  .checkbox-wrapper-22 .slider:before {
    background-color: #fff;
    bottom: 2px;
    content: "";
    height: 20px;
    left: 2px;
    position: absolute;
    transition: .4s;
    width: 20px;
  }

  .checkbox-wrapper-22 input:checked + .slider {
    background-color: #dd00dd;
  }

  .checkbox-wrapper-22 input:checked + .slider:before {
    transform: translateX(21px);
  }

  .checkbox-wrapper-22 .slider.round {
    border-radius: 34px;
  }

  .checkbox-wrapper-22 .slider.round:before {
    border-radius: 50%;
  }
  
  @media only screen and (max-width: 450px) {
  .imageitem {
    width:40vw;
	margin-right:2vw;
  }

  .adminthumb {
	  width: 40vw;
  }
}
</style>



<h3><span class="no-italics">📅</span> LFF Events - <?php if (isset($_GET['edit'])) {?>Edit<?php } else {?>Add New<?php } ?></h3>
<a href="<?php echo DOMAIN_ADMIN; ?>/plugin/lffevents" class="btn">Back to list</a>

<?php
$fileSet = PATH_CONTENT . 'lff-events/settings/settings.json';
?>

<?php
if (isset($_GET['edit'])) {
	$file = file_get_contents(PATH_CONTENT . 'lff-events/events/' . $_GET['edit'] . '.json');
	$fileJS = json_decode($file);
};
?>

<?php 
// Get all the venues
$venuenames=[];
if (!empty(glob(PATH_CONTENT . 'lff-events/venues/*.json'))) {
        foreach (glob(PATH_CONTENT . 'lff-events/venues/*.json') as $key => $file) {
            $data = json_decode(file_get_contents($file));
			$venuename = $data->venuename;
			array_push($venuenames,$venuename);
		}
}

$imagelist=[];
// Get all the images for category 'event'
if (!empty(glob(PATH_CONTENT . 'lff-events/images/*.json'))) {
        foreach (glob(PATH_CONTENT . 'lff-events/images/*.json') as $key => $file) {
            $data = json_decode(file_get_contents($file));
			$imagefilename = $data->imagefilename;
			if ($data->imagecategory == "event") {	array_push($imagelist,$imagefilename); }
		}
}

?>

<form method="post" class="current-event-form">
<input type="hidden" id="jstokenCSRF" name="tokenCSRF" value="<?php echo $tokenCSRF;?>">
<input type="hidden" name="eventid" 
<?php 
	if (isset($_GET['edit'])) {
								if ($fileJS->eventid) echo 'value="'.$fileJS->eventid .'"';  else echo 'value="'.uniqid() .'"';
	} else echo 'value="'.uniqid() .'"';
	
	?>> 
	<label for="eventtit">Title</label>
	<input type="text" id="eventtit" name="eventtitle" required <?php if (isset($_GET['edit'])) {
																echo 'value="' . $fileJS->eventtitle . '"';
															}; ?>>
	<label for="eventsub">Subtitle</label>
	<input type="text" id="eventsub" name="eventsubtitle" required <?php if (isset($_GET['edit'])) {
																echo 'value="' . $fileJS->eventsubtitle . '"';
															}; ?>>															
	<label for="eventdesc">Event Description </label>
	<textarea id="eventdesc" name="eventdescription">
<?php if (isset($_GET['edit'])) {
	echo $fileJS->eventdescription;
}; ?>
</textarea>

	<label for="venuesel">Venue</label>
	<select name="eventvenue" id="venuesel"> <?php
	foreach ($venuenames as $venuename) {
	echo '<option value="'.$venuename.'" ' ; 
	  if (isset($_GET['edit'])) {
			if ($venuename == $fileJS->eventvenue) { echo 'selected'; }
	  }
	echo '>'.$venuename.'</option>';
	}
	?>
	</select>
	
	<select style="display:none;" id="disptype" name="longevent" class="longevent">
		<option value="time">Time</option>
		<option value="fullday">Full Day</option>
	</select>

	<label for="startbox">Event Start (Required)</label>
	<input type="<?php

					if (isset($fileJS->fullday)  && $fileJS->fullday == 'fullday') {
						echo 'date';
					} else {
						echo 'datetime-local';
					};
					?>" name="eventstart" id="startbox" class="startdate" required <?php if (isset($_GET['edit'])) {
																			echo 'value="' . $fileJS->eventstart . '"';
																		}; ?>>
	<label for="endbox">Event End (Required)</label>
	<input type="<?php

					if (isset($fileJS->fullday)  && $fileJS->fullday == 'fullday') {
						echo 'date';
					} else {
						echo 'datetime-local';
					};
					?>" name="eventend" id="endbox" class="enddate" required <?php if (isset($_GET['edit'])) {
																		echo 'value="' . $fileJS->eventend . '"';
																	}; ?>>
	<label for="imagesel">Image</label>
	<?php if (isset($_GET['edit'])) { ?> <div class="pic"><img class="adminthumb" src="<?php echo HTML_PATH_ROOT.'/bl-content/lff-events/images/'.$fileJS->eventimage.'">'; ?></div><?php } ?>
	<div class="imagepicker" id="imagepickbox" style="display:none">
	<?php
	foreach ($imagelist as $imagefilename) {
		echo '<div class="imageitem">';
		echo '<div class="pic">';
	echo '<img class="adminthumb" name="'.$imagefilename.'" src="'.HTML_PATH_ROOT.'/bl-content/lff-events/images/'.$imagefilename.'" />'; 
	echo '</div>';
	echo '<div class="imgname">';
	echo explode('.',$imagefilename)[0];
	echo '</div>';
	echo '</div>';
	}
	
	?>
	</div>
	
	<input type="text" name="eventimage" id="eventimagesel" <?php if (isset($_GET['edit'])) { echo 'value="'.@$fileJS->eventimage.'"'; }; ?> 
	>
	<label for="priorityselector">Priority</label>
	<select id="priorityselector" name="eventpriority">
<?php for ( $x = 1; $x <=6; $x++) { ?>
								<option value="<?php echo $x; ?>"<?php if (isset($_GET['edit'])) {
								if ($fileJS->eventpriority == $x) { echo " selected";}  } ?>><?php echo $x; if ($x==1) echo ' highest'; ?></option>
								<?php } ?>
	</select>
	<label for="ctatextbox">CTA Text</label>
	<input type="text" name="eventctatext" id="ctatextbox" <?php if (isset($_GET['edit'])) {
													echo 'value="' . @$fileJS->eventctatext . '"';
												}; ?>>
												
	<label for="ctaurlbox">CTA Link</label>
	<input type="text" name="eventctaurl" id="ctaurlbox" <?php if (isset($_GET['edit'])) {
													echo 'value="' . @$fileJS->eventctaurl . '"';
												}; ?>>
	<div class="tickboxes" style="display:flex;flex-wrap:wrap; margin:2vh;">
	<div class="tickbox col" style="display:flex;">Show?
		<div class="checkbox-wrapper-22 col">
			<label for="enabledbox" class="switch">
			<input type="checkbox" class="col" id="enabledbox" name="eventshow" <?php if (isset($_GET['edit'])) {
		if (@$fileJS->eventshow == 'on') {echo 'checked'; }
	}; ?>> 
		<div class="slider round"></div>
		</label></div>
	</div>
	
	<div class="tickbox col" style="display:flex;">Recurring?
		<div class="checkbox-wrapper-22">
			<label for="recurbox" class="switch">
			<input type="checkbox" class="col" id="recurbox" name="eventrecur" <?php if (isset($_GET['edit'])) {
															if (@$fileJS->eventrecur == 'on') {echo 'checked'; }
			}; ?>>
			<div class="slider round"></div>
			</label>	
		</div>
	</div>
	<div class="tickbox col" style="display:flex;">Fully booked?
		<div class="checkbox-wrapper-22">
			<label for="fullbox" class="switch">
			<input type="checkbox" class="col" id="fullbox" name="eventfull" <?php if (isset($_GET['edit'])) {
															if (@$fileJS->eventfull == 'on') {echo 'checked'; };
			}; ?>>
			<div class="slider round"></div>
			</label>
		</div>
	</div>	
	</div>
	<button type="submit" name="create-event" class="submit-event">Save Event</button>
</form>

<script>
	document.querySelector('.longevent').addEventListener('click', () => {

		if (document.querySelector('.longevent').value == 'fullday') {
			document.querySelector('.startdate').setAttribute('type', 'date');
			document.querySelector('.enddate').setAttribute('type', 'date');
		} else {
			document.querySelector('.startdate').setAttribute('type', 'datetime-local');
			document.querySelector('.enddate').setAttribute('type', 'datetime-local');
		}

	});
	
	
	$(".adminthumb").click(function() {

	$('#eventimagesel').attr('value',$(this).attr('name'));
	$('#imagepickbox').slideUp();

	});
	
	$("#eventimagesel").click(function() {
		$('#imagepickbox').style="display:flex;";
		$('#imagepickbox').slideDown();
	});
	
</script>


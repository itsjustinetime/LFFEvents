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



<h3><span class="no-italics">📅</span> LFF Venues - <?php if (isset($_GET['edit'])) {?>Edit<?php } else {?>Add New<?php } ?> </h3>
<a href="<?php echo DOMAIN_ADMIN; ?>/plugin/lffevents?listvenues" class="btn">Back to list</a>

<?php
$fileSet = PATH_CONTENT . 'lff-events/settings/settings.json';

if (isset($_GET['edit'])) {
	$file = file_get_contents(PATH_CONTENT . 'lff-events/venues/' . $_GET['edit'] . '.json');
	$fileJS = json_decode($file);
};

 $venueCategories=explode(",",$this->getValue('venuecategories'));

$imagelist=[];
// Get all the imagesa
if (!empty(glob(PATH_CONTENT . 'lff-events/images/*.json'))) {
        foreach (glob(PATH_CONTENT . 'lff-events/images/*.json') as $key => $file) {
            $data = json_decode(file_get_contents($file));
			$imagefilename = $data->imagefilename;
			if ($data->imagecategory == 'venue') { array_push($imagelist,$imagefilename); }
		}
}

?>

<form method="post" class="current-event-form">
<input type="hidden" id="jstokenCSRF" name="tokenCSRF" value="<?php echo $tokenCSRF;?>">
<input type="hidden" name="venueid" 
<?php 
	if (isset($_GET['edit'])) {
								if ($fileJS->venueid) echo 'value="'.$fileJS->venueid .'"';  else echo 'value="'.uniqid() .'"';
	} else echo 'value="'.uniqid() .'"';
	
	?>> 
	<label for="namebox">Name</label>
	<input type="text" name="venuename" id="namebox" required <?php if (isset($_GET['edit'])) {
																echo 'value="' . $fileJS->venuename . '"';
															}; ?>>
	<label for="venuecatbox">Category</label>
	<select name="venuecategory" id="venuecatbox"> <?php
	foreach ($venueCategories as $venuecat) {
	echo '<option value="'.$venuecat.'" ' ; 
	  if (isset($_GET['edit'])) {
			if ($venuecat == $fileJS->venuecategory) { echo 'selected'; }
	  }
	echo '>'.$venuecat.'</option>';
	}
	?>
	</select>
	<label for="priorityselector">Priority</label>
	<select id="priorityselector" name="venuepriority">
<?php for ( $x = 1; $x <=10; $x++) { ?>
								<option value="<?php echo $x; ?>"<?php if (isset($_GET['edit'])) {
if ($fileJS->venuepriority == $x) { echo " selected";}  } else if ($x == 5) {echo " selected";}?>><?php echo $x; if ($x==1) echo ' highest'; ?></option>
								<?php } ?>
	</select>
	<label for="addressbox">Address</label>
	<input type="text" name="venueaddress" id="addressbox" required <?php if (isset($_GET['edit'])) {
																echo 'value="' . $fileJS->venueaddress . '"';
															}; ?>>
	<label for="gpsbox">GPS</label>
	<input type="text" name="venuegps" id="gpsbox" <?php if (isset($_GET['edit'])) {
													echo 'value="' . @$fileJS->venuegps . '"';
												}; ?>>
												
	<label for="vdesc">Venue Description </label>
	<textarea name="venuedescription" id="vdesc">
<?php if (isset($_GET['edit'])) {
	echo $fileJS->venuedescription;
}; ?>
</textarea>

	<label for="imagesel">Image</label>
	<?php if (isset($_GET['edit'])) { ?> <div class="pic"><img class="adminthumb" src="<?php echo HTML_PATH_ROOT.'/bl-content/lff-events/images/'.$fileJS->venueimage.'">'; ?></div><?php } ?>
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
		<input type="text" name="venueimage" id="eventimageselect" autocomplete="off" <?php if (isset($_GET['edit'])) { echo 'value="'.@$fileJS->venueimage.'"'; }; ?> 
	>
												
	<label for="websitebox">Website</label>
	<input type="text" name="venuewebsite" id="websitebox"<?php if (isset($_GET['edit'])) {
													echo 'value="' . @$fileJS->venuewebsite . '"';
												}; ?>>
												
	<label for="instabox">Instagram</label>
	<input type="text" name="venueinstagram" id="instabox" <?php if (isset($_GET['edit'])) {
													echo 'value="' . @$fileJS->venueinstagram . '"';
												}; ?>>
												
	<label for="fbbox">Facebook</label>											
	<input type="text" name="venuefacebook" id="fbbox" <?php if (isset($_GET['edit'])) {
													echo 'value="' . @$fileJS->venuefacebook . '"';
												}; ?>>
												
	<div class="venueshow" style="display:flex;">
	<label for="rectog" class="label">Recommended?</label>
	<input type="checkbox" class="toggle" id="rectog" name="venuerecommended" 
		<?php if (isset($_GET['edit'])) {
											if (@$fileJS->venuerecommended == 'on') {echo 'checked'; }
	}; ?>> 
	</div>
	
	<div class="venueshow" style="display:flex;">
	<label for="showtog" class="label">Show?</label>
	<input type="checkbox" class="toggle" id="showtog" name="venueshow" 
		<?php if (isset($_GET['edit'])) {
											if (@$fileJS->venueshow == 'on') {echo 'checked'; }
	}; ?>> 
	</div>
						
	<button type="submit" name="create-venue" class="submit-event">Save Venue</button>
</form>

<script>
	$(".adminthumb").click(function() {

	$('#eventimageselect').attr('value',$(this).attr('name'));
	$('#imagepickbox').slideUp();

	});
	
	$("#eventimageselect").click(function() {
		$('#imagepickbox').style="display:flex;";
		$('#imagepickbox').slideDown();
	});

</script>



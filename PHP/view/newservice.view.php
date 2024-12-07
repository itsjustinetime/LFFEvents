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



<h3><span class="no-italics">📅</span>Services - <?php if (isset($_GET['edit'])) {?>Edit Service <?php } else {?>Add New Service<?php } ?> </h3>
<a href="<?php echo DOMAIN_ADMIN; ?>/plugin/lffevents?listservices" class="btn">Back to list</a>

<?php
$fileSet = PATH_CONTENT . 'lff-events/settings/settings.json';
?>

<?php
if (isset($_GET['edit'])) {
	$file = file_get_contents(PATH_CONTENT . 'lff-events/services/' . $_GET['edit'] . '.json');
	$fileJS = json_decode($file);
};
?>

<?php 

 $serviceCategories=explode(",",$this->getValue('servicecategories'));

//$cats = file_get_contents(PATH_PLUGINS.'/LFFEvents/lffevents.json');
//$catJS = json_decode($cats);
//var_dump($fileJS);
//$serviceCategories = $catJS->servicecategories;
//var_dump($venueCategories);

$imagelist=[];
// Get all the images for services
if (!empty(glob(PATH_CONTENT . 'lff-events/images/*.json'))) {
        foreach (glob(PATH_CONTENT . 'lff-events/images/*.json') as $key => $file) {
            $data = json_decode(file_get_contents($file));
			$imagefilename = $data->imagefilename;
			if ($data->imagecategory == "service") { array_push($imagelist,$imagefilename); }
		}
}
?>

<form method="post" class="current-event-form">
<input type="hidden" id="jstokenCSRF" name="tokenCSRF" value="<?php echo $tokenCSRF;?>">
<input type="hidden" name="serviceid" 
<?php 
	if (isset($_GET['edit'])) {
								if ($fileJS->serviceid) echo 'value="'.$fileJS->serviceid .'"';  else echo 'value="'.uniqid() .'"';
	} else echo 'value="'.uniqid() .'"';
	
	?>> 
	<label for="nm">Name</label>
	<input type="text" id="nm" name="servicename" required <?php if (isset($_GET['edit'])) {
																echo 'value="' . $fileJS->servicename . '"';
															}; ?>>
	<label for="catpic">Category</label>
	<select id="catpic" name="servicecategory"> <?php
	
	foreach ($serviceCategories as $servicecat) {
	echo '<option value="'.$servicecat.'" ';
	if (isset($_GET['edit'])) {
		if ($servicecat == $fileJS->servicecategory) { echo 'selected'; }
	}
	 echo '>'.$servicecat.'</option>';
	}
	?>
	</select>

	<label for="addr">Address</label>
	<input type="text" id="addr" name="serviceaddress" <?php if (isset($_GET['edit'])) {
																echo 'value="' . $fileJS->serviceaddress . '"';
															}; ?>>
	<label for="gps">GPS</label>
	<input type="text" id="gps" name="servicegps" <?php if (isset($_GET['edit'])) {
													echo 'value="' . @$fileJS->servicegps . '"';
												}; ?>>
												
	<label for="desc">Service Description </label>
	<textarea id="desc" name="servicedescription">
<?php if (isset($_GET['edit'])) {
	echo $fileJS->servicedescription;
}; ?>
</textarea>

	<label for="imagesel">Image</label>
	<?php if (isset($_GET['edit'])) { ?> <div class="pic"><img class="adminthumb" src="<?php echo HTML_PATH_ROOT.'/bl-content/lff-events/images/'.$fileJS->serviceimage.'">'; ?></div><?php } ?>
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
	<input type="text" name="serviceimage" id="eventimageselect" autocomplete="off" <?php if (isset($_GET['edit'])) { echo 'value="'.@$fileJS->serviceimage.'"'; }; ?> 

												
	<label for="www">Website</label>
	<input type="text" id="www" name="servicewebsite" <?php if (isset($_GET['edit'])) {
													echo 'value="' . @$fileJS->servicewebsite . '"';
												}; ?>>
												
	<label for="insta">Instagram</label>
	<input type="text" id="insta" name="serviceinstagram" <?php if (isset($_GET['edit'])) {
													echo 'value="' . @$fileJS->serviceinstagram . '"';
												}; ?>>
												
	<label for="fb">Facebook</label>											
	<input type="text" id="fb" name="servicefacebook" <?php if (isset($_GET['edit'])) {
													echo 'value="' . @$fileJS->servicefacebook . '"';
												}; ?>>
	<label for="msgr">Messenger</label>											
	<input type="text" id="msgr" name="servicemessenger" <?php if (isset($_GET['edit'])) {
													echo 'value="' . @$fileJS->servicemessenger . '"';
												}; ?>>
												
	<label for="priorityselector">Priority</label>
	<select id="priorityselector" name="servicepriority">
<?php for ( $x = 1; $x <=6; $x++) { ?>
								<option value="<?php echo $x; ?>"<?php if (isset($_GET['edit'])) {
								if ($fileJS->servicepriority == $x) { echo " selected";}  } ?>><?php echo $x; if ($x==1) echo ' highest'; ?></option>
								<?php } ?>
	</select>											
	<div class="serviceshow" style="display:flex;">
	<label for="serviceshow" class="label">Show?</label>
	<input type="checkbox" class="toggle" name="serviceshow" <?php if (isset($_GET['edit'])) {
													if (@$fileJS->serviceshow == 'on') {echo 'checked'; }
	}; ?>>
	</div>
						
	<button type="submit" name="create-service" class="submit-event">Save Service</button>
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


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

 .checkbox-wrapper-22 .switch {
    display: inline-block;
    height: 34px;
    position: relative;
    width: 60px;
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
    bottom: 4px;
    content: "";
    height: 26px;
    left: 4px;
    position: absolute;
    transition: .4s;
    width: 26px;
  }

  .checkbox-wrapper-22 input:checked + .slider {
    background-color: #dd00dd;
  }

  .checkbox-wrapper-22 input:checked + .slider:before {
    transform: translateX(26px);
  }

  .checkbox-wrapper-22 .slider.round {
    border-radius: 34px;
  }

  .checkbox-wrapper-22 .slider.round:before {
    border-radius: 50%;
  }
</style>



<h3><span class="no-italics">📅</span>Highlights - <?php if (isset($_GET['edit'])) {?>Edit <?php } else {?>Add New<?php } ?> </h3>
<a href="<?php echo DOMAIN_ADMIN; ?>/plugin/lffevents?listhighlights" class="btn">Back to list</a>

<?php
$fileSet = PATH_CONTENT . 'lff-events/settings/settings.json';
?>

<?php
if (isset($_GET['edit'])) {
	$file = file_get_contents(PATH_CONTENT . 'lff-events/highlights/' . $_GET['edit'] . '.json');
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
echo $this->getValue('highlightcategories');
 $highlightCategories=explode(",",$this->getValue('highlightcategories'));

?>

<form method="post" class="current-event-form">
<input type="hidden" id="jstokenCSRF" name="tokenCSRF" value="<?php echo $tokenCSRF;?>">
<input type="hidden" name="highlightid" 
<?php 
	if (isset($_GET['edit'])) {
								if ($fileJS->highlightid) echo 'value="'.$fileJS->highlightid .'"';  else echo 'value="'.uniqid() .'"';
	} else echo 'value="'.uniqid() .'"';
	
	?>> 
	<label for="title">Title</label>
	<input type="text" id="title" name="highlighttitle" required <?php if (isset($_GET['edit'])) {
																echo 'value="' . $fileJS->highlighttitle . '"';
															}; ?>>
	<label for="subtitle">Subtitle</label>
	<input type="text" id="subtitle" name="highlightsubtitle" required <?php if (isset($_GET['edit'])) {
																echo 'value="' . $fileJS->highlightsubtitle . '"';
															}; ?>>
	<label for="highlightcategory">Category</label>
	<select name="highlightcategory"> <?php
	foreach ($highlightCategories as $highlightcat) {
	echo '<option value="'.$highlightcat.'">'.$highlightcat.'</option>';
	}
	?>
	</select>

	<label for="venuesel">Venue</label>
	<select name="highlightvenue" id="venuesel"> <?php
	foreach ($venuenames as $venuename) {
	echo '<option value="'.$venuename.'" ' ; 
	  if (isset($_GET['edit'])) {
			if ($venuename == $fileJS->highlightvenue) { echo 'selected'; }
	  }
	echo '>'.$venuename.'</option>';
	}
	?>
	</select>
												
	<label for="desc">Description </label>
	<textarea id="desc" name="highlightdescription">
<?php if (isset($_GET['edit'])) {
	echo $fileJS->highlightdescription;
}; ?>
</textarea>

	<label for="start">Start (Required)</label>
	<input id="start" type="datetime-local" name="highlightstart" step="900" class="startdate" required <?php if (isset($_GET['edit'])) {
																			echo 'value="' . $fileJS->highlightstart . '"';
																		}; ?>>
	<label for="end">End (Required)</label>
	<input id="end" type="datetime-local" name="highlightend" class="enddate" required <?php if (isset($_GET['edit'])) {
																		echo 'value="' . $fileJS->highlightend . '"';
																	}; ?>>
	<label for="priorityselector">Priority</label>
	<select id="priorityselector" name="highlightpriority">
	<?php for ( $x = 1; $x <=6; $x++) { ?>
								<option value="<?php echo $x; ?>"<?php if (isset($_GET['edit'])) {
								if ($fileJS->highlightpriority == $x) { echo " selected";}  } ?>><?php echo $x; if ($x==1) echo ' highest'; ?></option>
								<?php } ?>
	</select>											
																	
	<label for="ctatext">CTA Text</label>
	<input type="text" id="ctatext" name="highlightctatext" <?php if (isset($_GET['edit'])) {
													echo 'value="' . @$fileJS->highlightctatext . '"';
												}; ?>>
												
	<label for="ctaurl">CTA URL</label>
	<input type="text" id="ctaurl" name="highlightctaurl" <?php if (isset($_GET['edit'])) {
													echo 'value="' . @$fileJS->highlightctaurl . '"';
												}; ?>>
												
	<label for="offcode">Offer Code</label>
	<input type="text" id="offcode" name="highlightoffercode" <?php if (isset($_GET['edit'])) {
																echo 'value="' . $fileJS->highlightoffercode . '"';
															}; ?>>											
	<div class="tickboxes" style="margin:2vh;">
	<div class="tickbox col" style="display:flex;">Show?
		<div class="checkbox-wrapper-22">
			<label for="showtick" class="switch">
	<input type="checkbox" id="showtick" name="highlightshow" <?php if (isset($_GET['edit'])) {
													if (@$fileJS->highlightshow == 'on') {echo 'checked'; };
	}; ?>>
	<div class="slider round"></div>
	</label>
	
	</div>
	</div>
	</div>
						
	<button type="submit" name="create-highlight" class="submit-event">Save Highlight</button>
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
</script>


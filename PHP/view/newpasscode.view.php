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

</style>



<h3><span class="no-italics">📅</span>Passcodes - <?php if (isset($_GET['edit'])) {?>Edit Passcode <?php } else {?>Add New Passcode<?php } ?> </h3>
<a href="<?php echo DOMAIN_ADMIN; ?>/plugin/lffevents?listpasscodes" class="btn">Back to list</a>

<?php
$fileSet = PATH_CONTENT . 'lff-events/settings/settings.json';
?>

<?php
if (isset($_GET['edit'])) {
	$file = file_get_contents(PATH_CONTENT . 'lff-events/passcodes/' . $_GET['edit'] . '.json');
	$fileJS = json_decode($file);
};
?>

<?php 

//echo "<pre>"; print_r(get_defined_constants()); die;
$cats = file_get_contents(PATH_PLUGINS.'/LFFEvents/lffevents.json');
$catJS = json_decode($cats);

?>

<form method="post" class="current-event-form">
<input type="hidden" id="jstokenCSRF" name="tokenCSRF" value="<?php echo $tokenCSRF;?>">
<input type="hidden" name="passcodeid" 
<?php 
	if (isset($_GET['edit'])) {
								if ($fileJS->passcodeid) echo 'value="'.$fileJS->passcodeid .'"';  else echo 'value="'.uniqid() .'"';
	} else echo 'value="'.uniqid() .'"';
	
	?>> 
	<label for="nm">Name</label>
	<input type="text" id="nm" name="passcodename" required <?php if (isset($_GET['edit'])) {
																echo 'value="' . $fileJS->passcodename . '"';
															}; ?>>

	<label for="val">Value</label>
	<input type="text" id="val" name="passcodevalue" required <?php if (isset($_GET['edit'])) {
																echo 'value="' . $fileJS->passcodevalue . '"';
															}; ?> onchange="upperCase()">
	<label for="expires">Expiry Date (Required)</label>
	<input id="expires" type="text" name="passcodeexpires" class="startdate" required <?php if (isset($_GET['edit'])) {
																			echo 'value="' . $fileJS->passcodeexpires . '"';
																		}; ?>>
						
	<button type="submit" name="create-passcode" class="submit-event">Save Passcode</button>
</form>

<script>

function upperCase() {
  const x = document.getElementById("val");
  x.value = x.value.toUpperCase();
}
	
	jQuery('#expires').datetimepicker(
	{step:30,
	format:"Y-m-d H:i"
	}
	);
</script>


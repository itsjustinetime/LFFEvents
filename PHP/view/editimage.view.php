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

	.editimage {
		width: 50vw;
	}
</style>



<h3><span class="no-italics">📅</span>Edit Image</h3>
<a href="<?php echo DOMAIN_ADMIN; ?>/plugin/lffevents?listimages" class="btn">Back to list</a>

<?php
$fileSet = PATH_CONTENT . 'lff-events/settings/settings.json';
?>

<?php
if (isset($_GET['edit'])) {
	$file = file_get_contents(PATH_CONTENT . 'lff-events/images/' . $_GET['edit'] . '.json');
	$fileJS = json_decode($file);
};
?>

<?php 
$cats = file_get_contents(PATH_PLUGINS.'/LFFEvents/lffevents.json');
$catJS = json_decode($cats);
$imageCategories = $catJS->imagecategories;

?>

<form method="post" class="current-event-form">
<input type="hidden" id="jstokenCSRF" name="tokenCSRF" value="<?php echo $tokenCSRF;?>">
<input type="hidden" id="origfilename" name="sourcefilename" value="<?php echo explode('.',$fileJS->imagefilename)[0]; ?>">
<input type="text" id="text" name="destfilename" value="<?php echo explode('.',$fileJS->imagefilename)[0]; ?>">
<?php $filename=HTML_PATH_ROOT.'/bl-content/lff-events/images/'.$fileJS->imagefilename; ?>
<div class="imagebox"><img class="editimage" src="<?php echo $filename; ?>" ></div>
	<label for="catbox">Category</label>
	<select name="imagecategory" id="catbox"> <?php
	
	foreach ($imageCategories as $imagecat) {
	echo '<option value="'.$imagecat.'" ';
	if (isset($_GET['edit'])) {
		if ($imagecat == $fileJS->imagecategory) { echo 'selected'; }
	}
	 echo '>'.$imagecat.'</option>';
	}
	?>
	</select>

	<button type="submit" name="editimage" class="submit-event">Save</button>
</form>

<script>

</script>


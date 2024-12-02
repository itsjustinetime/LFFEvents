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

</style>



<h3><span class="no-italics">📅</span> LFF Images - Add New Image</h3>
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

 $imageCategories=explode(",",$this->getValue('imagecategories'));

?>
<div id="dz">
<form style="padding-bottom:50vh;" action="lffevents?addimage" method="post" class="current-event-form dropzone" id="dropzone" enctype="multipart/form-data">
<input type="hidden" id="jstokenCSRF" name="tokenCSRF" value="<?php echo $tokenCSRF;?>">

	<label for="namebox">FileName</label>
	
	<input type="file" id="namebox" name="file">
	<label for="catbox">Category</label>
	<select id="catbox" name="imagecategory"> <?php
	foreach ($imageCategories as $imagecat) {
	echo '<option value="'.$imagecat.'">'.$imagecat.'</option>';
	}
	?>
	</select>

	<button type="submit" name="create-image" class="submit-event">Upload</button>
</form>
</div>
<script>

</script>


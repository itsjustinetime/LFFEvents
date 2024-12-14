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
    <label>Drag images onto the page to upload them to the selected category, or use the file chooser & click 'Upload'.</label>
	
	<label for="catbox">Category</label>
	<select id="catbox" name="imagecategory"> <?php
	foreach ($imageCategories as $imagecat) {
	echo '<option value="'.$imagecat.'">'.$imagecat.'</option>';
	}
	?>
	</select>
	<div id="manualupload">
		<label for="namebox">FileName</label>
		<input type="file" id="namebox" name="file">
		<button type="submit" name="create-image" class="submit-event">Upload</button>
	</div>
	<div id="dragsuccess" style="margin-top:2vh; text-align:center; padding:.5em; border-radius:10px; background-color:#0f0; color:white; font-size:2em; opacity:0; transition:all 500ms;">Uploaded successfully</div>
</form>
</div>

<?php echo $this->includeJS('dropzone.min.js');  ?>

<script>
Dropzone.options.dropzone = {
    success: function(file, response){
		document.getElementById('manualupload').style="display:none";
		document.getElementById('dragsuccess').style.opacity=1;
		setTimeout(function() { document.getElementById('dragsuccess').style.opacity=0; }, 1200);
    }
};

$('#dropzone').on('success', function() {
  var args = Array.prototype.slice.call(arguments);

  // Look at the output in you browser console, if there is something interesting
  console.log(args);
});

</script>


<style>
	.btn {
		display: inline-flex;
		padding: 5px 10px;
		border: solid 1px #000;
		background: #000;
		color: #fff !important;
		text-decoration: none !important;
	}

	.btn-sm {
		display: inline-flex;
		padding: 5px 10px;
		border: solid 1px #000;
		background: #000;
		color: #fff !important;
		text-decoration: none !important;
		margin: 0 3px;
		text-align: center;
	}

	.btn-sm-red {
		background: red;
		border: red;
	}

	.form-settings {
		background: #fafafa;
		border: solid 1px #ddd;
		padding: 10px;
		margin-top: 10px;
	}

	.form-settings :is(select, input) {
		width: 100%;
		padding: 5px;
		border-radius: 0;
		border: solid 1px #ddd;
		background: #fff;
		margin: 10px 0;
	}

	.form-settings .create-settings {
		display: inline-flex;
		padding: 5px 10px;
		border: solid 1px #000;
		background: #000;
		color: #fff !important;
		text-decoration: none !important;
		width: 200px;
		margin-top: 10px;
	}

	.no-italics {
		font-style: normal;
	}
</style>

<label for="">Venue Categories</label>
<input type="text" name="venuecategories" placeholder="en" value="<?php echo $this->getValue('venuecategories'); ?>">
<label for="">Highlight Categories</label>
<input type="text" name="highlightcategories" placeholder="en" value="<?php echo $this->getValue('highlightcategories'); ?>">
<label for="">Image Categories</label>
<input type="text" name="imagecategories" placeholder="en" value="<?php echo $this->getValue('imagecategories'); ?>">
<label for="">Service Categories</label>
<input type="text" name="servicecategories" placeholder="en" value="<?php echo $this->getValue('servicecategories'); ?>">


<input type="submit" name="create-settings" class="create-settings btn btn-primary mt-3" value="Save Settings">


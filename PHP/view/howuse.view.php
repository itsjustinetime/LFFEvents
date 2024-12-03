<style>
	.btn {
		display: inline-flex;
		padding: 5px 10px;
		border: solid 1px #000;
		background: #000;
		color: #fff !important;
		text-decoration: none !important;
	}

	code {
		border: solid 1px #ddd;
		background: #fafafa;
		padding: 5px;
		display: inline-block;
		margin: 10px 0;
		color: green;
	}

	.no-italics {
		font-style: normal;
	}
</style>

<?php
global $SITEURL;
global $GSADMIN;
?>

<h3><span class="no-italics">📅</span> CurrentEvents - Help</h3>
<a href="<?php echo DOMAIN_ADMIN; ?>/plugin/currentevents" class="btn">Back</a>

<br><br>

<p>• Place this shortcode in content: <code>[% ce %]</code></p>

<p>• or this call in your template: <code>&lt;?php showEventCalendar() ;?&gt;</code></p>

<div class="col-md-12 mt-5 p-0">
    <script type='text/javascript' src='https://storage.ko-fi.com/cdn/widget/Widget_2.js'></script>
    <script type='text/javascript'>
        kofiwidget2.init('Support Me on Ko-fi', '#29abe0', 'I3I2RHQZS');
        kofiwidget2.draw();
    </script>
</div>
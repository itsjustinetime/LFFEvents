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

    .event-items {
        width: 100%;
        list-style-type: none;
        margin: 10px 0 !important;
    }

    .event-item {
        box-sizing: border-box;
        padding: 5px;
        align-items: center;
        margin: 0;
        background: #fafafa;
        border: solid 1px #ddd;
        display: grid;
        grid-template-columns: 1fr 100px 100px;
    }

    .no-italics {
        font-style: normal;
    }
</style>
<?php

$highlightdata=[];

if (!empty(glob(PATH_CONTENT . 'lff-events/highlights/*.json'))) {
        foreach (glob(PATH_CONTENT . 'lff-events/highlights/*.json') as $key => $file) {
			$data = json_decode(file_get_contents($file),true);
			$filestring=pathinfo($file)['filename'];
			$data['filepath']=$filestring;
			$highlightdata[]=$data;
		}
}


?>

<h3><span class="no-italics">📅</span>Highlights</h3>

<a href="<?php echo DOMAIN_ADMIN; ?>plugin/lffevents?addhighlight" class="btn">Add highlight ➕ </a>

 <?php
	
foreach ($highlightdata as $item) {
	?>
	<div class="listitem" style="border:1px solid pink; padding:10px;">
		<div class="itemdate"><h4><?php echo $item['highlightvenue']. ' | ' .$item['highlighttitle']; ?> &nbsp;</h4>
		<?php $evDate = new DateTime($item['highlightstart']); echo date_format($evDate,"l jS F Y g:i a"); ?> - 
		<?php $evDate = new DateTime($item['highlightend']); echo date_format($evDate,"l jS F Y g:i a"); ?></h4></div>
		<div class="row itemrow">
			<div class="col"><h5>Title:</h5><?php echo $item['highlighttitle']; ?></div>
			<div class="col"><h5>Subtitle:</h5><?php echo $item['highlightsubtitle']; ?></div>
		</div>
		<div class="row itemrow">
			<div class="col"><h5>Description</h5><?php echo $item['highlightdescription']; ?></div>
		</div>
		<div class="row itemrow">
			<div class="col"><h5>Category</h5><?php echo $item['highlightcategory']; ?></div>
			<div class="col"><h5>Priority</h5><?php echo $item['highlightpriority']; ?></div>
		</div>
		<div class="row itemrow">
			<div class="col"><a href="<?php echo DOMAIN_ADMIN . '/plugin/lffevents?addhighlight&edit=' . $item['filepath']; ?>" class="btn-sm">Edit</a></div>
			<div class="col"><a href="<?php echo DOMAIN_ADMIN . '/plugin/lffevents?deletehighlight=' . $item['filepath']; ?>" class="btn-sm btn-sm-red delbtn">Delete</a></div>
		</div>
	</div>
<?php } ?>

<script>
$(".delbtn").click(function() {
	 var ThisElement = $(this);
	 var title = ThisElement.parent().parent().children(':first').text();
	 console.log(title);
	 
	        var conf = confirm('Are you sure want to delete this entry for '+title+' ?');
        if(!conf) {
            return false;
        }
       
        var UrlToPass = ThisElement.attr('href');
        $.ajax({
            url : ajaxTarget,
            type : 'GET',
            data : UrlToPass,
            success: function() {
                LoadGrid();
            }
        });
        return false;

	
});

</script>
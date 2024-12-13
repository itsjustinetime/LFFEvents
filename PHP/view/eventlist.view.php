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

.sortbtn {
	background-color:white;
	border:2px solid green;
	color:black!important;
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
	
	.eventlist {
		margin-top:2vh;
	}
	
	.heading {
		font-weight: bold;
	}
	.listthumb {
		width:100px;
	}
	.itemrow {
		margin-bottom: 2vh;
	}
	.sortbtn {
	margin-bottom:1vh;}

	
</style>
<?php

$eventdata=[];
$columnname="eventstart";

if (isset($_GET['sortby'])) {$columnname=$_GET['sortby'];}
if (!empty(glob(PATH_CONTENT . 'lff-events/events/*.json'))) {
        foreach (glob(PATH_CONTENT . 'lff-events/events/*.json') as $key => $file) {
			$data = json_decode(file_get_contents($file),true);
			$filestring=pathinfo($file)['filename'];
			$data['filepath']=$filestring;
			$eventdata[]=$data;
		}
}
array_multisort( array_column($eventdata, $columnname), SORT_ASC, $eventdata );


?>

<h3><span class="no-italics">📅</span>Events List</h3>

<a href="<?php echo DOMAIN_ADMIN; ?>plugin/lffevents?addevent" class="btn">Add Event ➕ </a>
<a href="<?php echo DOMAIN_ADMIN; ?>plugin/lffevents?helpevent" class="btn">Help</a>
<div class="eventlist">
<div class="row">
<div class="col">Sort by</div>
</div>
<div class="row itemrow">
<div class="col"><a class="btn-sm sortbtn" href="<?php echo DOMAIN_ADMIN; ?>plugin/lffevents?listevents&sortby=eventtitle">Title</a></div>
<div class="col"><a class="btn-sm sortbtn" href="<?php echo DOMAIN_ADMIN; ?>plugin/lffevents?listevents&sortby=eventsubtitle">Subtitle</a></div>
<div class="col"><a class="btn-sm sortbtn" href="<?php echo DOMAIN_ADMIN; ?>plugin/lffevents?listevents&sortby=eventvenue">Venue</a></div>
<div class="col"><a class="btn-sm sortbtn" href="<?php echo DOMAIN_ADMIN; ?>plugin/lffevents?listevents&sortby=eventstart">Start</a></div>
<div class="col"><a class="btn-sm sortbtn" href="<?php echo DOMAIN_ADMIN; ?>plugin/lffevents?listevents&sortby=eventpriority">Priority</a></div>
</div>

    <?php
	
foreach ($eventdata as $item) {
	?>
	<div class="listitem" style="border:1px solid pink; padding:10px;">
		<div class="itemdate"><h4><?php $evDate = new DateTime($item['eventstart']); echo $item['eventtitle']." | ".date_format($evDate,"l jS F Y"); ?></h4></div>
		<div class="row itemrow">
			<div class="col"><img class="listthumb" src="<?php echo HTML_PATH_ROOT.'/bl-content/lff-events/images/'.$item['eventimage']; ?>"></div>
			<div class="col"><h5>Title:</h5><?php echo $item['eventtitle']; ?></div>
			<div class="col"><h5>Subtitle:</h5><?php echo $item['eventsubtitle']; ?></div>
		</div>
		<div class="row itemrow">
			<div class="col"><h5>Description</h5><?php echo $item['eventdescription']; ?></div>
		</div>
		<div class="row itemrow">
			<div class="col"><h5>Venue</h5><?php echo $item['eventvenue']; ?></div>
			<div class="col"><h5>Time</h5><?php echo explode(" ",$item['eventstart'])[1] . ' - '. explode(" ",$item['eventend'])[1]; ?></div>
			<div class="col"><h5>Priority</h5><?php echo $item['eventpriority']; ?></div>
		</div>

		<div class="row itemrow">
			<div class="col"><h5>Show</h5><?php if ($item['eventshow']=="on") echo "YES"; else echo "no"; ?></div>
			<div class="col"><h5>Recur</h5><?php if ($item['eventrecur']=="on") echo "YES"; else echo "no"; ?></div>
			<div class="col"><h5>Full?</h5><?php if ($item['eventfull']=="on") echo "YES"; else echo "no"; ?></div>
		</div>
		<div class="row itemrow">
			<div class="col"><a href="<?php echo DOMAIN_ADMIN . '/plugin/lffevents?addevent&edit=' . $item['filepath']; ?>" class="btn-sm">Edit</a></div>
			<div class="col"><a href="<?php echo DOMAIN_ADMIN . '/plugin/lffevents?deleteevent=' . $item['filepath']; ?>" class="btn-sm btn-sm-red delbtn">Delete</a></div>
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



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
</style>

<?php

$venuedata=[];
$columnname="venuename";

if (isset($_GET['sortby'])) {$columnname=$_GET['sortby'];}
if (!empty(glob(PATH_CONTENT . 'lff-events/venues/*.json'))) {
        foreach (glob(PATH_CONTENT . 'lff-events/venues/*.json') as $key => $file) {
			$data = json_decode(file_get_contents($file),true);
			$filestring=pathinfo($file)['filename'];
			$data['filepath']=$filestring;
			$venuedata[]=$data;
		}
}

array_multisort( array_column($venuedata, $columnname), SORT_ASC, $venuedata );

?>
<h3><span class="no-italics">📅</span>Venues List</h3>

<a href="<?php echo DOMAIN_ADMIN; ?>plugin/lffevents?addvenue" class="btn">Add Venue ➕ </a>
<h5>Sort By</h5>
<div class="row itemrow">
	<div class="col"><a class="btn-sm" href="<?php echo DOMAIN_ADMIN; ?>plugin/lffevents?listvenues&sortby=venuename">Name</a></div>
	<div class="col"><a class="btn-sm" href="<?php echo DOMAIN_ADMIN; ?>plugin/lffevents?listvenues&sortby=venueaddress">Address</a></div>
	<div class="col"><a class="btn-sm" href="<?php echo DOMAIN_ADMIN; ?>plugin/lffevents?listvenues&sortby=venuecategory">Cat</a></div>
</div>
<div class="row itemrow">
	<div class="col"><a class="btn-sm" href="<?php echo DOMAIN_ADMIN; ?>plugin/lffevents?listvenues&sortby=venuepriority">Priority</a></div>
	<div class="col"><a class="btn-sm" href="<?php echo DOMAIN_ADMIN; ?>plugin/lffevents?listvenues&sortby=venuerecommended">Recc</a></div>
	<div class="col"><a class="btn-sm" href="<?php echo DOMAIN_ADMIN; ?>plugin/lffevents?listvenues&sortby=venueshow">Show</a></div>
</div>
<?php 
foreach ($venuedata as $item) {
	?>
	
	<div class="listitem" style="border:1px solid pink; padding:10px;">
		<div class="itemdate"><h4><?php echo $item['venuename']; ?> &nbsp;</h4></div>
		<div class="row itemrow">
			<div class="col"><h5>Address</h5><?php echo $item['venueaddress']; ?></div>
			<div class="col"><img class="listthumb" src="<?php echo HTML_PATH_ROOT.'/bl-content/lff-events/images/'.$item['venueimage']; ?>"></div>
		</div>
		<div class="row itemrow">
			<div class="col"><h5>Category</h5><?php echo $item['venuecategory']; ?></div>
			<div class="col"><h5>Priority</h5><?php echo $item['venuepriority']; ?></div>
		</div>
		<div class="row itemrow">
			<div class="col"><h5>Recommended</h5><?php if ($item['venuerecommended'] == 'on') echo 'yes'; else echo 'no';?></div>
			<div class="col"><h5>Show?</h5><?php if ($item['venueshow'] == 'on') echo 'yes'; else echo 'no'; ?></div>
		</div>
		<div class="row itemrow">
			<div class="col"><a href="<?php echo DOMAIN_ADMIN . '/plugin/lffevents?addvenue&edit=' . $item['filepath']; ?>" class="btn-sm">Edit</a></div>
			<div class="col"><a href="<?php echo DOMAIN_ADMIN . '/plugin/lffevents?deletevenue=' . $item['filepath']; ?>" class="btn-sm btn-sm-red delbtn">Delete</a></div>
		</div>
	</div>
	<?php
}

?>

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



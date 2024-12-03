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


<h3><span class="no-italics">📅</span>Services List</h3>

<a href="<?php echo DOMAIN_ADMIN; ?>plugin/lffevents?addservice" class="btn">Add Service ➕ </a>

<div class="row">
<div class="col heading">Name</div>
<div class="col heading">Address</div>
<div class="col heading">Image</div>
<div class="col heading">Cat</div>
<div class="col heading">Edit</div>
<div class="col heading">Del</div>
</div>

    <?php
    if (!empty(glob(PATH_CONTENT . 'lff-events/services/*.json'))) {
        foreach (glob(PATH_CONTENT . 'lff-events/services/*.json') as $key => $file) {
            $data = json_decode(file_get_contents($file)); ?>
			<div class="row itemrow">
			<div class="col"><?php echo $data->servicename; ?></div>
			<div class="col"><?php echo $data->serviceaddress; ?></div>
			<div class="col"><img class="listthumb" src="<?php echo HTML_PATH_ROOT.'/bl-content/lff-events/images/'.$data->serviceimage; ?>"></div>
			<div class="col"><?php echo $data->servicecategory; ?></div>
			<div class="col"><a href="<?php echo DOMAIN_ADMIN . '/plugin/lffevents?addservice&edit=' . pathinfo($file)['filename']; ?>" class="btn-sm">Edit</a></div>
			<div class="col"><a href="<?php echo DOMAIN_ADMIN . '/plugin/lffevents?deleteservice=' . pathinfo($file)['filename']; ?>" class="btn-sm btn-sm-red delbtn">Delete</a></div>
			</div>	<?php		
        };
    };
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

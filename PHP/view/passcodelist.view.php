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


<h3><span class="no-italics">📅</span>Passcodes List</h3>

<a href="<?php echo DOMAIN_ADMIN; ?>plugin/lffevents?addpasscode" class="btn">Add Passcode ➕ </a>

<div class="row">
<div class="col heading">Name</div>
<div class="col heading">Value</div>
<div class="col heading">Expiry Date</div>
<div class="col heading"></div>
<div class="col heading"></div>
</div>

    <?php
    if (!empty(glob(PATH_CONTENT . 'lff-events/passcodes/*.json'))) {
        foreach (glob(PATH_CONTENT . 'lff-events/passcodes/*.json') as $key => $file) {
            $data = json_decode(file_get_contents($file)); ?>
			<div class="row itemrow">
			<div class="col"><?php echo $data->passcodename; ?></div>
			<div class="col"><?php echo $data->passcodevalue; ?></div>
			<div class="col"><?php echo $data->passcodeexpires; ?></div>
			<div class="col"><a href="<?php echo DOMAIN_ADMIN . '/plugin/lffevents?addpasscode&edit=' . pathinfo($file)['filename']; ?>" class="btn-sm">Edit</a></div>
			<div class="col"><a href="<?php echo DOMAIN_ADMIN . '/plugin/lffevents?deletepasscode=' . pathinfo($file)['filename']; ?>" class="btn-sm btn-sm-red">Delete</a></div>
			</div>	<?php		
        };
    };
    ?>



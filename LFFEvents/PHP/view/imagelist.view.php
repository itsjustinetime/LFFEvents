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
	.lffimage {
		width:10vw;
	}
	
	@media only screen and (max-width: 600px) {
		.lffimage { 
		width: 41vw;
		}
	}
</style>

<?php

$imagedata=[];
$columnname="imagecategory";

if (isset($_GET['sortby'])) {$columnname=$_GET['sortby'];}
if (!empty(glob(PATH_CONTENT . 'lff-events/images/*.json'))) {
        foreach (glob(PATH_CONTENT . 'lff-events/images/*.json') as $key => $file) {
			$data = json_decode(file_get_contents($file),true);
			$filestring=pathinfo($file)['filename'];
			$data['filepath']=$filestring;
			$imagedata[]=$data;
		}
}
//array_multisort( array_column($imagedata, $columnname), SORT_ASC, $imagedata );
array_multisort(array_column($imagedata, 'imagecategory'), SORT_ASC, array_column($imagedata, 'imagefilename'), SORT_NATURAL | SORT_FLAG_CASE, $imagedata);

?>

<h3><span class="no-italics">📅</span>Images</h3>

<a href="<?php echo DOMAIN_ADMIN; ?>plugin/lffevents?addimage" class="btn">Add Image ➕ </a>

<div class="imagegrid" style="display:flex; flex-wrap:wrap;">

<?php
$lastCat="";
$catnum=0;
foreach ($imagedata as $image) 
	{
	//if (($lastCat != $image['imagecategory']) && $catnum > 0) { echo "</div>"; }
	if ($image['imagecategory'] != $lastCat) {
				echo '</div>';
				echo '<div class="imagecat" style="margin-top: 2vh; background: grey; color: white;  padding: 10px; text-transform: uppercase;">';
				echo '<h4>'.$image['imagecategory'].'</h4>';
				echo '</div>';
				echo '<div class="imagegrid" style="display:flex; flex-wrap:wrap;">';
				$catnum++;
			}
	?>
	<div class="griditem" style="display:flex; flex-wrap:wrap; flex-direction:column; margin:10px;">
		<a href="<?php echo DOMAIN_ADMIN . '/plugin/lffevents?editimage&edit=' . $image['filepath']; ?>">
			<img class="lffimage" src="<?php echo HTML_PATH_ROOT.'/bl-content/lff-events/images/'.$image['imagefilename']; ?>"/>
		</a>
		<div class="gridtext" style="text-align:center;"><?php echo explode('.',$image['imagefilename'])[0]; ?></div>
		<div class="gridtext" style="text-align:center;"><?php echo $image['imagecategory']; ?></div>
		<div class="gridbuttons" style="text-align:center;">
			<a href="<?php echo DOMAIN_ADMIN . '/plugin/lffevents?editimage&edit=' . $image['filepath']; ?>" class="btn-sm">Edit</a>
			<a href="<?php echo DOMAIN_ADMIN . '/plugin/lffevents?deleteimage=' . $image['filepath']; ?>" class="btn-sm btn-sm-red delbtn">Delete</a>
		</div>
	</div>
	<?php
	$lastCat=$image['imagecategory'];
}

?>

</div>

<br><br><br>
<div class="imagegrid" style="display:flex; flex-wrap:wrap;">

    <?php
    ?>

</div>

<script>
$(".delbtn").click(function() {
	 var ThisElement = $(this);
	 var title = ThisElement.parent().parent().children(':first').next().text();
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
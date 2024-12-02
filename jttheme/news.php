<?php 
$scrollClass="";
//if ($WHERE_AM_I == "home") {$scrollClass="js-scroll";}

$category = getCategory('news');
$count=1;
$limit=3;
        // Print the category name
        ?>
		
	<div class="postcattitle">
	<?php echo $category->name(); ?>
	</div> <?php
        // Print the pages title related to the category "example"
        foreach ($category->pages() as $pageKey) :
		if ($count > $limit) break;
            $page = new Page($pageKey);
 
	//$scrollClass="";
	//echo '<br>Cat:'.$page->category().'<br>'.$page->type().'<br>';
	// rules:
	// if page is of type 'published' display as 3 column layout otherwise display single colummn
	// if we're not on the homepage don't add js-scroll to the classlist
	if ($page->type() == "published" && $page->category()=="News"):
	 
?>
	<article>
		<h3 class="<? php echo $scrollClass; ?> slide-left"><a href="<?php echo $page->permalink(); ?>"><?php echo $page->title() ?></a></h3>
		<div class="article-col">
			<div class="firstcol">
				<a href="<?php echo $page->permalink() ?>" class="image"><img class="<?php echo $scrollClass; ?> slide-right articlecoverimage" src="<?php echo $page->coverImage() ?>" alt="" /></a>
			</div>
			<div class="middlecol pagetext <? php echo $scrollClass; ?> fade-in">
			<?php echo $page->contentBreak() ?>

			<!-- Read more button -->
			<?php if($page->readMore()): ?>
			<ul class="actions">
				<li><a href="<?php echo $page->permalink() ?>" class="button"><?php $language->p('More') ?></a></li>
			</ul>
			
			<?php endif; ?>
			</div>
			
			<div class="articleinfo lastcol">
					<ul class="articleinfotext">
						<li><?php echo $page->date(); ?></li>
						<li><?php echo $page->username(); ?></li>
					</ul>
					<div class="articletags">
						<ul class="articletaglist">
						<?php 
							$returnsArray = true;
							$items = $page->tags($returnsArray);
							foreach ($items as $tagKey=>$tagName) {
								$tag = new Tag($tagKey);
								echo '<li class="articletag"><a href="'.$tag->permalink().'">'.$tag->name().'</a></li>';
							}
						?>
						</ul>
					</div>
			</div>
		</div>
	</article>
	<?php endif;
	$count++;
	
endforeach;
?>
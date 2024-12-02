<?php 
// Single page view
$scrollClass="";
$page=array_shift($content); ?>

<article>
	<h3 class=""><?php echo $page->title() ?></h3>
	<div class="article">	
		<a href="<?php echo $page->permalink() ?>" class="image"><img class="<?php echo $scrollClass; ?> slide-right coverimage" src="<?php echo $page->coverImage() ?>" alt="" /></a>
		
		<div class="singlepagetext <?php echo $scrollClass; ?> fade-in">
		<?php echo $page->contentBreak() ?>

		<!-- Read more button -->
		<?php if($page->readMore()): ?>
		<ul class="actions">
			<li><a href="<?php echo $page->permalink() ?>" class="button"><?php $language->p('More') ?></a></li>
		</ul>
		
		<?php endif; ?>
		</div>
		
		<div class="articleinfo" style="display:inline-flex; align-items:center;">
				<ul class="articleinfotext">
					<li><?php echo $page->date().' by '.$page->username(); ?></li>
				</ul>
				<div class="articletagscol">
					<ul class="articletaglistcol" style="display:inline-flex; justify-content:space-between; width:100%; margin-left:2em;">
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
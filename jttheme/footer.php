<div class="footer">
	<div class="linkbox">
		<div class="socialtitle">Our Socials</div>
	</div>
	<div class="linkbox">
		<ul class="sociallinks">
		
<?php 
			 $socialNetworks = array(
                    // Key => Label
                    'facebook'=>'Facebook',
                    'instagram'=>'Instagram',
                    'mixcloud'=>'Mixcloud',
                    'discord'=>'Discord',
					'bluesky'=>'BlueSky'
                );
			foreach ($socialNetworks as $key=>$label): ?>
                    <?php if ($site->{$key}()): ?>
                        <li class="sociallink"><a href="<?php echo $site->{$key}() ?>"><i class="fa-brands fa-<?php echo $key ?>"></i></a></li>
                    <?php endif; ?>
                <?php endforeach; ?>
		</ul>
	</div>
	<div class="footerexplain">
	Leeds First Friday is a community event organised &amp; run entirely by volunteers.
	</div>
	
	<div class="jt">
	&copy; Copyright Leeds First Friday 2024
		<i class="fa-solid fa-pencil"></i>&nbsp;Web design by JT
	</div>
</div>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS -->
    <?php 
 ?>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <!-- Javascript -->
	
    <?php echo Theme::js('js/js.js') ?>


    <!-- Load plugins with the hook siteHead -->
    <?php Theme::plugins('siteHead') ?>
</head>
<body>
<?php $themestr=Theme::css('css/theme.css');
		echo $themestr;
		?>
    <!-- Load plugins with the hook siteBodyBegin -->
    <?php Theme::plugins('siteBodyBegin') ?>
	<?php include(THEME_DIR."header.php"); ?>
<main>

	<div class="main" id="maindiv">
		<div class="container">
			<!-- <h1><?php echo $site->title() ?></h1> -->
			<!-- <h2><?php echo $site->slogan() ?></h2> -->
			<!-- First page -->
			<?php 
if($WHERE_AM_I == "home"):
 $firstPage = array_shift($content) ?>
			<section id="banner">
	<?php if($firstPage->coverImage()){ ?>
				<span class="image object">
					<img class="coverimage" src="<?php echo $firstPage->coverImage() ?>" alt="" />
				</span>
	<?php } ?>
				<div class="content">
					<header>
						<p><?php echo $firstPage->description() ?></p>
					</header>

					<p><?php echo $firstPage->contentBreak() ?></p>

					<!-- Read more button -->
					<?php if($firstPage->readMore()): ?>
					<ul class="actions">
						<li><a href="<?php echo $firstPage->permalink() ?>" class="button"><?php $language->p('More') ?></a></li>
					</ul>
					<?php endif; ?>
				</div>
			</section>
<?php 
endif;

//echo $WHERE_AM_I.'<br>';
$scrollclass="";
if ($WHERE_AM_I == "home") { 
include(THEME_DIR.'news.php');
	Theme::plugins('LFF_Events');
	Theme::plugins('LFF_Future');
	Theme::plugins('LFF_VenuesRec');
	$scrollclass="js-scroll";
}
 

if ($WHERE_AM_I == 'page' && !$_GET) {  ?>
			<section>
				<div class="posts">
				<?php include(THEME_DIR.'page.php'); ?>
				</div>
			</section>
<?php } 

else if ($WHERE_AM_I == 'page' ) {
	include(THEME_DIR.'virtpage.php');
	
}
else { ?>

			<section>
				<div class="posts">
			<?php include(THEME_DIR.'posts.php'); ?>
				</div>
			</section>
<?php } ?>
		</div> <!-- end of container div -->
	</div> <!-- end of main div -->

<?php include(THEME_DIR.'footer.php'); ?>
    <!-- Load plugins with the hook siteBodyEnd -->
    <?php Theme::plugins('siteBodyEnd') ?>
	
</main> <!-- End of main -->

	<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	 <script>
        var swiper = new Swiper(".mySwiper", {
      slidesPerView: "auto",
      spaceBetween: 10,
	  loop: false,
	  navigation: {
        nextEl: ".lffnextbtn",
        prevEl: ".lffprevbtn",
      },
		scrollbar: {
        el: ".swiper-scrollbar",
        hide: false,
      },
    });
	
// Hide Header on on scroll down
var didScroll;
var lastScrollTop = 0;
var delta = 10;
var navbarHeight = $('#siteheader').outerHeight();

$(window).scroll(function(event){
    didScroll = true;
});

setInterval(function() {
    if (didScroll) {
        hasScrolled();
        didScroll = false;
    }
}, 100);

function hasScrolled() {
    var st = $(this).scrollTop();
    
    // Make sure they scroll more than delta
    if(Math.abs(lastScrollTop - st) <= delta)
        return;
    
    // If they scrolled down and are past the navbar, add class .nav-up.
    // This is necessary so you never see what is "behind" the navbar.
    if (st > lastScrollTop && st > navbarHeight){
        // Scroll Down
        $('#siteheader').removeClass('show').addClass('hide');
    } else {
        // Scroll Up
        if(st + $(window).height() < $(document).height()) {
            $('#siteheader').removeClass('hide').addClass('show');
        }
    }
    
    lastScrollTop = st;
}


document.getElementById("navopenbtn").addEventListener('click', function openClick(){

	document.getElementById("navclose").style.display="block";
	$('html').addClass('scrollstop');
	document.getElementById("navarea").classList.add('navopen');
	scrollPosition=window.scrollY;
	setTimeout(function() {		},500);
});

document.getElementById("navclosebtn").addEventListener('click', function closeClick(){
	document.getElementById("navclose").style.display="none";
	document.getElementById("navarea").classList.remove('navopen');
	$('html').removeClass('scrollstop')
	setTimeout(function() {		},500);
});

//initialize throttleTimer as false 
let throttleTimer = false;
const throttle = (callback, time) => {
    //don't run the function while throttle timer is true 
    if (throttleTimer) return;
    
    //first set throttle timer to true so the function doesn't run 
    throttleTimer = true;
    
    setTimeout(() => {
        //call the callback function in the setTimeout and set the throttle timer to false after the indicated time has passed 
        callback();
        throttleTimer = false;
	}, time);
}


const scrollElements = document.querySelectorAll(".js-scroll");

const elementInView = (el, dividend = 1) => {
  const elementTop = el.getBoundingClientRect().top;

  return (
    elementTop <=
    (window.innerHeight || document.documentElement.clientHeight) / dividend
  );
};

const elementOutofView = (el) => {
  const elementTop = el.getBoundingClientRect().top;

  return (
    elementTop > (window.innerHeight || document.documentElement.clientHeight)
  );
};

const displayScrollElement = (element) => {
  element.classList.add("scrolled");
};

const hideScrollElement = (element) => {
  element.classList.remove("scrolled");
};

const handleScrollAnimation = () => {
  scrollElements.forEach((el) => {
    if (elementInView(el, 1.25)) {
      displayScrollElement(el);
    } else if (elementOutofView(el)) {
      hideScrollElement(el)
    }
  })
}

window.addEventListener("scroll", () => { 
  throttle(handleScrollAnimation(),250);
});

  </script>
</body>
</html>
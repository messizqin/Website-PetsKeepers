<?php 
/*
AUTHOR: Messiz Qin
GITHUB: https://github.com/Weilory
PROJECT: PetsKeepers
*/

/*
The main idea is that we load every thing at once, and during the visiting, avoid refreshing as possible as we can. 
The reason is that due to asynchronous feature of js, loading css animations becomes a problem, the page will freeze at start. therefore, i solve it by using a image slider with a higher priority which has a higher zindex to hide everything behind that is loading. 
because refreshing is not allowed, we use ajax to passes variable between php and javascript
*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<!-- sticky navigation bar -->
	<meta charset="UTF-8">
	<title>Intro</title>
	<meta charset="UTF-8">
	<title>Pets Keeping Service</title>
	<!-- css standard module -->
	<link rel="stylesheet" type="text/css" href="../css/bootstrap-4.0.0.css" />
	<!-- project style sheets -->
	<link rel="stylesheet" type="text/css" href="../css/intro.css" />
	<link rel="stylesheet" type="text/css" href="../css/nav.css" />
	<link rel="stylesheet" type="text/css" href="../css/calendar.css" />
	<link rel="stylesheet" type="text/css" href='../css/services.css' />
	<link rel="stylesheet" type="text/css" href='../css/tobook.css' />
	<link rel="stylesheet" type="text/css" href='../css/booked.css' />
	<link type="text/css" rel="stylesheet" href="../css/form.css">
	<!-- javascript stardard modules -->
	<script type="text/javascript" src="../js/jquery-3.5.1.js"></script>
	<script type="text/javascript" src='../js/datedropper.pro.min.js'></script>	
	<style>
		/* tight layout */
		*{margin: 0px;} 
	</style>
</head>
<body>

<!-- sticky navigation bar -->
<div class='navheader'>
	<header id='complex'>
		<a href="#" class="logo">PetsKeepers</a>
		<ul>
			<!-- control view by changing display to block or none -->
			<li><a href="#" onclick='go_home()'>Home</a></li>
			<li><a href="#" onclick="go_about()">About</a></li>
			<li><a href="#" onclick="go_services()">Services</a></li>
		</ul>
	</header>
</div>

<!-- about page: image slider -->
<div class='intro'>
	<div class="wrap">
		<!-- control arrow for sliding left -->
        <div id="arrow-left" class="arrow"></div>
        <div id="slider">
            <div class="slide slide1">
            	<!-- when user click anywhere on the page, terminate the slide -->
                <div class="slide-content" onclick="terminate()">
					<span>
                    	<div class='title'>
							BEST Pet Keepers in the world
						</div>
						<div class='content'>
							We provide a loving and caring home
						</div>
                    </span>
				</div>
            </div>
			<div class="slide slide2">
                <div class="slide-content" onclick="terminate()">
					<span>
            	        <div class='title'>
							You can TRUST us to look after your pet
						</div>
						<div class='content'>
							We can report your pet's status in any time
						</div>
                	</span>
				</div>
            </div>
			<div class="slide slide3">
                <div class="slide-content" onclick="terminate()">
					<span>
    	                <div class='title'>
							We have PROFESSIONAL pet keepers
						</div>
						<div class='content'>
							We will keep them happy everyday
						</div>
                	</span>
				</div>
            </div>
			<div class="slide slide4">
                <div class="slide-content" onclick="terminate()">
					<span>
        	            <div class='title'>
							This is LUXURY for your pet
						</div>
						<div class='content'>
							We have a popular pet food storage
						</div>
            	     </span>
				</div>
            </div>
			<div class="slide slide5">
                <div class="slide-content" onclick="terminate()">
					<span>
        	            <div class='title'>
							Your pet will LOVE here
						</div>
						<div class='content'>
							It will be hard to drag your dog home when leaving
						</div>
                	</span>
				</div>
            </div>
        </div>
        <!-- control arrow for sliding right -->
        <div id="arrow-right" class="arrow"></div>
    </div>
</div>

<!-- home page: textarea, svg, calendar, google map -->
<div class='home'>
	<!-- svg: location, calendar -->
	<section class="banner"></section>
	<?php require '../html/location.html'; ?>
	<section class='canner'></section>
	<?php require '../html/calendar.html'; ?>
	<section class='danner'></section>
	<!-- text area -->
	<div class='supply'>
		<article class='essay'>
			<h2 class='ml title'>Just need someone to look after your puppy whilst you are away?</h2>
			<p class='mb content'>Congratulations! This is definetely the right place, we provides Australia's most convinient & trustworthy Pet Care Services.</p>
		</article>
	</div>
	<!-- embed google map -->
	<div class='mapp'>
		<div class="gmap_canvas"><iframe width="600" height="500" id="gmap_canvas" src="https://maps.google.com/maps?q=Precious%20Paws%20Pet%20Care%20Services%20Mackay&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe><a href="https://www.whatismyip-address.com/divi-discount/"></a></div>
	</div>

<?php

// generate random dates 
function randomGen($min, $max, $quantity) {
    $numbers = range($min, $max);
    shuffle($numbers);
    return array_slice($numbers, 0, $quantity);
}

// handing now and future time allocation
// to allow user to look forward the calendar
$this_month = (int)date('m');
$this_year = (int)date('Y');
$next_year = $this_year + 1; 
$crossed = false; 
$six_months = array(); 

for($i=0; $i<6; $i++){
	$t_month = $this_month + $i;
	if($t_month > 12){
		$crossed = true;
		array_push($six_months, $t_month - 12); 
	}else{
		array_push($six_months, $t_month); 
	}
}

// handling special month 30 days and 31 days
function daysInMonth($month){
	if($month == 2){
		return 29; 
	}elseif(in_array($month, array(4, 6, 9, 11))){
		return 30; 
	}else{
		return 31; 
	}
}

// generate calendar available and unavailable dates
function cale_cons($this_month, $excepted, $delim=7){
	echo "<div class='cal'>"; 
	for($i=1; $i<=daysInMonth($this_month); $i++){
		if(in_array($i, $excepted)){
			echo "<div class='sin ex'>".$i."</div>"; 
		}else{
			echo "<div class='sin av'>".$i."</div>"; 
		}
		if($i % $delim == 0){
			echo "<br />";
		}
	}
	echo "</div>";
}

// call the function to write html calendar
echo "<div class='calcon'>"; 
foreach($six_months as $month){
	cale_cons($month, randomGen(1, daysInMonth($month), 6));
}
echo "</div>"; 

?>
</div>

<!-- services page: login, register, booking -->
<div class='services'>
	<img src='../media/loader.gif' id='loader' />
	<div id='rform' style='display: none;'>
		<?php include('../html/rform.html'); ?>
	</div>
	<div id='sform'>
		<?php include('../html/sform.html'); ?>
	</div>
	<div id='eform' style='display: none;'>
		<?php include('../html/eform.html'); ?>
	</div>
	<div id='tobook' style='display: none;'>
		<?php include('../php/tobook.php') ?>
	</div>
	<div id='booked' style='display: none;'>
		<?php include('../php/booked.php') ?>
	</div>
	<div id='btn-placeholder'></div>
</div>

<!-- project javascript: page interaction -->
<script src='../js/intro.js'></script>
<!-- <script src='../js/services.js'></script> -->
<?php include_once('../php/services.js.php'); ?>
<script src='../js/booked.js'></script>
<script src='../js/home.js'></script>
<script src='../js/nav.js'></script>
</body>
</html>

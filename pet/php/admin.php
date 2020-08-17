<?php 

/*
AUTHOR: Messiz Qin
GITHUB: https://github.com/Weilory
PROJECT: PetsKeepers
*/

/*
administer access only 
*/

// url direct access deny
session_start();

if(isset($_SESSION['redirect']) && $_SESSION['redirect'] == true){
	$_SESSION['redirect'] = false; 
	$_SESSION['retry'] = true;
}else{
	require_once('../php/inverse.php');
	echo "<h2>403 Forbidden</h2>";
	echo "<p>Sorry, this page cannot be accessed directly from url</p>";
	echo '<hr />';
	echo '<a href="' . Inverse::root() . '/php/index.php' . '">Click here to the home page</a>';
    die();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Admin</title>
	<!-- css standard module -->
	<link rel="stylesheet" type="text/css" href="../css/bootstrap-4.0.0.css" />
	<!-- project style sheets -->
	<link type="text/css" rel="stylesheet" href="../css/form.css" />
	<link rel="stylesheet" href="../css/booked.css" type="text/css" />
	<link rel="stylesheet" rel="stylesheet" href="../css/admin.css" />
	<!-- javascript stardard modules -->
	<script type="text/javascript" src="../js/jquery-3.5.1.js"></script>
	<script type="text/javascript" src='../js/datedropper.pro.min.js'></script>	
</head>
<body>

<div id='admin-container'></div>
<button id='goback' class='btn btn-outline-success btn-lg' style='display: none;'>Go Back</button>

<?php include_once("../html/cform.html"); ?>
<form id='admin-w' class='booking' method='POST'></form>

<!-- all funcs in admin.js are reach-able in parser.php -->
<script type='text/javascript' src="../js/admin.js"></script>

<script type='text/javascript'>
	<?php include('../php/parser.php'); ?>	
</script>

</body>
</html>
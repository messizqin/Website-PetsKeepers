<?php 

/*
AUTHOR: Messiz Qin
GITHUB: https://github.com/Weilory
PROJECT: PetsKeepers
*/

/*
user forget password and they need to reset
secured by following:
	1. encrypted token
	2. password reset link expire time
*/

require_once('../php/users.php');
require_once('../php/inverse.php');
require_once('../php/sess.php');

// ajax reach-able only
if(!isset($_GET['token'])){
	echo "<h2>403 Forbidden</h2>";
	echo "<p>Sorry, this page cannot be accessed directly from url</p>";
	echo '<hr />';
	echo '<a href="' . Inverse::root() . '/php/index.php' . '">Click here to the home page</a>';
    die();
}

// setting timezone is extremely important, since server may have different location
date_default_timezone_set("Australia/Melbourne");

// decode url token to id, expiretime
$data = json_decode(base64_decode($_GET['token']), true);
$currentT = strtotime(date('d-m-Y h:i:s A'));
$expireT = strtotime($data['expTime']);
if($currentT > $expireT){
	echo "<h2>Link expired</h2>";
	echo "<p>Sorry, this password reset link has expired, note that will only be valid for <b>two hours</b></p>";
	echo '<hr />';
	echo '<a href="' . Inverse::root() . '/php/index.php' . '">Click here to the home page</a>';
	die();
}

$users_obj = new Users();
$users_obj->setId($data['id']);
$usr = $users_obj->getUserById();
// secure by token
if($usr['token'] != $data['token']){
	echo "<h2>User identifiers verfication failed</h2>";
	echo "<p>Sorry, please try again</b></p>";
	echo '<hr />';
	echo '<a href="' . Inverse::root() . '/php/index.php' . '">Click here to the home page</a>';
	die();
}

// throw id, catch in password.php as an entry to update
$session_handler = new Sess();
session_start();
$_SESSION['pass'] = array('id' => $data['id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>PetsKeepers Password Reset</title>
	<!-- css standard module -->
	<link rel="stylesheet" type="text/css" href="../css/bootstrap-4.0.0.css" />
	<!-- project style sheets -->
	<link rel="stylesheet" type="text/css" href="../css/form.css" />
	<link rel="stylesheet" type="text/css" href="../css/reset.css" />
	<!-- javascript stardard modules -->
	<script type="text/javascript" src="../js/jquery-3.5.1.js"></script>
</head>
<body>

<div class='services'>
<img id='loader' src="../media/loader.gif" />
<?php include_once('../html/tform.html'); ?>
</div>

<script type="text/javascript" src="../js/reset.js"></script>
</body>
</html>

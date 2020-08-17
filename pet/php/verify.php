<?php 
/*
AUTHOR: Messiz Qin
GITHUB: https://github.com/Weilory
PROJECT: PetsKeepers
*/

/*
after user click on verify link they get from email
open a new page to show the account status
*/

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>PetKeeper Verify account</title>
</head>
<body>

<?php

require_once('../php/users.php');

// user id is encrypted to prevent self url activation attempts
$id = $_GET['id'];
$token = $_GET['token'];

$objUser = new Users();
$objUser->setId($id);

function verify_fail($str){
	echo "<div style='margin:10px;padding:10px;font-size:20px;color:#fc4f30;border:1px solid; background-color:#eee; display:inline-block;border-radius:20px;'>Sorry! " . $str . ".</div><hr />";
}

function verify_success($str){
	echo "<div style='margin:10px;padding:10px;font-size:20px;color:#00bfd5;border:1px solid; background-color:#eee; display:inline-block;border-radius:20px;'>Congratulations! " . $str . ".</div><hr />";
}

$user = $objUser->getUserById();
// success only under circumstances of:
//     encrypted id matches token
//     user is stored in the database
//     internet connected
if(is_array($user) && count($user)>0){
	if(sha1($user['id']) == $token){
		if($objUser->activateUserAccount()){
			verify_success('Your account has been activated, you can now log in');
		}else{
			verify_fail('something went wrong in activation');
		}
	}else{
		verify_fail('token failed');
	}
}else{
	verify_fail('user not find');
}


?>

</body>
</html>
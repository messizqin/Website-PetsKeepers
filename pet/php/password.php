<?php 

/*
AUTHOR: Messiz Qin
GITHUB: https://github.com/Weilory
PROJECT: PetsKeepers
*/

/*
action.php ajax subhandler for password reset
update user encrypted user password to databsae by Users::UpdatePassword
*/

if(isset($_POST['password'])){
	require_once('../php/users.php');
	require_once('../php/sess.php');
	$session_handler = new Sess();
	session_start();
	$users_obj = new Users();
	$users_obj->setId($_SESSION['pass']['id']);
	$users_obj->setPassword(md5($_POST['password']));
	unset($_SESSION['pass']);
	if($users_obj->updatePassword()){
		// throw the redirect location to reset.js->ajax.done
		require_once('../php/inverse.php');
		echo json_encode(['status' => 1, 'msg' => 'Succeed', 'location' => Inverse::phpd('index')]);
	}else{
		echo json_encode(['status' => 0, 'msg' => 'update new password failed']);
	}
}

?>

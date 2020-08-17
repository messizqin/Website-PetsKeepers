<?php

/*
AUTHOR: Messiz Qin
GITHUB: https://github.com/Weilory
PROJECT: PetsKeepers
*/

/*
subhandler for action.php
bridge admin.js between users/dates/pets.php modules
*/

if(isset($_POST['table'])){
	switch($_POST['table']){
		case 'users':
			try{
				require_once('../php/users.php');
				$users_obj = new Users();
				$users_obj->update($_POST['id'], $_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['phone']);
				echo 'success';
			}catch(Exception $e){
				echo $e->getMessage();
			}
		break; 

		case 'dates':
			try{
				require_once('../php/dates.php');
				$dates_obj = new Dates();
				$dates_obj->update($_POST['id'], $_POST['sendin'], $_POST['pickup'], $_POST['duration']);
				echo 'success';
			}catch(Exception $e){
				echo $e->getMessage();
			}
		break;

		case 'pets':
			try{
				require_once('../php/pets.php');
				$pets_obj = new Pets();
				$pets_obj->update($_POST['id'], $_POST['category'], $_POST['petname'], $_POST['petage'], $_POST['petweight']);
				echo 'success';
			}catch(Exception $e){
				echo $e->getMessage();
			}
		break;
	}
}

?>
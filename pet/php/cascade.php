<?php 

/*
AUTHOR: Messiz Qin
GITHUB: https://github.com/Weilory
PROJECT: PetsKeepers
*/

/*
admin.js delete archive
ajax subhandler
deletion to one record leads to cascading deletion to all belonged in that one-to-many relationship, this is achieved in modules
*/

if(isset($_POST['table'])){
	switch($_POST['table']){
		case 'users':
			require_once('../php/users.php');
			$users_obj = new Users();
			$users_obj->delete($_POST['id']);
		break; 

		case 'dates':
			require_once('../php/dates.php');
			$dates_obj = new Dates();
			$dates_obj->delete($_POST['id']);
		break; 

		case 'pets':
			require_once('../php/pets.php');
			$pets_obj = new Pets();
			$pets_obj->delete($_POST['id']);
		break; 
	}
}

?>
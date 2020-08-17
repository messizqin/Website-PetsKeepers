<?php 
/*
AUTHOR: Messiz Qin
GITHUB: https://github.com/Weilory
PROJECT: PetsKeepers
*/

/*
subhandler for action.php
pass in Dates and Pets objects recorded in $_POST['dataframe'] by ajax
save into database.
*/

require_once('../php/dates.php');
require_once('../php/pets.php');

// convert date to milliseconds: * 86400
function ms2d($ms){return abs(intval($ms/86400));}

// One-to-Many: one user corresponds to many dates
if(isset($_POST['dataframe'])){
	$date_obj = new Dates();
	$date_obj->setUser_id($_POST['dataframe']['user_id']);
	$date_obj->setBooked(date('Y-m-d', $_POST['dataframe']['startdate']));
	$date_obj->setSendin(date('Y-m-d', $_POST['dataframe']['sendin']));
	$date_obj->setPickup(date('Y-m-d', $_POST['dataframe']['pickup']));
	// calculate duration in units of day, by two dates in milliseconds
	$date_obj->setDuration(ms2d($_POST['dataframe']['pickup']- $_POST['dataframe']['sendin']));
	$date_obj->save();
	$date_id = $date_obj->lastestId();
	// One-to-Many: one date corresponds to many pets
	foreach($_POST['dataframe']['pets'] as $pet){
		$pet_obj = new Pets();
		$pet_obj->setDate_id($date_id);
		$pet_obj->setCategory($pet['category']);
		$pet_obj->setPetname($pet['petname']);
		$pet_obj->setPetage($pet['petage']);
		$pet_obj->setPetweight($pet['petweight']);
		$pet_obj->save();
	}
	echo 'succeeded';
}

?>
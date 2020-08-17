<?php

/*
AUTHOR: Messiz Qin
GITHUB: https://github.com/Weilory
PROJECT: PetsKeepers
*/

/*
subhanlder of action.php, called by ajax
pass in the user id
return a string which is a table containing all booking of the user 
*/

require_once('../php/users.php');
require_once('../php/dates.php');
require_once('../php/pets.php');
require_once('../php/inverse.php');

// only execute if the user id is set. 
if(isset($_POST['infill'])){
	$toecho = "<div class='tobook-article'>";
	$date_obj = new Dates();
	// $_POST['infill'] is user_id, in javascript, defined in mydude 
	$date_obj->setUser_id($_POST['infill']);
	$date_datas = $date_obj->getDateByUserId();

	// date counter
	$idcounter = 0;
	foreach($date_datas as $date_data){
		$idcounter++;
		// date table header
		$toecho .= "<table id='book-date' class='table table-hover'><thead><tr>";
		$toecho .= '<th scope="col">#Date</th>';
		$toecho .= '<th scope="col">Booked</th>';
		$toecho .= '<th scope="col">Send-In</th>';
		$toecho .= '<th scope="col">Pick-up</th>';
		$toecho .= '<th scope="col">Duration</th>';
		$toecho .= '</tr></thead>';
		$toecho .= '<tbody><tr>';
		// date table content
		$toecho .= '<th scope="row">' . $idcounter . '</th>';
		$toecho .= '<td>' . $date_data['booked'] . '</td>';
		$toecho .= '<td>' . $date_data['sendin'] . '</td>';
		$toecho .= '<td>' . $date_data['pickup'] . '</td>';
		$toecho .= '<td>' . $date_data['duration'] . '</td>';
		$toecho .= '</tr></tbody></table>';

		// create one pet object, use multiple assignment to get value
		$pet_obj = new Pets();
		$pet_obj->setDate_id($date_data['id']);
		$pet_datas = $pet_obj->getPetsByDateId();

		// pet table header
		$toecho .= '<table id="book-pet" class="table table-hover"><thead><tr>';
		$toecho .= '<th scope="col">#Pets</th>';
		$toecho .= '<th scope="col">Category</th>';
		$toecho .= '<th scope="col">Name</th>';
		$toecho .= '<th scope="col">Age</th>';
		$toecho .= '<th scope="col">Weight</th>'; 
		$toecho .= '</tr></thead>';

		// pet table content
		$petcounter = 0;
		foreach($pet_datas as $pet_data){
			$petcounter++;
			$toecho .= '<tbody><tr>';
			$toecho .= '<th scope="row">' . $petcounter . '</th>';
			$toecho .= '<td>' . $pet_data['category'] . '</td>';
			$toecho .= '<td>' . $pet_data['petname'] . '</td>';
			$toecho .= '<td>' . $pet_data['petage'] . '</td>';
			$toecho .= '<td>' . $pet_data['petweight'] . '</td>';
		}
		$toecho .= '</tr></tbody></table>';
		$toecho .= "<hr />";
	}
	$toecho .= '</div>';
	// data can be retrieved by json_decode
	echo json_encode(['htm' => $toecho]);
}
?>


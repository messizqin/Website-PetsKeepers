<?php 

/*
AUTHOR: Messiz Qin
GITHUB: https://github.com/Weilory
PROJECT: PetsKeepers
*/

/*
ajax subhandler called from parser.php
generate pets table
*/

if(isset($_POST['type']) && $_POST['type'] == 'general'){
	require_once('../php/db_connect.php');
	require_once('../php/table_func.php');

	$dbc = new DBConnect();
	$conn = $dbc->connect();

	$pet_sql = "SELECT * FROM pets ORDER BY " . $_POST['order'] . " " . $_POST['seq'];
	$pet_pdo = $conn->query($pet_sql);

	$pet_table_headers = array('Category', 'Pet Name', 'Pet Age', 'Weight');
	$pet_table_cells = array();

	while($row = $pet_pdo->fetch()){
		array_push($pet_table_cells, $row['category'], $row['petname'], $row['petage'], $row['petweight']);
	}

	echo json_encode(['htm' => table_html($pet_table_headers, $pet_table_cells, 'pets', array('category', 'petname', 'petage', 'petweight'))]);
}

if(isset($_POST['type']) && $_POST['type'] == 'dates'){
	require_once('../php/db_connect.php');
	require_once('../php/table_func.php');

	$dbc = new DBConnect();
	$conn = $dbc->connect();

	$user_sql = "SELECT * FROM pets WHERE date_id=" . $_POST['dates_id'] . " ORDER BY " . $_POST['order'] . " " . $_POST['seq'];
	$user_pdo = $conn->query($user_sql);

	$user_table_headers = array('id', 'Category', 'Name', 'Age', 'Weight');
	$user_table_cells = array();

	while($row = $user_pdo->fetch()){
		array_push($user_table_cells, $row['id'], $row['category'], $row['petname'], $row['petage'], $row['petweight']);
	}

	echo json_encode(['htm' => table_html($user_table_headers, $user_table_cells, 'pets', array('category', 'petname', 'petage', 'petweight'))]);
}

?>

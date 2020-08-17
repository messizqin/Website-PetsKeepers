<?php 

/*
AUTHOR: Messiz Qin
GITHUB: https://github.com/Weilory
PROJECT: PetsKeepers
*/

/*
ajax subhandler called from parser.php
generate dates table
*/

if(isset($_POST['type']) && $_POST['type'] == 'general'){
	require_once('../php/db_connect.php');
	require_once('../php/table_func.php');

	$dbc = new DBConnect();
	$conn = $dbc->connect();

	$date_sql = "SELECT * FROM dates ORDER BY " . $_POST['order'] . " " . $_POST['seq'];
	$date_pdo = $conn->query($date_sql);

	$date_table_headers = array('Booked', 'Send In', 'Pick Up', 'Duration');
	$date_table_cells = array();

	while($row = $date_pdo->fetch()){
		array_push($date_table_cells, $row['booked'], $row['sendin'], $row['pickup'], $row['duration']);
	}

	echo json_encode(['htm' => table_html($date_table_headers, $date_table_cells, 'dates', array('booked', 'sendin', 'pickup', 'duration'))]);
}

if(isset($_POST['type']) && $_POST['type'] == 'users'){
	require_once('../php/db_connect.php');
	require_once('../php/table_func.php');

	$dbc = new DBConnect();
	$conn = $dbc->connect();

	$date_sql = "SELECT * FROM dates WHERE user_id=" . $_POST['users_id'] . " ORDER BY " . $_POST['order'] . " " . $_POST['seq'];
	$date_pdo = $conn->query($date_sql);

	$date_table_headers = array('id', 'Booked', 'Send In', 'Pick Up', 'Duration');
	$date_table_cells = array();

	while($row = $date_pdo->fetch()){
		array_push($date_table_cells, $row['id'], $row['booked'], $row['sendin'], $row['pickup'], $row['duration']);
	}

	echo json_encode(['htm' => table_html($date_table_headers, $date_table_cells, 'dates', array('booked', 'sendin', 'pickup', 'duration'))]);
}

?>
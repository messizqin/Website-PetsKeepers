<?php

/*
AUTHOR: Messiz Qin
GITHUB: https://github.com/Weilory
PROJECT: PetsKeepers
*/

/*
ajax subhandler called from parser.php
generate users table
*/

if(isset($_POST['type']) && $_POST['type'] == 'general'){
	require_once('../php/db_connect.php');
	require_once('../php/table_func.php');

	$dbc = new DBConnect();
	$conn = $dbc->connect();

	$user_sql = "SELECT * FROM users ORDER BY " . $_POST['order'] . " " . $_POST['seq'];
	$user_pdo = $conn->query($user_sql);

	$user_table_headers = array('id', 'Firstname', 'Lastname', 'Email', 'Phone');
	$user_table_cells = array();

	while($row = $user_pdo->fetch()){
		array_push($user_table_cells, $row['id'], $row['first_name'], $row['last_name'], $row['email'], $row['phone']);
	}

	echo json_encode(['htm' => table_html($user_table_headers, $user_table_cells, 'users', array('firstname', 'lastname', 'email', 'phone'))]);
}

?>
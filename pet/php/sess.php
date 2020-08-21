<?php
/*
AUTHOR: Messiz Qin
GITHUB: https://github.com/Weilory
PROJECT: PetsKeepers
*/

// require this file and instanitiate DbSessionHandler class before session_start();
// do not use window.location.href = '', use window.location.replace('') instead, otherwise it will be regarded as a new request, and a new sesison will be created. 

require_once('DbSessionHandler.php');
class Sess extends DbSessionHandler {
	protected $pdo_data_source_name = 'mysql:dbname=petkeepers;host=localhost';
	protected $pdo_username = 'root';
	protected $pdo_password = 'root';
	protected $session_db_table = 'sessions';
	protected $session_name = 'admin';
}

?>
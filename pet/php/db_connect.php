<?php 
	/*
	AUTHOR: Messiz Qin
	GITHUB: https://github.com/Weilory
	PROJECT: PetsKeepers
	*/

	/*
	Database Connection
	*/
	class DBConnect{
		private $host = 'localhost';
		private $dbname = 'petkeepers';
		private $user = 'root';
		private $pass = 'root';

		// PDO connection
		public function connect(){
			try{
				$conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbname, $this->user, $this->pass);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				return $conn;
			}catch(PDOException $e){
				echo 'Database connection failed: ' . $e->getMessage();
			}
		}

		// return Boolean, testify if user email has been registered
		public function validate_existing_email($new_email){
			$conn = mysqli_connect($this->host, $this->user, $this->pass);
			mysqli_select_db($conn, $this->dbname);
			if(!$conn){
				echo 'There is no database connection';
				exit();
			}
			$data = mysqli_query($conn, 'SELECT * FROM users');
			while($info = mysqli_fetch_array($data)){
				if($info['email'] == $new_email){
					return false; 
				}
			}
			return true; 
		}

		// return the last inserted id in the dates table		
		public function getNewDateId(){
			$conn = mysqli_connect($this->host, $this->user, $this->pass);
			mysqli_select_db($conn, $this->dbname);
			if(!$conn){
				echo 'There is no database connection';
				exit();
			}
			$data = mysqli_query($conn, "SELECT MAX(id) as maximum FROM dates");
			$row = mysqli_fetch_assoc($data);
			return $row['maximum'];
		}
	}
?>
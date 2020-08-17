<?php
	/*
	AUTHOR: Messiz Qin
	GITHUB: https://github.com/Weilory
	PROJECT: PetsKeepers
	*/

	/*
	one user corresponds to one record in the sql users table
	fields:
		id: auto increment
		first_name: first name of the user
		last_name: last name of the user
		email: email address of the user
		password: password of the user, always encrypted
		phone: phone number of the user in Australia
		activated: staus of user account, 0 for not activated, 1 for activated
		token: user id encrypted in base64
		created_on: user account created date in Y-m-d format
	ATTENTION: mysql functions are not allowed to be mixed with PDO connections
	*/

	// always use require_once instead of require
	// otherwise a 500 internal server error may occur
	require_once('../php/db_connect.php');

	class Users{
		// unaccessible class variables
		protected $id;
		protected $first_name;
		protected $last_name;
		protected $email;
		protected $password;
		protected $phone;
		protected $activated;
		protected $token;
		protected $created_on;
		// public database connection
		public $conn; 

		function setId($id) { $this->id = $id; }
		function getId() { return $this->id; }
		function setFirst_name($first_name) { $this->first_name = $first_name; }
		function getFirst_name() { return $this->first_name; }
		function setLast_name($last_name) { $this->last_name = $last_name; }
		function getLast_name() { return $this->last_name; }
		function setEmail($email) { $this->email = $email; }
		function getEmail() { return $this->email; }
		function setPassword($password) { $this->password = $password; }
		function getPassword() { return $this->password; }
		function setPhone($phone) { $this->phone = $phone; }
		function getPhone() { return $this->phone; }
		function setActivated($activated) { $this->activated = $activated; }
		function getActivated() { return $this->activated; }
		function setToken($token) { $this->token = $token; }
		function getToken() { return $this->token; }
		function setCreated_on($created_on) { $this->created_on = $created_on; }
		function getCreated_on() { return $this->created_on; }
		
		// Users constructor: establish a unique connection
		function __construct(){
			$db = new DbConnect();
			$this->conn = $db->connect();
		}

		// after settr func being called, use save to dump the data into database
		public function save(){
			$sql = "INSERT INTO `users`(`id`, `first_name`, `last_name`, `email`, `password`, `phone`, `activated`, `token`, `created_on`) VALUES (NULL,:first_name,:last_name,:email,:password,:phone,:activated,:token,:created_on)";
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(':first_name', $this->first_name);
			$stmt->bindParam(':last_name', $this->last_name);
			$stmt->bindParam(':email', $this->email);
			$stmt->bindParam(':password', $this->password);
			$stmt->bindParam(':phone', $this->phone);
			$stmt->bindParam(':activated', $this->activated);
			$stmt->bindParam(':token', $this->token);
			$stmt->bindParam(':created_on', $this->created_on);
			try{
				if($stmt->execute()){
					return true;
				}else{
					return false;
				}
			}catch(Exception $e){
				echo $e->getMessage();
			}
		}

		// after useerid settr being run, getUserById returns a user object retrieved from database by userid attribute
		public function getUserById(){
			$stmt = $this->conn->prepare('SELECT * FROM users WHERE id = :id');
			$stmt->bindParam(':id', $this->id);
			try{
				if($stmt->execute()){
					$user = $stmt->fetch(PDO::FETCH_ASSOC);
				}
			}catch(Exception $e){
				echo $e->getMessage();
			}
			return $user;
		}

		// after user click on registration link they received from email, activateUserAccount update activated from 0 to one
		public function activateUserAccount(){
			$stmt = $this->conn->prepare('UPDATE users SET activated = 1 WHERE id = :id');
			$stmt->bindParam(':id', $this->id);
			try{
				if($stmt->execute()){
					return true;
				}else{
					return false;
				}
			}catch(Exception $e){
				echo $e->getMessage();
			}
		}

		// email, id, token are three unique identifiers of a Users object
		public function getUserByEmail(){
			$stmt = $this->conn->prepare('SELECT * FROM users WHERE email = :email');
			$stmt->bindParam(':email', $this->email);
			try{
				if($stmt->execute()){
					$user = $stmt->fetch(PDO::FETCH_ASSOC);
				}
			}catch(Exception $e){
				echo $e->getMessage();
			}
			return $user;
		}

		// admin edit function support, update single user record
		public function update($id, $firstname, $lastname, $email, $phone){
			$stmt = $this->conn->prepare('UPDATE users SET first_name = :firstname, last_name = :lastname, email = :email, phone = :phone WHERE id = :id');
			$stmt->bindParam(':firstname', $firstname);
			$stmt->bindParam(':lastname', $lastname);
			$stmt->bindParam(':email', $email);
			$stmt->bindParam(':phone', $phone);
			$stmt->bindParam(':id', $id);
			try{
				$stmt->execute();
			}catch(Exception $e){
				echo $e->getMessage();
			}
		}

		// delete to user lead to delete to all belonged date, together with all booked pets of that user
		public function delete($id){
			require_once('../php/dates.php');
			$stmt = $this->conn->prepare('DELETE FROM users WHERE id = :id');
			$stmt->bindParam(':id', $id);
			try{
				$stmt->execute();
				$dates_obj = new Dates();
				$dates_obj->setUser_id($id);
				$dates = $dates_obj->getDateByUserId();
				foreach($dates as $date){
					$dates_obj->delete($date['id']);
				}
			}catch(Exception $e){
				echo $e->getMessage();
			}
		}

		// after user click on password reset link they received from email, secure this process by assigning a token into database and check it two matches
		public function updateToken(){
			$stmt = $this->conn->prepare('UPDATE users SET token = :token WHERE id = :id');
			$stmt->bindParam(':token', $this->token);
			$stmt->bindParam(':id', $this->id);
			try{
				if($stmt->execute()){
					return true;
				}else{
					return false;
				}
			}catch(Exception $e){
				echo $e->getMessage();
			}
		}

		public function updatePassword(){
			$stmt = $this->conn->prepare('UPDATE users SET password = :password WHERE id = :id');
			$stmt->bindParam(':password', $this->password);
			$stmt->bindParam(':id', $this->id);
			try{
				if($stmt->execute()){
					return true;
				}else{
					return false;
				}
			}catch(Exception $e){
				echo $e->getMessage();
			}
		}
	}
?>






















<?php 
	/*
	AUTHOR: Messiz Qin
	GITHUB: https://github.com/Weilory
	PROJECT: PetsKeepers
	*/

	/*
	one Dates() object corresponds to one record of dates table in database
	Users and Dates are in One-to-Many relationship
	fields: 
		id: auto increment
		user_id: the id of user who owns this date record
		booked: the date this requestion is submitted
		sendin: the planning date that the user will be sending the pet
		pickup: the scheduling date that the user will be picking up the pet
		duration: days between sendin and pickup
	*/

	require_once('../php/db_connect.php');

	class Dates{
		protected $id;
		protected $user_id;
		protected $booked;
		protected $sendin;
		protected $pickup;
		protected $duration;
		public $conn;

		function setId($id) { $this->id = $id; }
		function getId() { return $this->id; }
		function lastestId(){ $dbc = new DBConnect(); return $dbc->getNewDateId(); }
		function setUser_id($user_id) { $this->user_id = $user_id; }
		function getUser_id() { return $this->user_id; }
		function setBooked($booked) { $this->booked = $booked; }
		function getBooked() { return $this->booked; }
		function setSendin($sendin) { $this->sendin = $sendin; }
		function getSendin() { return $this->sendin; }
		function setPickup($pickup) { $this->pickup = $pickup; }
		function getPickup() { return $this->pickup; }
		function setDuration($duration) { $this->duration = $duration; }
		function getDuration() { return $this->duration; }

		// User/Dates/Pets, each object corresponds to one unique database connection
		function __construct(){
			$db = new DbConnect();
			$this->conn = $db->connect();
		}

		// save into dates table within all attributes
		public function save(){
			$sql = "INSERT INTO `dates`(`id`, `user_id`, `booked`, `sendin`, `pickup`, `duration`) VALUES (NULL,:user_id,:booked,:sendin,:pickup,:duration)";
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(':user_id', $this->user_id);
			$stmt->bindParam(':booked', $this->booked);
			$stmt->bindParam(':sendin', $this->sendin);
			$stmt->bindParam(':pickup', $this->pickup);
			$stmt->bindParam(':duration', $this->duration);
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

		// get all booked dates of a certain user
		public function getDateByUserId(){
			$stmt = $this->conn->prepare('SELECT * FROM dates WHERE user_id = :user_id');
			$stmt->bindParam(':user_id', $this->user_id);
			try{
				if($stmt->execute()){
					$dts = $stmt->fetchAll(PDO::FETCH_ASSOC);
				}
			}catch(Exception $e){
				echo $e->getMessage();
			}
			return $dts;
		}

		// admin edit support, convert js date object to seconds and store into database
		public function update($id, $sendin, $pickup, $duration){
			$stmt = $this->conn->prepare('UPDATE dates SET sendin = :sendin, pickup = :pickup, duration = :duration WHERE id = :id');
			$sdate = date('Y-m-d H:i:s', $sendin);
			$pdate = date('Y-m-d H:i:s', $pickup);
			$stmt->bindParam(':sendin', $sdate);
			$stmt->bindParam(':pickup', $pdate);
			$stmt->bindParam(':duration', $duration);
			$stmt->bindParam(':id', $id);
			try{
				$stmt->execute();
			}catch(Exception $e){
				echo $e->getMessage();
			}
		}

		// delete to date leads to deletion to all pet booking that date
		public function delete($id){
			require_once('../php/pets.php');
			$stmt = $this->conn->prepare('DELETE FROM dates WHERE id = :id');
			$stmt->bindParam(':id', $id);
			try{
				$stmt->execute();
				$pets_obj = new Pets();
				$pets_obj->setDate_id($id);
				$pets = $pets_obj->getPetsByDateId();
				foreach($pets as $pet){
					$pets_obj->delete($pet['id']);
				}
			}catch(Exception $e){
				echo $e->getMessage();
			}
		}
	}

?>
<?php 
	/*
	AUTHOR: Messiz Qin
	GITHUB: https://github.com/Weilory
	PROJECT: PetsKeepers
	*/

	/*
	fields:
		id: auto increment
		date_id: Dates and Pets are in One-to-Many relationship
		category: category of the pet, usually cat or dog
		petage: pet age as a natural number
		petweight: weight of the pet
	*/

	require_once('../php/db_connect.php');

	class Pets{
		protected $id;
		protected $date_id;
		protected $category;
		protected $petname;
		protected $petage;
		protected $petweight;
		public $conn;

		function setId($id) { $this->id = $id; }
		function getId() { return $this->id; }
		function setDate_id($date_id) { $this->date_id = $date_id; }
		function getDate_id() { return $this->date_id; }
		function setCategory($category) { $this->category = $category; }
		function getCategory() { return $this->category; }
		function setPetname($petname) { $this->petname = $petname; }
		function getPetname() { return $this->petname; }
		function setPetage($petage) { $this->petage = $petage; }
		function getPetage() { return $this->petage; }
		function setPetweight($petweight) { $this->petweight = $petweight; }
		function getPetweight() { return $this->petweight; }

		function __construct(){
			$db = new DbConnect();
			$this->conn = $db->connect();
		}

		public function save(){
			$sql = "INSERT INTO `pets`(`id`, `date_id`, `category`, `petname`, `petage`, `petweight`) VALUES (NULL,:date_id,:category,:petname,:petage,:petweight)";
			$stmt = $this->conn->prepare($sql);
			$stmt->bindParam(':date_id', $this->date_id);
			$stmt->bindParam(':category', $this->category);
			$stmt->bindParam(':petname', $this->petname);
			$stmt->bindParam(':petage', $this->petage);
			$stmt->bindParam(':petweight', $this->petweight);
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

		// return the all pets object belong to the certain date id
		// date id does not mean the same day since it's generated from auto increment
		public function getPetsByDateId(){
			$stmt = $this->conn->prepare('SELECT * FROM pets WHERE date_id = :date_id');
			$stmt->bindParam(':date_id', $this->date_id);
			try{
				if($stmt->execute()){
					$pts = $stmt->fetchAll(PDO::FETCH_ASSOC);
				}
			}catch(Exception $e){
				echo $e->getMessage();
			}
			return $pts;
		}

		// admin edit func support, update single pet record
		public function update($id, $category, $petname, $petage, $petweight){
			$stmt = $this->conn->prepare('UPDATE pets SET category = :category, petname = :petname, petage = :petage, petweight = :petweight WHERE id = :id');
			$stmt->bindParam(':category', $category);
			$stmt->bindParam(':petname', $petname);
			$stmt->bindParam(':petage', $petage);
			$stmt->bindParam(':petweight', $petweight);
			$stmt->bindParam(':id', $id);
			try{
				$stmt->execute();
			}catch(Exception $e){
				echo $e->getMessage();
			}
		}

		// remove single pet record
		public function delete($id){
			$stmt = $this->conn->prepare('DELETE FROM pets WHERE id = :id');
			$stmt->bindParam(':id', $id);
			try{
				$stmt->execute();
			}catch(Exception $e){
				echo $e->getMessage();
			}
		}
	}

?>
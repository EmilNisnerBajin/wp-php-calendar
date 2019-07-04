<?php

require_once ("User.php");
require_once ("Task.php");
require_once ("Day.php");
require_once ("Week.php");
require_once("constants.php");
require_once ("Income.php");
require_once ("Expense.php");
require_once("constants.php");

	class DBUtils{

		private $conn;

		public function __construct($fileName){
			$data = parse_ini_file($fileName);
			$username = $data["user"];
			$password = $data["password"];
			$host = $data["host"];
			$database = $data["database"];
			$dns = "mysql:host=$host;dbname=$database";
			$this->conn = new PDO($dns,$username,$password);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		public function __destruct(){
			$conn = null;
		}
		// Every user gets registered with height, width and picture field set to null
		public  function registerUser($user){
			$sqlStatement = "INSERT INTO ".TBL_USER." (".COL_USER_USERNAME.", ".COL_USER_PASSWORD.", ".COL_USER_FIRSTNAME.", ".COL_USER_LASTNAME.", ".COL_USER_ADDRESS.", ".COL_USER_CITY.", ".COL_USER_COUNTRY.", ".COL_USER_POSTCODE.", ".COL_USER_BIRTHDAY.", ".COL_USER_GENDER.", ".COL_USER_EMAIL.") VALUES (:username, :password ,:firstname, :lastname, :address, :city , :country, :postcode, :birthday, :gender, :email);";
			$result = false;
			try{
				$query = $this->conn->prepare($sqlStatement);
				$query->bindValue(":username",$user->getUsername(),PDO::PARAM_STR);
				$query->bindValue(":password",$user->getPassword(),PDO::PARAM_STR);
				$query->bindValue(":firstname",$user->getFirstName(),PDO::PARAM_STR);
				$query->bindValue(":lastname",$user->getLastName(),PDO::PARAM_STR);
				$query->bindValue(":address",$user->getAddress(),PDO::PARAM_STR);
				$query->bindValue(":city",$user->getCity(),PDO::PARAM_STR);
				$query->bindValue(":country",$user->getCountry(),PDO::PARAM_STR);
				$query->bindValue(":postcode",$user->getPostcode(),PDO::PARAM_STR);
				$query->bindValue(":birthday",$user->getBirthday(),PDO::PARAM_STR);
				$query->bindValue(":gender",$user->getGender(),PDO::PARAM_STR);
				$query->bindValue(":email",$user->getEmail(),PDO::PARAM_STR);
				$query->bindValue(":firstname",$user->getFirstName(),PDO::PARAM_STR);
				$result = $query->execute();
			}catch(PDOException $e){
				//echo "Query failed: register User". $e->getMessage();
			}finally{
				if($result){
					$username = $user->getUsername();
					mkdir("images/users/$username",0777,true);
				}
				return $result;
			}
		}

		// Adding taks in DB
		public function addTask($task,$user){
			$userId = $this->getUserId($user);
			$result = false;
			if($userId == null){
				return $result;
			}else{
				$sqlStatement = "INSERT INTO ".TBL_TASK." (".COL_TASK_NAME.", ".COL_TASK_USERID.", ".COL_TASK_STARTDATE.", ".COL_TASK_ENDDATE.", ".COL_TASK_TIME.", ".COL_TASK_IMPORTANCE.", ".COL_TASK_REPEAT." ) VALUES (:name, :userId, :startDate, :endDate, :time, :importance, :repeat);";
				try{
					$query = $this->conn->prepare($sqlStatement);
					$query->bindValue(":name",$task->getName(),PDO::PARAM_STR);
					$query->bindValue(":userId",$userId,PDO::PARAM_STR);
					$query->bindValue(":startDate",$task->getStartDate(),PDO::PARAM_STR);
					$query->bindValue(":endDate",$task->getEndDate(),PDO::PARAM_STR);
					$query->bindValue(":time",$task->getTime(),PDO::PARAM_STR);
					$query->bindValue(":importance",$task->getImportance(),PDO::PARAM_STR);
					$query->bindValue(":repeat",$task->getRepeat(),PDO::PARAM_STR);
					$result = $query->execute();
				}catch(PDOException $e){
					//echo "Query failed: add task ". $e->getMessage();
				}finally{
					return $result;
				}
			}
		}

		// Get all user tasks in array
		public function getAllTasks($user){
			$userId = $this->getUserId($user);
			$tasks = array();
			$sqlStatement = "SELECT * FROM ".TBL_TASK." t WHERE t.".COL_TASK_USERID." like :userId;";
			try{
				$query = $this->conn->prepare($sqlStatement);
				$query->bindValue(":userId",$userId,PDO::PARAM_STR);
				$query->execute();
				$data = $query->fetch();
				while($data != null){
					$task = new Task($data);
					$tasks[] = $task;
					$data = $query->fetch();
				}
			}catch(PDOException $e){
				//echo "Query failed: get all tasks".$e->getMessage();
			}finally{
				return $tasks;
			}
		}

		public function getAllPeriods($user){
			$userId = $this->getUserId($user);
			$periods = array();
			$sqlStatement = "SELECT * FROM ".TBL_PRIOD." p WHERE p.".COL_PERIOD_USERID." like :userId;";
			try{
				$query = $this->conn->prepare($sqlStatement);
				$query->bindValue(":userId",$userId,PDO::PARAM_STR);
				$query->execute();
				$data = $query->fetch();
				while($data != null){
					$period = new Period($data[COL_PERIOD_MS], $data[COL_PERIOD_ME], $user);
					$periods[] = $period;
					$data = $query->fetch();
				}
			}catch(PDOException $e){
				echo "Query failed: get all periods".$e->getMessage();
			}finally{
				return $periods;
			}
		}

		// Get tasks in range
		public function getTasksInRange($startDate, $endDate, $userId){
			$sqlStatement = "SELECT * FROM ".TBL_TASK." t where ((t.".COL_TASK_STARTDATE." between :startDate and :endDate) or (t.".COL_TASK_ENDDATE." between :startDate and :endDate) or (t.".COL_TASK_STARTDATE." < :startDate and t.".COL_TASK_ENDDATE." > :endDate) or ( t.".COL_TASK_ENDDATE." IS NULL or t.".COL_TASK_ENDDATE."=\"\")) AND ".COL_TASK_USERID."=:userId;"; // 
			$tasks = array();
			try{
				$query = $this->conn->prepare($sqlStatement);
				$query->bindValue(":startDate",$startDate,PDO::PARAM_STR);
				$query->bindValue(":endDate",$endDate,PDO::PARAM_STR);
				$query->bindValue(":userId",$userId,PDO::PARAM_STR);
				$query->execute();
				$data = $query->fetch();
				while($data != null){
					$task = new Task($data);
					$tasks[] = $task;
					$data = $query->fetch();
				}
			}catch(PDOException $e){
				//echo "Query failed: get Tasks in range ".$e->getMessage();
			}finally{
				return $tasks;
			}
		}

		// Update task
		public function updateTask($task){
			//print_r($task);
				$sqlStatement = "UPDATE ".TBL_TASK." SET ".COL_TASK_NAME."=:name, ".COL_TASK_STARTDATE."=:startDate, ".COL_TASK_ENDDATE."=:endDate, ".COL_TASK_TIME."=:time, ".COL_TASK_IMPORTANCE."=:importance, ".COL_TASK_REPEAT."=:repeat WHERE ".COL_TASK_ID."=:taskId;";
				$result = false;
				try{
					$query = $this->conn->prepare($sqlStatement);
					$query->bindValue(":name",$task->getName(),PDO::PARAM_STR);
					$query->bindValue(":startDate",$task->getStartDate(),PDO::PARAM_STR);
					$query->bindValue(":endDate",$task->getEndDate(),PDO::PARAM_STR);
					$query->bindValue(":time",$task->getTime(),PDO::PARAM_STR);
					$query->bindValue(":importance",$task->getImportance(),PDO::PARAM_STR);
					$query->bindValue(":repeat",$task->getRepeat(),PDO::PARAM_STR);
					$query->bindValue(":taskId",$task->getId(),PDO::PARAM_STR);
					$result = $query->execute();
				}catch(PDOException $e){
					//echo "Update task Query failed :".$e->getMessage();
				}finally{
					return $result;
			}
		}
	

		// Update User
		public function updateUser($tmp, $user){
				$userId = $this->getUserId($tmp);
				$result = false;
				if($userId) {
					$sqlStatement = "UPDATE ".TBL_USER." SET ".COL_USER_USERNAME."=:username, ".COL_USER_PASSWORD."=:password1, ".COL_USER_PASSWORD2."=:password2, ".COL_USER_EMAIL."=:email, ".COL_USER_BIRTHDAY."=:birthday, ".COL_USER_POSTCODE.":postcode, ".COL_USER_COUNTRY.":country, ".COL_USER_CITY.":city, ".COL_USER_ADDRESS.":address, ".COL_USER_LASTNAME.":lastname, ".COL_USER_FIRSTNAME.":firstname WHERE ".COL_USER_ID."=:userId;";
					
					try{
						$query = $this->conn->prepare($sqlStatement);
						$query->bindValue(":username",$user->getUsername(),PDO::PARAM_STR);
						$query->bindValue(":password1",$user->getPassword(),PDO::PARAM_STR);
						$query->bindValue(":password2",$user->getPassword(),PDO::PARAM_STR);
						$query->bindValue(":email",$user->getEmail(),PDO::PARAM_STR);
						$query->bindValue(":birthday",$user->getBirthday(),PDO::PARAM_STR);
						$query->bindValue(":postcode",$user->getPostcode(),PDO::PARAM_STR);
						$query->bindValue(":country",$user->getCountry(),PDO::PARAM_STR);
						$query->bindValue(":city",$user->getCity(),PDO::PARAM_STR);
						$query->bindValue(":address",$user->getAddress(),PDO::PARAM_STR);
						$query->bindValue(":lastname",$user->getLastName(),PDO::PARAM_STR);
						$query->bindValue(":firstname",$user->getFirstName(),PDO::PARAM_STR);
						$query->bindValue(":userId",$userId,PDO::PARAM_STR);
						$result = $query->execute();
					}catch(PDOException $e){
						echo "Update user Query failed :".$e->getMessage();
					}finally{
						return $result;
					}
				}
				return $result;

		}

		public function getTask($id){
			$sqlStatement = "SELECT * FROM ".TBL_TASK." t WHERE t.".COL_TASK_ID." like :taskId";
			try{
				$task = null;
				$query = $this->conn->prepare($sqlStatement);
				$query->bindValue(":taskId",$id,PDO::PARAM_STR);
				$result = $query->execute();
				$task = new Task($query->fetch());
			}catch(PDOException $e){
				//echo "Get task query failed: ".$e->getMessage();
			}finally{
				return $task;
			}
		}

		// Remove task from database
		public function removeTask($id){
			$sqlStatement = "DELETE FROM ".TBL_TASK." WHERE ".COL_TASK_ID." = :taskId";
			try{
				$result = false;
				$query = $this->conn->prepare($sqlStatement);
				$query->bindValue(":taskId",$id,PDO::PARAM_STR);
				$result = $query->execute();
			}catch(PDOException $e){
				//echo "Remove task query failed: ".$e->getMessage();
			}finally{
				return $result;
			}
		}


		public function getUserId($user){
			$sqlStatement = "SELECT ".COL_USER_ID." FROM ".TBL_USER." u WHERE u.".COL_USER_USERNAME." like :username and u.".COL_USER_PASSWORD." like :password;";
			$password = $user->getPassword();
			$username = $user->getUsername();
			$result = null;
			try{
				$query = $this->conn->prepare($sqlStatement);
				$query->bindValue(":username",$username,PDO::PARAM_STR);
				$query->bindValue(":password",$password,PDO::PARAM_STR);
				$query->execute();
				$data = $query->fetch();
				if(array_key_exists(COL_USER_ID, $data)){
					$result = $data[COL_USER_ID];
				}else{
					return null;
				}
			}catch(PDOException $e){
				//echo "Query failed getUserID: ".$e->getMessage();
			}finally{
				return $result;
			}
		}


		// Check if a username/password combination exists in database
		public  function checkLogin($username, $password){
			$sqlStatement = "SELECT * FROM ".TBL_USER." u where u.".COL_USER_USERNAME." like :username and u.".COL_USER_PASSWORD." like :password;";
			$result = false;
			try{
				$query = $this->conn->prepare($sqlStatement);
				$query->bindValue(":username",$username,PDO::PARAM_STR);
				$query->bindValue(":password",$password,PDO::PARAM_STR);
				$query->execute();
				$query->fetch() != null ? $result=true : $result=false;
			}catch(PDOException $e){
				//echo "Query failed: check login".$e->getMessage();
			} finally {
				return $result;
			}
		}


		// Return new User object
		public  function getUser($username){
			$sqlStatement = "SELECT * FROM ".TBL_USER." u where u.".COL_USER_USERNAME." like :username";
			$result = false;
			try{
				$query = $this->conn->prepare($sqlStatement);
				$query->bindValue(":username",$username,PDO::PARAM_STR);
				$query->execute();
				$data = $query->fetch();
				$user = new User($data);
				return $user;
			}catch(PDOException $e){
				//echo "Query failed: get user".$e->getMessage();
			}
		}

		//Pomocni metod za dobijanje user-a
		public static function getDummyUser(){
			$podaci = array();
			$podaci["username"] = "Kavasaki123";
			$podaci["password1"] = "sakyplaky";
			$podaci["password2"] = "sakyplaky";
			$podaci["firstName"] = "Kasas";
			$podaci["lastName"] = "Krugar";
			$podaci["address"] = "Put 123";
			$podaci["city"] = "Srpska Crnja";
			$podaci["postcode"] ="00000";
			$podaci["birthday"] = "01. 01. 2001.";
			$podaci["gender"] = "f";
			$podaci["email"] = "plaky123@kekin.com";
			$podaci["country"] = "Srbija";
			$podaci["height"] = 170;
			$podaci["weight"] = 60;
			$dummy = new User($podaci);
			return $dummy;
		}

		public static function getDummyWeek(){
			$data = array();
			$data["month"] = "May";
			$data["year"] = "2019";
			$data["startDate"] = "";
			$data["endDate"] = "";
			$data["days"] = array();

			// kreiranje taskova
			$t = array();
			$t["name"] = "Idi na faks";
			$t["startDate"]="13.05.2019";
			$t["endDate"] = "13.05.2019";
			$t["importance"] = 1;
			$t["repeat"] = true;
			$t["time"] = "8:00";
			$task1 = new Task($t);

			$t["name"] = "uci";
			$t["startDate"]="13.05.2019";
			$t["endDate"] = "13.05.2019";
			$t["importance"] = 3;
			$t["repeat"] = false;
			$t["time"] = "13:00";
			$task2 = new Task($t);

			$t["name"] = "Spavaj";
			$t["startDate"]="13.05.2019";
			$t["endDate"] = "13.05.2019";
			$t["importance"] = 2;
			$t["repeat"] = false;
			$t["time"] = "20:00";
			$task3 = new Task($t);

			//kreiranje day-a
			$day = new Day("Ponedeljak");
			$day->addTask($task1);
			$day->addTask($task2);
			$day->addTask($task3);

			//kreiranje week-a
			//$data["days"] = $day;

			$week = new Week($data);
			$week->addDay($day);

		
			return $week;
			
		}


		#~~~~~~~ Deo sa finansijama ~~~~~~~~#

		#Dodavanje novih prihoda
		public function addIncome($date, $source, $amount) {
			$sql = "INSERT INTO ".TBL_INCOME." (".COL_INCOME_DATE.", ".COL_INCOME_SOURCE.", ".COL_INCOME_AMOUNT.") VALUES (:d, :s, :a);";
			$temp = $this->conn;
			try {
				$query = $temp->prepare($sql);
				$query->bindValue(":d", date("Y-m-d", strtotime($date)), PDO::PARAM_STR); 
				$query->bindValue(":s", $source, PDO::PARAM_STR);
				$query->bindValue(":a", (int)$amount, PDO::PARAM_INT);
				return $query->execute();
			} catch (Exception $e) {
			}
			return false;
			
		}

		#Dodavanje novih troskova
		public function addExpense($date, $essential, $item, $amount) {
			$sql = "INSERT INTO ".TBL_EXP." (".COL_EXP_DATE.", ".COL_EXP_ESS.", ".COL_EXP_ITEM. ", ".COL_INCOME_AMOUNT.") VALUES (:d, :e, :i, :a);";
			try {
				$temp = $this->conn;
				$query = $temp->prepare($sql);
				$query->bindValue(":d", date("Y-m-d", strtotime($date)), PDO::PARAM_STR); 
				$query->bindValue(":e", $essential, PDO::PARAM_BOOL);
				$query->bindValue(":i", $item, PDO::PARAM_STR);
				$query->bindValue(":a", (int)$amount, PDO::PARAM_INT);
				return $query->execute();
			} catch (Exception $e) {
			}
			return false;
			
		}

		#Koliko je ukupno para korisnik zaradio
		public function showTotalIncome() {
			$sql = "SELECT sum(".COL_INCOME_AMOUNT.") FROM ".TBL_INCOME.";";
			$query = $this->conn->prepare($sql);
			$query->execute();
			$niz= $query->fetch();
			return $niz['sum(amount)'];
		}
		#Koliko je ukupno para korisnik potrosio
		public function showTotalExpense() {
			$sql = "SELECT sum(".COL_INCOME_AMOUNT.") FROM ".TBL_EXP.";";
			try {
				$query = $this->conn->prepare($sql);
				$query->execute();
				$niz= $query->fetch();
				return $niz['sum(amount)'];
			} catch (Exception $e) {
			}
			return false;
		}

		#Koliko trenutno ima para
		public function amountAvailable() {
			$inc = $this->showTotalIncome() + 0; #da bi ga gledao broj (posto je string inace)
			$exp = $this->showTotalExpense() + 0;
			$total = $inc - $exp; 
			return $total;
		}

		#Izlistavanje dana i meseci, prosledjujem ime tabele i da li se trazi mesec ili dan da ne bih ponovo duplirala kod
		# boje
		public function showDayMonth($table, $month=false) {
			$income = !strcmp($table, TBL_INCOME);
			if($income) {
				$sql = "SELECT DISTINCT " . COL_INCOME_DATE . " FROM " . TBL_INCOME ." ORDER BY ".COL_INCOME_DATE." DESC;";
			} else {
				$sql = "SELECT DISTINCT " . COL_EXP_DATE . " FROM " . TBL_EXP ." ORDER BY ".COL_EXP_DATE." DESC;";
			}
			try {
				$temp = $this->conn;
				$query = $temp->prepare($sql);
				$query->execute(); 
				$dates = $query->fetchAll();

				if($income){
					$monthWithTheLargestIncomeSum = $this->monthWithLargestIncomeSum($dates);
					$monthWithLargestSingleIncome = $this->monthWithLargestSingleIncome();
				}else{
					$monthWithTheLargestExpenseSum = $this->monthWithLargestExpenseSum($dates);
					$monthWithLargestSingleExpense = $this->monthWithLargestSingleExpense();
				}

				$changeColors = Functions::setChangeColors();

				if (!$month) {
					$print = "<div class='dan'>";
					foreach ($dates as $d) {
						$konkretanDatum = $d[0];
						if($income){
							$color = strcmp($monthWithLargestSingleIncome, $konkretanDatum) == 0 ? $changeColors['lsi'] : $changeColors['defCol'];
							$qstr = "inc=day";
						}else{
							$color = strcmp($monthWithLargestSingleExpense, $konkretanDatum) == 0 ? $changeColors['lse'] : $changeColors['defCol'];
							$qstr = "exp=day";
						}
						$print .= "<div style='width: 100px; height: 25px; background-color: $color; margin: 2px; display: inline-block;'>";
	      				$print .= "<a href='?{$qstr}&date={$konkretanDatum}'>{$konkretanDatum}</a>"; #ovo samo ubacuje datum u url, treba mi za sesiju
	    				$print .= "</div>";
					}
					$print .= "</div>";
					echo $print;
				} else {
					$pom = 0; #za proveru sa mesecima; da ne bih stampala 7 puta maj
					$print = "<div class='meseci'>";
					//$color = "rgb(244, 66, 131)";
					if($income){
						$monthWLSI = explode("-", $monthWithLargestSingleIncome)[1];
					}else{
						$monthWLSE = explode("-", $monthWithLargestSingleExpense)[1];
					}
					foreach ($dates as $d) {
						$mesec = explode("-", $d[0]);
						if($pom != (int)$mesec[1]) {
							if($income){
								$color =  (int)$mesec[1] == $monthWLSI? $changeColors['lsi'] : $changeColors['defCol'];
								$color = (int)$mesec[1] == (int)$monthWithTheLargestIncomeSum? $changeColors['lis'] : $color;
								$qstr = "inc=month";
							}else{
								$color =  (int)$mesec[1] == $monthWLSE? $changeColors['lse'] : $changeColors['defCol'];
								$color = (int)$mesec[1] == (int)$monthWithTheLargestExpenseSum? $changeColors['les'] : $color;
								$qstr = "exp=month";
							}
							$print .= "<div style='width: 100px; height: 100px; background-color: $color; margin: 2px; display: inline-block;'>";
	      					$print .= "<a href='?$qstr&date={$mesec[1]}'>{$mesec[1]}</a>";
	    					$print .= "</div>";
						}
						$pom = $mesec[1];
					}
					$print .= "</div>";
					echo $print;
				}	
			} catch (Exception $e) {
					
			}
			
			
	}

		#-----------------------------------------------------------------------------------
		#month with largest income -S-
		public function monthWithLargestSingleIncome(){
			try{
				$sqlMax = "SELECT max(". COL_INCOME_AMOUNT .") FROM " . TBL_INCOME . ";";
				$temp = $this->conn;
				$query = $temp->prepare($sqlMax);
				$query->execute();
				$max = $query->fetch();
				
				$sql = "SELECT " . COL_INCOME_DATE . " FROM " . TBL_INCOME . " WHERE " . COL_INCOME_AMOUNT . " = $max[0] ;";
				$query = $temp->prepare($sql);
				$query->execute();
				$maxDate = $query->fetch();
				
				//$month = explode("-", $maxDate[0])[1];
				echo "Largest single income: $max[0] <br>";
				return $maxDate[0];
			}catch(PDOException $e){

			}
		}

		public function monthWithLargestSingleExpense(){
			try{
				$sqlMax = "SELECT max(". COL_EXP_AMOUNT .") FROM " . TBL_EXP . ";";
				$temp = $this->conn;
				$query = $temp->prepare($sqlMax);
				$query->execute();
				$max = $query->fetch();
				
				$sql = "SELECT " . COL_EXP_DATE . " FROM " . TBL_EXP . " WHERE " . COL_EXP_AMOUNT . " = $max[0] ;";
				$query = $temp->prepare($sql);
				$query->execute();
				$maxDate = $query->fetch();
				
				//$month = explode("-", $maxDate[0])[1];
				echo "Largest single expense: $max[0] <br>";
				return $maxDate[0];
			}catch(PDOException $e){

			}
		}

		#month with largest income sum -S-
		public function monthWithLargestIncomeSum($dates){
			try{
				$max = 0;
				$maxMonth = '';
				foreach ($dates as $d) {
					$mesec = explode("-", $d[0]);
					$m = $this->monthsSumOfIncome($mesec[1]);
					if($m >= $max){
						$max = $m;
						$maxMonth = $mesec[1];
					}
				}
				//echo "Mesec: $maxMonth suma: $max";
				echo "Largest income sum: $max <br>";
				return $maxMonth;
			}catch(PDOException $e){

			}
		}

		public function monthWithLargestExpenseSum($dates){
			try{
				$max = 0;
				$maxMonth = '';
				foreach ($dates as $d) {
					$mesec = explode("-", $d[0]);
					$m = $this->monthsSumOfExpense($mesec[1]);
					if($m >= $max){
						$max = $m;
						$maxMonth = $mesec[1];
					}
				}
				//echo "Mesec: $maxMonth suma: $max";
				echo "Largest expense sum: $max <br>";
				return $maxMonth;
			}catch(PDOException $e){

			}
		}
		# select sum(amount) from income where dateOfIncome like '%-05-%'; -S-
		public function monthsSumOfIncome($month){
			try{
				$sql = "SELECT sum(". COL_INCOME_AMOUNT .") FROM " . TBL_INCOME . " WHERE " . COL_INCOME_DATE . " like :month;";
				$query = $this->conn->prepare($sql);
				$query->bindValue(":month", "%-{$month}-%",PDO::PARAM_STR);
				$query->execute();
				$sumOfIncome = $query->fetch()[0];
				
				return (int)$sumOfIncome;
			}catch(PDOException $e){

			}
		}
		public function monthsSumOfExpense($month){
			try{
				$sql = "SELECT sum(". COL_EXP_AMOUNT .") FROM " . TBL_EXP . " WHERE " . COL_EXP_DATE . " like :month;";
				$query = $this->conn->prepare($sql);
				$query->bindValue(":month", "%-{$month}-%",PDO::PARAM_STR);
				$query->execute();
				$sumOfIncome = $query->fetch()[0];
				
				return (int)$sumOfIncome;
			}catch(PDOException $e){

			}
		}

		public function listDaysIncome($date){
			try{
				$sql = "SELECT " . COL_INCOME_SOURCE . " , " . COL_INCOME_AMOUNT . " FROM " . TBL_INCOME ." WHERE " . COL_INCOME_DATE . " like :date ;";
				$query = $this->conn->prepare($sql);
				$query->bindValue(":date", "$date",PDO::PARAM_STR);
				$query->execute();
				$listOfIncomes = $query->fetchAll();
				echo "List of incomes for day: $date <br>
					<table border = 1'>
					<tr><th>Source</th><th>Amount</th></tr>";
				foreach ($listOfIncomes as $inc) {
					echo "<tr><td>$inc[0]</td> <td>$inc[1]</td></tr>";
				}
				echo "</table>";
			}catch(PDOException $e){

			}
		}

		public function listMonthsIncome($date){
			try{
				$sql = "SELECT " . COL_INCOME_DATE . " , ". COL_INCOME_SOURCE . " , " . COL_INCOME_AMOUNT . " FROM " . TBL_INCOME ." WHERE " . COL_INCOME_DATE . " like :date ;";

				$query = $this->conn->prepare($sql);
				$query->bindValue(":date", "%-{$date}-%",PDO::PARAM_STR);
				$query->execute();
				$listOfIncomes = $query->fetchAll();
				echo "List of incomes for month: $date <br>
					<table border = 1'>
					<tr><th>Date</th><th>Source</th><th>Amount</th></tr>";
				foreach ($listOfIncomes as $inc) {
					echo "<tr><td>$inc[0]</td> <td>$inc[1]</td><td>$inc[1]</td></tr>";
				}
				echo "</table>";
			}catch(PDOException $e){

			}
		}

		public function listDaysExpense($date){
			try{
					$sql = "SELECT " . COL_EXP_ESS . " , " . COL_EXP_ITEM . " , " . COL_EXP_AMOUNT . " FROM " . TBL_EXP ." WHERE " . COL_EXP_DATE . " like :date ;";
					$query = $this->conn->prepare($sql);
					$query->bindValue(":date", "$date",PDO::PARAM_STR);
					$query->execute();
					$listOfExpenses = $query->fetchAll();
					echo "List of expenses for day: $date <br>
						<table border = 1'>
						<tr><th>Essential</th><th>Item</th><th>Amount</th></tr>";
					foreach ($listOfExpenses as $exp) {
						echo "<tr><td>$exp[0]</td> <td>$exp[1]</td> <td>$exp[2]</td></tr>";
					}
					echo "</table>";
			}catch(PDOException $e){

			}
		}

		public function listMonthsExpense($date){
			try{
				$sql = "SELECT " . COL_EXP_DATE . " , " . COL_EXP_ESS . " , " . COL_EXP_ITEM . " , " . COL_EXP_AMOUNT . " FROM " . TBL_EXP ." WHERE " . COL_EXP_DATE . " like :date ;";
				$query = $this->conn->prepare($sql);
				$query->bindValue(":date", "%-{$date}-%",PDO::PARAM_STR);
				$query->execute();
				$listOfExpenses = $query->fetchAll();
				echo "List of expenses for month: $date <br>
					<table border = 1'>
					<tr><th>Date</th><th>Essential</th><th>Item</th><th>Amount</th></tr>";
				foreach ($listOfExpenses as $exp) {
					echo "<tr><td>$exp[0]</td> <td>$exp[1]</td> <td>$exp[2]</td> <td>$exp[3]</td></tr>";
				}
				echo "</table>";
			}catch(PDOException $e){

			}
		}
		#---------------------------------------------------------------------------------------------------------------------------------


		
		#Izlistavanje troskova 
		public function listAllExpenses() {
			$sql = "SELECT * FROM " . TBL_EXP ." ORDER BY " . COL_EXP_DATE ." DESC;";
			$query = $this->conn->prepare($sql);
			$query->execute(); 
			$expenses = $query->fetchAll();

			$print = "<table style='border: 1px solid red;'>";
			$print .= "<tr><th>ID</th><th>Date</th><th>Essential</th><th>Item</th><th>Amount</th></tr>";
			foreach ($expenses as $exp) { #po jedan red iz baze
				$print .= "<tr>";
				$print .= "<td>{$exp['idExpense']}</td>";
				$print .= "<td>{$exp['dateOfExpense']}</td>";
				if ($exp['essential']) {
					$print .= "<td>yes</td>";
				} else {
					$print .= "<td>no</td>";
				}
				$print .= "<td>{$exp['item']}</td>";
				$print .= "<td>{$exp['amount']}</td>"; 
				$print .= "</tr>";
			}
			$print .= "</table>";
			echo $print;
		}

		public function listAllIncomes() {
			$sql = "SELECT * FROM " . TBL_INCOME ." ORDER BY " . COL_INCOME_DATE ." DESC;";
			$query = $this->conn->prepare($sql);
			$query->execute();#ispisuje ti 1 jer vraca true ili false
			$incomesQuery = $query->fetchAll();

			$print = "<table style='border: 1px solid black;'>";
			$print .= "<tr><th>ID</th><th>Date</th><th>Source</th><th>Amount</th></tr>";
			foreach ($incomesQuery as $inc) { #po jedan red iz baze
				$print .= "<tr>";
				$print .= "<td>{$inc['idIncome']}</td>";
				$print .= "<td>{$inc['dateOfIncome']}</td>";
				$print .= "<td>{$inc['source']}</td>";
				$print .= "<td>{$inc['amount']}</td>"; 
				//$print .= "<td><a href=''>more</a></td>"; 
				$print .= "</tr>";
			}
			$print .= "</table>";
			echo $print;
		}



		#------------------------- Deo sa healthom -----------------------#

		#Dodavanje novih kardio vezbi

		public function addCardio($name, $date, $time) {

			$sql = "INSERT INTO ".TBL_HEALTHCARDIO." (".COL_HEALTHCARDIO_NAME.", ".COL_HEALTHCARDIO_DATE.", ".COL_HEALTHCARDIO_TIME.") VALUES (:nam, :dat, :tim);";
			$temp = $this->conn;

			try {

				$query = $temp->prepare($sql);
				$query->bindValue(":nam", $name, PDO::PARAM_STR);
				$query->bindValue(":dat", date("Y-m-d", strtotime($date)), PDO::PARAM_STR);
				$query->bindValue(":tim", (int)$time, PDO::PARAM_INT);
				return $query->execute();

			} catch (Exception $e) {

			}
			return false;
			
		}


		public function addStrength($name, $date, $time) {

			$sql = "INSERT INTO ".TBL_HEALTHCARDIO." (".COL_HEALTHCARDIO_NAME.", ".COL_HEALTHCARDIO_DATE.", ".COL_HEALTHCARDIO_TIME.") VALUES (:nam, :dat, :tim);";
			$temp = $this->conn;

			try {

				$query = $temp->prepare($sql);
				$query->bindValue(":nam", $name, PDO::PARAM_STR);
				$query->bindValue(":dat", date("Y-m-d", strtotime($date)), PDO::PARAM_STR);
				$query->bindValue(":tim", (int)$time, PDO::PARAM_INT);
				return $query->execute();

			} catch (Exception $e) {

			}
			return false;
			
		}

		public function addOther($name, $date, $time) {

			$sql = "INSERT INTO ".TBL_HEALTHOTHER." (".COL_HEALTHOTHER_NAME.", ".COL_HEALTHOTHER_DATE.", ".COL_HEALTHOTHER_TIME.") VALUES (:nam, :dat, :tim);";
			$temp = $this->conn;

			try {

				$query = $temp->prepare($sql);
				$query->bindValue(":nam", $name, PDO::PARAM_STR);
				$query->bindValue(":dat", date("Y-m-d", strtotime($date)), PDO::PARAM_STR);
				$query->bindValue(":tim", (int)$time, PDO::PARAM_INT);
				return $query->execute();

			} catch (Exception $e) {
				
			}
			return false;
			
		}

	}
?>

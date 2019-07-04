<?php
	class Day{

		private static $dayName = array("Monday","Tuesday","Wendnesday","Thursday","Friday","Saturday","Sunday");

		private $weekDay;
		private $tasks;
		private $date;

		// Function returns number 1-7 which represent day of the week
		public function getWeekDayNumber($date){
			return date('N',strtotime($date))-1;
		}

		// Returns day name
		public function getDayName($num){
			return Day::$dayName[$num];
		}


		public function __construct($date){
			$dateNum = $this->getWeekDayNumber($date);
			$this->weekDay = array("name"=>$this->getDayName($dateNum),
								   "number"=>$dateNum);
			$this->tasks = array();
			$this->date = $date;
		}

		// Adds task
		public function addTask($task){
			if(is_a($task, "Task")){
				$this->tasks[] = $task;
			}
		}


		public function getWeekDay(){
			return $this->weekDay;
		}

		public function getName(){
			return $this->weekDay["name"];
		}

		public function getTasks(){
			return $this->tasks;
		}

		public function getDate(){
			return $this->date;
		}
	}
?>
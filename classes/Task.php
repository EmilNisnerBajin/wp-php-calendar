<?php

	require_once("constants.php");

	class Task implements JsonSerializable{
		private $name;
		private $startDate;
		private $endDate;
		private $importance;
		private $repeat;
		private $time;
		private $id;

		public function __construct($array){
			$this->name 		= $array[COL_TASK_NAME];
			$this->startDate 	= $array[COL_TASK_STARTDATE];
			$this->endDate 		= $array[COL_TASK_ENDDATE];
			$this->importance 	= $array[COL_TASK_IMPORTANCE];
			$this->repeat 		= $array[COL_TASK_REPEAT];
			$this->time 		= $array[COL_TASK_TIME];
			$this->id 			= isset($array[COL_TASK_ID]) ? $array[COL_TASK_ID] : "" ;
		}

		public function jsonSerialize(){
			return array(
							COL_TASK_ID 		=> $this->id,
							COL_TASK_NAME 		=> $this->name,
							COL_TASK_STARTDATE	=> $this->startDate,
							COL_TASK_ENDDATE 	=>	$this->endDate,
							COL_TASK_IMPORTANCE => $this->importance,
							COL_TASK_REPEAT 	=> $this->repeat,
							COL_TASK_TIME 		=> $this->time
						);

		}

		public function multySet($arr){
			$this->name 		= isset($arr[COL_TASK_NAME]) ? $arr[COL_TASK_NAME] : "";
			$this->startDate 	= isset($arr[COL_TASK_STARTDATE]) ? $arr[COL_TASK_STARTDATE] : date("Y-m-d");
			$this->endDate 		= isset($arr[COL_TASK_ENDDATE]) ? $arr[COL_TASK_ENDDATE] : date("Y-m-d");
			$this->importance 	= isset($arr[COL_TASK_IMPORTANCE]) ? $arr[COL_TASK_IMPORTANCE] : "1";
			$this->repeat 		= isset($arr[COL_TASK_REPEAT]) ? $arr[COL_TASK_REPEAT] : "0";
			$this->time 		= isset($arr[COL_TASK_TIME]) ? $arr[COL_TASK_TIME] : date("a",time());
		}

		public function getName(){
			return $this->name;
		}

		public function getId(){
			return $this->id;
		}

		public function getStartDate(){
			return $this->startDate;
		}

		public function getEndDate(){
			return $this->endDate;
		}

		public function getImportance(){
			return $this->importance;
		}

		public function getTime(){
			return $this->time;
		}

		public function getRepeat(){
			return $this->repeat;
		}
	}
?>
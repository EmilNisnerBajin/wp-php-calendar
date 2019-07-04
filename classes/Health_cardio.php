<?php
	class Health_cardio {

		private $idExcerciseC;
		private $name;
		private $date;
		private $time;

		function __construct($idExcersiseC, $name, $date,  $time) {
				$this->idExcerciseC = $idExcerciseC;
				$this->name = $name;
				$this->date = $date;
				$this->time = $time;
				
		}

		function getIdExcerciseC() {
			return $this->idExcerciseC;
		}

		function getName() {
			return $this->name;
		}

		function getDate() {
			return $this->date;
		}

		function getTime() {
			return $this->time;
		}

	}


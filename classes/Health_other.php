<?php
	class Health_other {

		private $idExcerciseO;
		private $name;
		private $date;
		private $time;

		function __construct($idExcersiseO, $name, $date, $time) {
				$this->idExcerciseO = $idExcerciseO;
				$this->name = $name;
				$this->date = $date;
				$this->time = $time;
			
		}

		function getIdExcerciseO() {
			return $this->idExcerciseO;
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
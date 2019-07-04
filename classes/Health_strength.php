<?php
	class Health_strength {

		private $idExcerciseS;
		private $name;
		private $date;
		private $reps; 

		function __construct($idExcersiseS, $name, $date, $reps) {
				$this->idExcerciseS = $idExcerciseS;
				$this->name = $name;
				$this->date = $date;
				$this->reps = $reps;

		}

		function getIdExcerciseS() {
			return $this->idExcerciseC;
		}

		function getName() {
			return $this->name;
		}

		function getDate() {
			return $this->date;
		}

		function getReps() {
			return $this->reps;
		}
	}
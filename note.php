<?php
#beleska je jedna sacuvana forma
	class Note 
	{
		
		private $idNote; #necu ubaciti u konstruktor jos da nam ne narusi dosadasnji kod
		private $title;
		private $text;
		private $date; #korisnik samo unosi datum na koji se beleska odnosi, regeksima mozemo lako dobiti d/m/y
		private $locked; #da li zakljucavamo belesku ili ne
			
		function __construct($title, $text, $date, $locked)
		{ #moramo profiltrirati karaktere, zato ne koristimo POST
				$this->title = $title;
				$this->text = $text;
				$this->date = $date;
				$this->locked = $locked;
			
		}

		function getIdNote() {
			return $this->title;
		}

		function getTitle() {
			return $this->title;
		}

		function getText() {
			return $this->text;
		}

		function getDate() {
			return $this->date;
		}

		function isLocked() {
			return $this->locked;
		}

		public function toArray()
		{
			return [
				'date' => $this->getDate(),
				'title' => $this->getTitle(),
				'text' => $this->getText(),
				'locked' => $this->isLocked() 
			];
		}


	}




?>
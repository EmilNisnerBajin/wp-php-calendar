<?php
	class Income{
		private $idIncome; #trebace zbog baze
		private $date;
		private $source; # paycheck, gift, lottery :D ..
		private $amount;

		function __construct($idIncome, $date, $source, $amount){
				$this->idIncome = $idIncome;
				$this->date = $date;
				$this->source = $source;
				$this->amount = $amount;
		}

		#da namestimo da svaki mesec dobija platu, da ne mora da unosi svaki put
		function monthlyPaycheck() {
			if (!strcmp($this->source, 'paycheck') || !strcmp($this->source, 'plata')) { #vraca 0 ako su =
				return 1;
			} else {
				return 0;
			}
		}

		function getIdIncome(){
			return $this->idIncome;
		}

		function getDate(){
			return $this->date;
		}

		function getSource(){
			return $this->source;
		}

		function getAmount(){
			return $this->amount;
		}

	}
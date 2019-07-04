<?php
	class Expense{

		private $idExpense;
		private $date;
		private $essential; #true for food, water, soap.. false for fancy dinners, trips, clothes
		private $item; #e.g. movies or food for today, or night out
		private $amount;

		function __construct($idExpense, $date, $essential, $item, $amount){
				$this->idExpense = $idExpense;
				$this->date = $date;
				$this->essential = $essential;
				$this->item = $item;
				$this->amount = $amount;
		}

		function getIdExpense(){
			return $this->idExpense;
		}

		function getDate(){
			return $this->date;
		}

		function isEssential(){
			return $this->essential;
		}

		function getItem(){
			return $this->item;
		}

		function getAmount(){
			return $this->amount;
		}

	}
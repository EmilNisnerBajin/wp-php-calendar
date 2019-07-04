<?php

require_once("TimeUtils.php");
require_once("Day.php");

class Week{
	private $days; // Associative array where index represents a date of a day
	private $month;
	private $year;
	private $startDate;
	private $endDate;

	// Creating a week based on a given date
	public function __construct($date){
		$weekRange = TimeUtils::getWeekBeginingAndEnd($date);
		$this->startDate = $weekRange["begin"];
		$this->endDate = $weekRange["end"];


		// Month calculation
		$sameMonth;
		$firstDateMonth=date("F",strtotime($this->startDate));
		$secondDateMonth=date("F",strtotime($this->endDate));

		strcmp($firstDateMonth, $secondDateMonth) ? $sameMonth = true : $sameMonth = false;

		$m = array();

		if(!$sameMonth){
			$m["first"] = $firstDateMonth;
		}else{
			$m["first"] = $firstDateMonth;
			$m["second"] = $secondDateMonth;
		}

		$this->month = $m;

		// Year calculation
		$sameYear;
		$firstDateYear=date("Y",strtotime($this->startDate));
		$secondDateYear=date("Y",strtotime($this->endDate));

		strcmp($firstDateYear,$secondDateYear) ? $sameYear = true : $sameYear = false;

		$y = array();

		if(!$sameYear){
			$y["first"] = $firstDateYear;
		} else {
			$y["first"] = $firstDateYear;
			$y["second"] = $secondDateYear;
		}

		$this->year = $y;
		$this->days = array();
	}

	public function addDay($day){
		$date = $day->getDate();
		$this->days[$date] = $day;
	}

	public function generateDays(){
		$startDate = $this->getStartDate();
		$endDate = $this->getEndDate();
		$current = $startDate;
		//echo "Adding days";
		while($current <= $endDate){
			$day = new Day($current);
			$this->addDay($day);
			$current = strtotime("+1 day",strtotime($current));	//increase day by one
			$current = date("Y-m-d", $current);
		}
	}

	public function getYear(){
		$num = count($this->year);
		if($num==2){
			return $this->year["first"]."-".$this->year["second"];
		}else{
			return $this->year["first"];
		}
	}

	public function getMonth(){
		$num = count($this->month);
		if($num==2){
			return $this->month["first"]."-".$this->month["second"];
		}else{
			return $this->month["first"];
		}
	}

	public function getDays(){
		return $this->days;
	}

	public function addTasks($tasks){
		foreach ($tasks as $task) {
			$this->addTask($task);
		}
	}

	public function addTask($task){
			$dateStart = $task->getStartDate();
			$dateEnd = $task->getEndDate();

			if($dateEnd == null){ // repeat dogadjaj iz ove nedelje
				$dateEnd = $dateStart;

				if($dateStart < $this->startDate){ // Dogadjaj iz prethodnih nedelja al se ponavlja jer endDate = null;
					$tmpDay = new Day($dateStart);
					$n = $tmpDay->getWeekDay()["number"];
					$dani = array_keys($this->days);
					$trazeniDatum = $dani[$n];
					//echo "Dogadjaj za datum:$dateStart ali posto se ponavlja onda za datum u ovoj nedelji:$trazeniDatum";
					$dateStart = $trazeniDatum;
					$dateEnd = $dateStart;
				}
			}


			
			if($dateEnd > $this->endDate){
				$dateEnd = $this->endDate;
			}

			if($dateStart < $this->startDate){
				$dateStart = $this->startDate;
			}

			while($dateStart <= $dateEnd){
				$day = $this->days[$dateStart];
				$dateStart = strtotime("+1 day",strtotime($dateStart));	//increase day by one
				$dateStart = date("Y-m-d", $dateStart);
				$day->addTask($task);
			}
	}

	public function getTask($taskId){
		foreach ($this->days as $day) {
			foreach($day->getTasks() as $task){
				if(strcmp($taskId,$task->getId()==0)){
					return $task;
				}
			}
		}
	}

	public function removeTask($taskId){
		foreach ($this->days as $day) {
			foreach ($day->getTasks() as $task) {
				if($task->getId() == $taskId){
					$task = null;
				}
			}
		}
	}

	public function getStartDate(){
		return $this->startDate;
	}

	public function getEndDate(){
		return $this->endDate;
	}
}
?>
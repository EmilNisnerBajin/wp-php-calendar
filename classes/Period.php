<?php 
require_once("classes/DBUtils.php");

$database = new DBUtils(DATABASE_INI_FILE);

/**
 * 
 */
class Period
{
	private $menstruationLength;
	private $period;
	private $ovulation;

	private $menstruationStart;
	private $menstruationEnd;
	
	function __construct($start, $end, $user)
	{
		$this->menstruationStart 	= htmlspecialchars($start);
		$this->menstruationEnd 		= htmlspecialchars($end);

		$this->menstruationLength 	= htmlspecialchars($this->calculateLength($user));
		$this->period 				= htmlspecialchars($this->calculatePeriod($user));
		$this->ovulation 			= htmlspecialchars($this->calculateOvulation());
	}

	public function getStart() {
		return $this->menstruationStart;

	}

	public function getEnd() {
		return $this->menstruationEnd;
	}

	public function getLength() {
		return $this->menstruationLength;
	}

	public function getPeriod() {
		return $this->period;
	}

	public function getOvulation() {
		return $this->ovulation;
	}


	public function calculateLength($user) {
		$periods = $database->getAllPeriods($user);
		$sum = 0;
		$avg = 0;
		foreach ($periods as $p) {
			$sum += $p->getEnd() - $p->getStart();
		}

		$avg = $sum/count($periods);
		return $avg;


	}

	public function calculatePeriod($user) {
		$periods = $database->getAllPeriods();
		$sum = 0;
		$avg = 0;
		for($i=count($periods)-1; $i>0; $i++) {
			$sum += $periods[$i]->getStart() - $periods[$i-1]->getStart();

		}
		$avg = $sum/count($periods);
		return $avg;

	}

	public function calculateOvulation() {
		return $this->getStart() + $this->period/2;
	}
}


 ?>
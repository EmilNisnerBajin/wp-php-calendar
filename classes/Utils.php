<?php
	require_once("Task.php");
	require_once("Week.php");
	require_once("DBUtils.php");
	require_once("constants.php");

class Utils{
	

	public static function createDayHTML($day){
		$html = "";
		if(is_a($day, "Day")){
			$html.= "<div class=\"Day\">";
			$html.= "<center><h3>".$day->getName()."</h3></center>";
			$html.= "<center><h4>".date("d",strtotime($day->getDate()))."</h4></center>";
			$html.= "</div>";
		}
		return $html;
	}

	public static function createTaskHTML($task){
		$html = "";
		if(is_a($task, "Task")){
			$id = $task->getId();
			$importance = $task->getImportance();
			$html.="<div class=\"Task imp$importance\">";
			$html.="<div class=\"Info\">";
				$html.= "<center><h5>".$task->getName()."</h5></center>";
				$html.= "<center><h5>".$task->getTime()."</h5></center>";
			$html.="</div>";
			$html.="<center>";
				$html.="<div class=\"TaskOptions\">";
					   $html.="<form action=\"calendar.php\" method=\"post\">";
					   $html.="<input type=\"submit\" name=\"option\" value=\"Edit\">";
					   $html.="<input type=\"submit\" name=\"option\" value=\"Remove\">";
					   $html.="<input type=\"hidden\" name=\"taskId\" value=\"$id\">";
					   $html.="</form>";
				$html.="</div>";
			$html.="</center>";
			$html.= "</div>";
		}
		return $html;
	}

	public static function createEditTaskForm($task){
		$id = $task->getId();
		$importance = $task->getImportance();
		$name = $task->getName();
		$startDate = $task->getStartDate();
		$endDate = $task->getEndDate();
		$repeat = $task->getRepeat();
		$time = $task->getTime();
		$html="<div class=\"EditOptions\">";
		$html.="<form action=\"calendar.php\" method=\"post\">";
		$html.="<table>";
		$html.="<tr><th><center>Edit Task</center></th></tr>";
		$html.="<tr><td>Name:</td><td><input type=\"text\" name=\"".COL_TASK_NAME."\" value=\"$name\"</td></tr>";
		$html.="<tr><td>Start Date:</td><td><input type=\"date\" name=\"".COL_TASK_STARTDATE."\" value=\"$startDate\"</td></tr>";
		$html.="<tr><td>End Date:</td><td><input type=\"date\" name=\"".COL_TASK_ENDDATE."\" value=\"$endDate\"</td></tr>";
		$html.="<tr><td>Time:</td><td><input type=\"time\" name=\"".COL_TASK_TIME."\" value=\"$time\"</td></tr>";
		$html.="<tr><td>Importance:</td><td>".Utils::createImportanceEditHtml($importance)."</td></tr>";
		$html.="<tr><td>Repeat:</td><td>".Utils::createRepeatEditHtml($repeat)."</td></tr>";
		$html.="<tr><td><input type=\"submit\" name=\"edit\" value=\"Save\"></td><td><input type=\"submit\" name=\"option\" value=\"Cancel\"></td></tr>";
		$html.="</table>";
		$html.="<input type=\"hidden\" name=\"taskId\" value=\"$id\">";
		$html.="</form>";
		$html.="</div>";
		return $html;
	}


	public static function createImportanceEditHtml($imp){
		$map = array("1"=>"Low", "2"=>"Medium", "3"=>"High");
		$html = "<select name=".COL_TASK_IMPORTANCE.">";
		foreach ($map as $key => $val) {
			$html.="<option value=\"$key\"";
			if($imp == $key){
				$html.="selected";
			}
			$html.=">".$val."</option>";
		}
		return $html;
	}

	public static function createRepeatEditHtml($rep){
		$html = "<select name=".COL_TASK_REPEAT.">";
		if($rep == 0){
			$html.="<option value=\"0\" selected>No</option>";
			$html.="<option value=\"1\" >Yes</option>";
		} else {
			$html.="<option value=\"0\">No</option>";
			$html.="<option value=\"1\" selected >Yes</option>";
		}
		$html.= "</select>";
		return $html;
	}

	public static function createWeekHTML($week,$userId){
		$database = new DBUtils(DATABASE_INI_FILE);
		$html = "";
		if(is_a($week, "Week")){
			$week->generateDays();
			$startDate = $week->getStartDate();
			$endDate = $week->getEndDate();
			$tasks = $database->getTasksInRange($startDate,$endDate,$userId);
			$week->addTasks($tasks);
			$html .= "<table>";
			foreach ($week->getDays() as $day) {
				$html .= "<tr>";
				$html .= "<td>";
				$html .= Utils::createDayHTML($day);
				$html .= "</td>";
				foreach ($day->getTasks() as $task) {
					$html .= "<td>";
					$html .= Utils::createTaskHTML($task);
					$html .="</td>";
				}
				$html .= "</tr>";
			}
			$html .= "</table>";
		}
		return $html;
	}

	public static function createCalendarHeading($week){
		$html = "";
		if(is_a($week, "Week")){
			$year = $week->getYear();
			$month = $week->getMonth();
			$html .= "<center><h1>$month Of $year</h1></center><br>";
		}
		return $html;
	}

	// Deprecated
	public static function determineImportanceColor($task){
		$color = "white";
		if(is_a($task, "Task")){
			$importance = $task->getImportance();
			switch($importance){
				case 1:
					$color = "green";
					break;
				case 2:
					$color = "yellow";
					break;
				case 3:
					$color = "red";
					break;
			}
		}
		return $color;
	}

	public static function readTasks($filename){
		$json = file_get_contents($filename);
		$data = json_decode($json,true);
		$days = array();
		$taskArr = array();
		$week;
		foreach ($data["Dani"] as $day) {
			$d = new Day($day["Dan"]);
			foreach($day["Tasks"] as $t){
				$taskArr["name"] = $t["name"];
				$taskArr["startDate"] = $t["startDate"];
				$taskArr["endDate"] = $t["endDate"];
				$taskArr["importance"] = $t["importance"];
				$taskArr["time"] = $t["time"];
				$taskArr["repeat"] = $t["repeat"];
				$newTask = new Task($taskArr);
				$d->addTask($newTask);
			}
			$days[] = $d;
		}
		$weekMonth = "May";
		$weekYear = "2019";
		$weekStartDate = "12.05.2019";
		$weekEndDate = "19.05.2019";
		$week = new Week($weekYear,$weekMonth,$days,$weekStartDate,$weekEndDate);
		return $week;
	}
}
?>
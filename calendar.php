<?php
	require_once("Functions.php");
	require_once("classes/Task.php");
	require_once("classes/Day.php");
	require_once("classes/DBUtils.php");
	require_once("classes/Utils.php");
	require_once("classes/Week.php");
	require_once("classes/DownloadTask.php");
	require_once("classes/SessionChecker.php");
	
	$database = new DBUtils(DATABASE_INI_FILE);

	$isEditTaskSet = false;
	$user = $_SESSION["user"];
	$userId = $database->getUserId($user);
	$date; 
	

	$messages = array();


	// Task Edit
	if(isset($_POST["edit"])){
		$option = htmlspecialchars($_POST["edit"]);
		if(strcmp($option, "Save")==0){
			$taskToEdit = $_SESSION["taskToEdit"];
			
			$newInfo = array();
			$newInfo[COL_TASK_NAME] 		= 	htmlspecialchars($_POST[COL_TASK_NAME]);
			$newInfo[COL_TASK_REPEAT] 		= 	htmlspecialchars($_POST[COL_TASK_REPEAT]);
			$newInfo[COL_TASK_STARTDATE]	=	htmlspecialchars($_POST[COL_TASK_STARTDATE]);
			$newInfo[COL_TASK_ENDDATE]		=	htmlspecialchars($_POST[COL_TASK_ENDDATE]);
			$newInfo[COL_TASK_IMPORTANCE]	=	htmlspecialchars($_POST[COL_TASK_IMPORTANCE]);
			$newInfo[COL_TASK_TIME]			=	htmlspecialchars($_POST[COL_TASK_TIME]);
			$taskToEdit->multySet($newInfo);
			
			if($database->updateTask($taskToEdit)){
				$messages[] = "Task successfully edited";
			}else{
				$messages[] = "Edit task failed";
			}
		}else if(strcmp($option, "Cancel")==0){
			$isEditTaskSet = false;
		}else{
			$messages[] = "Invalid option selected";
		}
		unset($_SESSION["taskToEdit"]); // Kada se task sacuva ili editovanje otkaze izbacujem ga iz sesije
	}

	// Pomocni fajl na kojem je kod za dodavanje taska
	require_once("classes/addTask.php");

	// Week Choser
	if(isset($_POST["prevWeek"])){
		$date = htmlspecialchars($_GET["date"]);
		$date = strtotime("-1 weeks",strtotime($date));	//decrease week by one
		$date = date("Y-m-d", $date);
	}else if(isset($_POST["nextWeek"])){
		$date = htmlspecialchars($_GET["date"]);
		$date = strtotime("+1 weeks",strtotime($date));	//increase week by one
		$date = date("Y-m-d", $date);
	}else if(isset($_COOKIE["date"])){
		$date = htmlspecialchars($_COOKIE["date"]);
	}else{
		$date = date("Y-m-d");
	}
	setcookie("date",$date,time()+60*60);

	$week = new Week($date);
	$weekHTML = Utils::createWeekHTML($week,$userId);

	// Task download
	if(isset($_GET["downloadOption"])){
		$opt = htmlspecialchars($_GET["downloadOption"]);
		if(strcmp($opt, "Download This Week Tasks")==0){
			//echo "download this week tasks";
			$startDate = $week->getStartDate();
			$endDate = $week->getEndDate();
			$tasks = $database->getTasksInRange($startDate,$endDate,$userId);
		} else{
			//echo "download all tasks";
			$tasks = $database->getAllTasks($user);
		}
		$downloadTask = new DownloadTask();
		$downloadTask->downloadJson($tasks);
	}


	// Task Edit / Removal
	if(isset($_POST["option"])){
		$option = htmlspecialchars($_POST["option"]);
		$id = htmlspecialchars($_POST["taskId"]);
		if(strcmp($option, "Remove")==0){
			if($database->removeTask($id)){
				$week->removeTask($id);
				$messages[] = "Task deleted";
			}else{
				$messages[] = "Error deleting task";
			}
			
		}else if(strcmp($option, "Edit")==0){
			$isEditTaskSet = true;
			$taskToEdit = $week->getTask($id);
			$_SESSION["taskToEdit"] = $taskToEdit;
		}
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Calendar</title>
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="cssFiles/calendar.css">
</head>
<body>
	
		<?php
		Functions::top_menu("Calendar");
		echo Utils::createCalendarHeading($week);
		?>


		<div class="Messages">
			<?php
				foreach ($messages as $msg) {
					echo "<div class=\"message\">$msg</div>";
				}
			?>
		</div>
		<div class="AddTask">
			<form action="calendar.php" method="post">
				Name: <input type="text" name="<?php echo COL_TASK_NAME;?>">
				Repeat Weekly:
				<select name="<?php echo COL_TASK_REPEAT;?>">
					<option value="Yes">Yes</option>
					<option value="No">No</option>
				</select>
				Start Date: <input type="date" name="<?php echo COL_TASK_STARTDATE;?>">
				End Date: <input type="date" name="<?php echo COL_TASK_ENDDATE;?>">
				Time: <input type="time" name="<?php echo COL_TASK_TIME;?>">
				Priority: 
				<select name="<?php echo COL_TASK_IMPORTANCE;?>">
					<option value="1">Low</option>
					<option value="2">Medium</option>
					<option value="3">High</option>
				</select>
				<input type="submit" value="Add Task" name="addTask">
			</form>
		</div>

		<center>
			<div class="WeekChoser">
			<form action="calendar.php?date=<?php echo $date?>" method="post">
				<input type="submit" name="prevWeek" value="<">
				Switch week
				<input type="submit" name="nextWeek" value=">">
			</form>
		</div>
		</center>

		<?php
		if($isEditTaskSet){
			echo Utils::createEditTaskForm($taskToEdit);
		}
		echo $weekHTML;
		?>

		<div class="DownloadOptions">
			<form action="calendar.php" method="get">
				<input type="submit" name="downloadOption" value="Download This Week Tasks">
				<input type="submit" name="downloadOption" value="Download all tasks">
			</form>
		</div>	
</body>
</html>



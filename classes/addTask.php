<?php
	if(isset($_POST["addTask"])){
		$repeat = htmlspecialchars($_POST[COL_TASK_REPEAT]);
		$isRepeating;
		if(strcmp($repeat,"Yes")==0){
			$_POST[COL_TASK_ENDDATE] = null; // Dogadjaj se ponavlja pa je end date null
			$_POST[COL_TASK_REPEAT] = 1;
			$isRepeating = true;
		}
		else{
			$_POST[COL_TASK_REPEAT] = 0;
			$isRepeating = false;
		}

		$newTaskArr = array();
		$newTaskArr[COL_TASK_NAME] 			= htmlspecialchars($_POST[COL_TASK_NAME]);
		$newTaskArr[COL_TASK_REPEAT]		= htmlspecialchars($_POST[COL_TASK_REPEAT]);
		$newTaskArr[COL_TASK_ENDDATE] 		= htmlspecialchars($_POST[COL_TASK_ENDDATE]);
		$newTaskArr[COL_TASK_STARTDATE] 	= htmlspecialchars($_POST[COL_TASK_STARTDATE]);
		$newTaskArr[COL_TASK_IMPORTANCE] 	= htmlspecialchars($_POST[COL_TASK_IMPORTANCE]);
		$newTaskArr[COL_TASK_TIME] 			= htmlspecialchars($_POST[COL_TASK_TIME]);

		// Prover validnosti podataka
		$startDateGiven;
		$endDateGiven;
		$startAndEndDateOk;
		$proceed;
		//Ponavljanje
		if($isRepeating){
			$startDateGiven = $newTaskArr[COL_TASK_STARTDATE] == null;
			if($startDateGiven){
				$msg = "Start date must be set!";
				$messages[] = $msg;
				$proceed = false;
			}else{
				$proceed = true;
			}
		// Dogadjaj se ne ponavlja
		}else{
			$startDateGiven = $newTaskArr[COL_TASK_STARTDATE] == null;
			$endDateGiven = $newTaskArr[COL_TASK_ENDDATE] == null;
			$startAndEndDateOk = $newTaskArr[COL_TASK_ENDDATE] >= $newTaskArr[COL_TASK_STARTDATE];
			if($startDateGiven){
				$msg = "Start date must be set!";
				$messages[] = $msg;
				$proceed = false;
			}
			if($endDateGiven){
				$msg = "End date must be set!";
				$messages[] = $msg;
				$proceed = false;
			}
			if(!$startAndEndDateOk){
				$msg = "Wrong order of end and start date!";
				$messages[] = $msg;
				$proceed = false;
			}
			if(!$startDateGiven && !$endDateGiven && $startAndEndDateOk){
				$proceed = true;
			}
		}
		if($proceed){
			$newTask = new Task($newTaskArr);
			if($database->addTask($newTask,$user)){
				$msg = "Task added successfully";
			}else{
				$msg = "Error adding task";
			}
			$messages[] = $msg;
		}else{
			$msg = "Task not created.";
			$messages[] = $msg;
		}
	}
?>
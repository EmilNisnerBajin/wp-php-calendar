<?php 
require_once("classes/DBUtils.php");
require_once("Functions.php");
require_once("classes/Period.php");

$database = new DBUtils(DATABASE_INI_FILE);
session_start();


if(isset($_SESSION["user"])) {
	$user = $_SESSION["user"];
} ?>


<!DOCTYPE html>
<html>
<head>
	<title>Period Calendar</title>
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>
	<?php if(!is_null($user)) { ?>
 	
 		<header><h1>Period Calendar</h1>
 		
	 		<?php  
			Functions::top_menu("Period Calendar");

			?>
		</header>

	<?php
	} else {
		echo "nema user-a";
	} ?>


</body>
</html>

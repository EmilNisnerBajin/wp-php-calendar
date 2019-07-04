<?php
	session_start(); // Starts a session
	if(!isset($_SESSION["user"])){ //If there is no user in session redirect it

		$script = "<script>window.location.replace(\"./index.php\");</script>";
		#$script = "<script>window.location.replace(\"./index.php\");</script>";
		echo $script;

		// Ovaj red izbacije 500 internal server error, nisam uspeo pronaci zasto
		// pa sam iskoristio JS 
		// header("Location: index.php");

		exit();
	}
?>
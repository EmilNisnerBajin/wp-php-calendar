<?php
	session_start();

	unset($_SESSION["user"]);
	if(isset($_COOKIE["date"])){
		setcookie("date","",time()-5);
	}
	
	$script = "<script>window.location.replace(\"./index.php\");</script>";
	echo $script;
?>
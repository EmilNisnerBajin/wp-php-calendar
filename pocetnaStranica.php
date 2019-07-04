<?php
	require_once("Functions.php");
	require_once("classes/DBUtils.php");
	require_once("classes/User.php");

	session_start();

	$database = new DBUtils(DATABASE_INI_FILE);
?>

<!DOCTYPE html>
<html>
<head>
	<title>Life organizer</title>
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>
	<?php
		    if (isset($_GET["user"])) {
		    	$username = htmlspecialchars($_GET["user"]);
		      	$user = $database->getUser($username);
		      	$_SESSION["user"] = $user;
		        if ($user) {
					echo $user->getFullName();
				} else {
					echo "Nepoznat korisnik";
				}
		    } else if(isset($_SESSION["user"])) {
		    	$user = $_SESSION["user"];
		    } else{
		    	echo "Nepoznat korisnik";
		    }
		?>
	<h1>Organize My Life</h1>
	 <?php  
		Functions::top_menu("Home");
	?>
</body>
</html>
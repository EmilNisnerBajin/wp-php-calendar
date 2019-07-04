<?php
	#neophodno zbog menija za navigaciju i vrv nesto u buducnosti
  	require_once("Functions.php");

	# Za sada ne radi nista od znacaja
  	if(isset($_POST["healthCalculation"])){
		$height = $_POST["height"];
		$weight = $_POST["weight"];
		$excercise = $_POST["excercise"];
		$eating = $_POST["eating"];
		$goal = $_POST["goal"];
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Calculate your health</title>
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body >
	<h1>Let's calculate your health in a few easy steps!</h1> 
	
	<?php
		#menu na vrhu stranice za navigaciju  
    	Functions::top_menu("Health");
  ?>

  	
  
</body>
</html>
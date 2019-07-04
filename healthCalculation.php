<?php
	
	if(isset($_POST['submit'])){
		session_start();
		$_SESSION['height'] = htmlentities($_POST['height']);
		$_SESSION['weight'] = htmlentities($_POST['weight']);
	}
	
	#neophodno zbog menija za navigaciju i vrv nesto u buducnosti
  	require_once("Functions.php");

	# Za sada ne radi nista od znacaja
  	if(isset($_POST["healthCalculation"])){
		$height 	= htmlspecialchars($_POST["height"]);
		$weight 	= htmlspecialchars($_POST["weight"]);
		$excercise 	= htmlspecialchars($_POST["excercise"]);
		$eating 	= htmlspecialchars($_POST["eating"]);
		$goal 		= htmlspecialchars($_POST["goal"]);
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

  <br>
  <!-- Forma za unos podataka -->
	<form action="health_main.php" method="POST" id ="health">
		<table>
			Personal information
			<tr>
				<td>Your height:</td>
				<td><input type="text" name="height"></td>
			</tr>
			<tr>
				<td>Your weight:</td>
				<td><input type="text" name="weight"></td>
			</tr>

		</table>

		<br><br>
		Some further information

		<table>	
			<tr>
				<td>How often do you work out ?</td>
				<td><select name="excercise">
				  <option value="excercise_0">	less then once a week	</option>
				  <option value="excercise_1">	1 - 2 times a week		</option>
				  <option value="excercise_2">	3 - 4 times a week		</option>
				  <option value="excercise_3">	6 - 7 times a week		</option>
				  <option value="excercise_4"> 	8 or more times a week	</option>
				</select></td>
			</tr>
			<tr>
				<td>How healthy do you eat ? </td>
				<td><select name="eating">
				  <option value="eat_0">Not very healthy, I like fries too much</option>
				  <option value="eat_1">I try to eat healthy a few times a week</option>
				  <option value="eat_2">I try to eat a healthy meal every day</option>
				  <option value="eat_3">I try to only eat healthy meals</option>
				</select></td>
			</tr>
			<tr>
				<td>What is your main fitness goal? </td>
				<td> <select name="goal">
				  <option value="goal_0">I dont have any, pelase send  help</option>
				  <option value="goal_1">Get more muscular</option>
				  <option value="goal_2">Improve health in general</option>
				  <option value="goal_3">Loose weight</option>
				</select></td>
			</tr>
		</table>
		<br><br>
		<table>
			<tr>
				<td><input type="submit" name="healthCalculation" value="Calculate my health!" class="w3-btn w3-block w3-blue w3-left-align"></td>
			</tr>
		</table>
	</form>
	
</body>
</html>
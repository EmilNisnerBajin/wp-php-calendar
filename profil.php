<?php 
	require_once ("classes/User.php");
	require_once ("Functions.php");
	require_once ("classes/DBUtils.php");
	require_once("pocetnaStranica.php");

	

	if(isset($_SESSION["user"])) {
		$user=$_SESSION["user"];
		
	} else {
		$user = NULL;
	}

 ?>


<!DOCTYPE html>
<html>

<head>
	<title>My Profile</title>
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>
 	<?php if(!is_null($user)) { ?>
 	
 		<header><h1>My Profile</h1>
 		
	 		<?php  
			Functions::top_menu("My Profile");

			?>
		</header>
			 <div style=" text-align: center">
		 		<p><a href="promenaSlike.php"><img src="<?php echo $user->getImage(); ?>" class="w3-circle" style="height: 100px; width: 100px; align-self: center;"></a></p>
		
			
			 
				  <p><h3><?php echo $user->getFullName(); ?></h3></p>
				  <p><?php echo "@".$user->getUsername(); ?></p>
				
				  <p>Address: <?php echo $user->getAddress().", ".$user->getCity().", ".$user->getCountry(); ?></p>
				  <p>Birthday: <?php echo $user->getBirthday(); ?></p>
				  <?php 
				  	if($user->getWeight() != "") {
				  		echo "<p>Weight: ".$user->getWeight()." kg</p>"; 


				  	}
				  	if($user->getHeight() !="") {
				  		echo "<p>Height: ".$user->getHeight()." cm</p>";

				  	}
				   ?>
				   <a href="promenaProfila.php"><button>Change your profile</button></a>
				 </div>
			

				  

	<?php } else {
		echo "Nema user-a";
	}?>


</body>
</html>

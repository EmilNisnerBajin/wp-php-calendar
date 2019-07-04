<?php 
	require_once ("classes/User.php");
	require_once ("Functions.php");
	require_once ("classes/DBUtils.php");

	$database = new DBUtils(DATABASE_INI_FILE);

	if(isset($_SESSION["user"])) {
		$user=$_SESSION["user"];
		
	}  else {
		$user=NULL;
	}
	$errors = array();
	$notifications = array();


	if(isset($_POST["change"])){
		$data = array();
		$data[COL_USER_USERNAME] 	= htmlspecialchars($_POST[COL_USER_USERNAME]);
		$data["oldPassword"]		= htmlspecialchars($_POST["oldPassword"]);
		$data[COL_USER_PASSWORD] 	= htmlspecialchars($_POST[COL_USER_PASSWORD]);
		$data[COL_USER_PASSWORD2]	= htmlspecialchars($_POST[COL_USER_PASSWORD2]);
		$data[COL_USER_BIRTHDAY]	= htmlspecialchars($_POST[COL_USER_BIRTHDAY]);
		$data[COL_USER_POSTCODE] 	= htmlspecialchars($_POST[COL_USER_POSTCODE]);
		$data[COL_USER_COUNTRY] 	= htmlspecialchars($_POST[COL_USER_COUNTRY]);
		$data[COL_USER_CITY] 		= htmlspecialchars($_POST[COL_USER_CITY]);
		$data[COL_USER_EMAIL]		= htmlspecialchars($_POST[COL_USER_EMAIL]);
		$data[COL_USER_FIRSTNAME] 	= htmlspecialchars($_POST[COL_USER_FIRSTNAME]);
		$data[COL_USER_LASTNAME]	= htmlspecialchars($_POST[COL_USER_LASTNAME]);
		$data[COL_USER_ADDRESS]		= htmlspecialchars($_POST[COL_USER_ADDRESS]);

		$tmp = $user;
		$user2 = $user->changeProfile($data, $errors);
		if($database->updateUser($tmp, $user2)) {
				$notifications[] = "Polja, koja su pravilno unesena, su promenjena. ";
				$_SESSION["user"] = $user2;
		}else{
			$errors[] = "Promena profila nije uspela";
			$_SESSION["user"] = $user;
		}
	}

	

 ?>
<html>
<head>
	<title>Change your profile</title>
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>
	<?php if(!is_null($user)) { ?>
 	
 		<header><h1>Change your profile</h1>
 		
	 		<?php  
			Functions::top_menu("My Profile");
		}
			?>
		</header>
		<div id="content">
			<div id="notification">
				<?php
					foreach($notifications as $not){
						echo "<div>$not</div>";
					}
				?>
			</div>
			<div id="errors">
				<?php
					foreach($errors as $err){
						echo "<div>$err</div>";
					}
				?>
			</div>
		</div>

	<form method="POST" action="profil.php">
	<table>
		
		<tr>
			<td>Username: </td>
			<td><input type="text" name="<?php echo COL_USER_USERNAME; ?>"></td>
		</tr>
		<tr>
			<td>Old password: </td>
			<td><input type="password" name="oldPassword"></td>
			
		</tr>
		<tr>
			<td>New Password: </td>
			<td><input type="password" name="<?php echo COL_USER_PASSWORD;?>"></td>
		</tr>
		<tr>
			<td>Repeat password: </td>
			<td><input type="password" name="<?php echo COL_USER_PASSWORD2;?>"></td>
		</tr>
		
		 <tr>
		 	<td>First Name: </td>
		 	<td><input type="text" name="<?php echo COL_USER_FIRSTNAME; ?>"></td>
		 </tr>
		 <tr>

		 	<td>Last Name: </td>
		 	<td><input type="text" name="<?php echo COL_USER_LASTNAME; ?>"></td>
		 </tr>
		 <tr>
		 	<td>Address: </td>
		 	<td><input type="text" name="<?php echo COL_USER_ADDRESS; ?>"></td>
		 </tr>
		 <tr>
		 	<td>City: </td>
		 	<td><input type="text" name="<?php echo COL_USER_CITY; ?>"></td>
		 </tr>
		 <tr>
		 	<td>Country: </td>
		 	<td><input type="text" name="<?php echo COL_USER_COUNTRY; ?>"></td>
		 </tr>
		 <tr>
		 	<td>Postcode: </td>
		 	<td><input type="text" name="<?php echo COL_USER_POSTCODE; ?>"></td>
		 </tr>
		 <tr>
		 	<td>Birthday: </td>
		 	<td><input type="date" name="<?php echo COL_USER_BIRTHDAY; ?>"></td>
		 </tr>
		 <tr>
		 	<td>e-mail: </td>
		 	<td><input type="text" name="<?php echo COL_USER_EMAIL; ?>"></td>
		 </tr>
		 <tr>
		 	<td><a href="./profil.php"><button>Cancel</button></a></td>
		 	<td><input type="submit" name="change" value="Change..."></td>
		 </tr>


	</table>
</form>
	

</body>
</html>

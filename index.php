<?php
require_once ("classes/DBUtils.php");
require_once ("classes/User.php");
require_once("classes/constants.php");


$database = new DBUtils(DATABASE_INI_FILE);


$errors = array();
$notifications = array();

if(isset($_POST["register"])){
	$regInfo = array();
	$regInfo[COL_USER_USERNAME] 	= htmlspecialchars($_POST[COL_USER_USERNAME]);
	$regInfo[COL_USER_PASSWORD] 	= htmlspecialchars($_POST[COL_USER_PASSWORD]);
	$regInfo[COL_USER_PASSWORD2]	= htmlspecialchars($_POST[COL_USER_PASSWORD2]);
	$regInfo[COL_USER_GENDER] 		= htmlspecialchars($_POST[COL_USER_GENDER]);
	$regInfo[COL_USER_BIRTHDAY]		= htmlspecialchars($_POST[COL_USER_BIRTHDAY]);
	$regInfo[COL_USER_POSTCODE] 	= htmlspecialchars($_POST[COL_USER_POSTCODE]);
	$regInfo[COL_USER_COUNTRY] 		= htmlspecialchars($_POST[COL_USER_COUNTRY]);
	$regInfo[COL_USER_CITY] 		= htmlspecialchars($_POST[COL_USER_CITY]);
	$regInfo[COL_USER_EMAIL]		= htmlspecialchars($_POST[COL_USER_EMAIL]);
	$regInfo[COL_USER_FIRSTNAME] 	= htmlspecialchars($_POST[COL_USER_FIRSTNAME]);
	$regInfo[COL_USER_LASTNAME]		= htmlspecialchars($_POST[COL_USER_LASTNAME]);

	$user = new User($regInfo);
	if($user->validate($errors)){
		$success = $database->registerUser($user);
		if($success){
			$notifications[] = "Registration succeed";
		}else{
			$errors[] = "Registration failed! Username already exists";
		}
	}
}

if(isset($_POST["login"])){
	$username = htmlspecialchars($_POST[COL_USER_USERNAME]);
	$password = htmlspecialchars($_POST[COL_USER_PASSWORD]);
	if($database->checkLogin($username, $password)){
		//Slanje username-a na pocetnu stranicu
		header("Location: pocetnaStranica.php?user=$username");
		$database = null;
	}else{
		$errors[] = "Wrong username-password combination";
	}
}

?>

<html>
	<head>
		<title>Home Page</title>
		<link rel="stylesheet" href="cssFiles/login.css">
	</head>
	<body>
		<h1><center>Login/Register</center></h1>
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
		<div class="loginKontejner">
			<h2>Login</h2>
			<form action="index.php" method="post">
				<table>
					<tr>
						<td>Username</td>
						<td><input type="text" name="<?php echo COL_USER_USERNAME ?>"></td>
					</tr>
					<tr>
						<td>Password</td>
						<td><input type="password" name="<?php echo COL_USER_PASSWORD ?>"></td>
					</tr>
					<tr>
						<td><input type="submit" value="login" name="login"></td>
					</tr>
				</table>
			</form>
		</div>
		<div class="registerKontejner">
			<h2>Register</h2>
			<form action="index.php" method="post">
				<table>
					<tr>
						<td>First Name</td>
						<td><input type="text" name="<?php echo COL_USER_FIRSTNAME; ?>"></td>
					</tr>
					<tr>
						<td>Last Name</td>
						<td><input type="text" name="<?php echo COL_USER_LASTNAME; ?>"></td>
					</tr>
					<tr>
						<td>Username</td>
						<td><input type="text" name="<?php echo COL_USER_USERNAME; ?>"></td>
					</tr>
					<tr>
						<td>Password</td>
						<td><input type="password" name="<?php echo COL_USER_PASSWORD; ?>"></td>
					</tr>
					<tr>
						<td>Repeat Password</td>
						<td><input type="password" name="<?php echo COL_USER_PASSWORD2; ?>"></td>
					</tr>
					<tr>
						<td>Country</td>
						<td><input type="text" name="<?php echo COL_USER_COUNTRY; ?>"></td>
					</tr>
					<tr>
						<td>City</td>
						<td><input type="text" name="<?php echo COL_USER_CITY; ?>"></td>
					</tr>
					<tr>
						<td>Post Code</td>
						<td><input type="text" name="<?php echo COL_USER_POSTCODE; ?>"></td>
					</tr>
					<tr>
						<td>Address</td>
						<td><input type="text" name="<?php echo COL_USER_ADDRESS; ?>"></td>
					</tr>
					<tr>
						<td>Email</td>
						<td><input type="text" name="<?php echo COL_USER_EMAIL; ?>"></td>
					</tr>
					<tr>
						<td>Date Of Birth</td>
						<td><input type="date" name="<?php echo COL_USER_BIRTHDAY; ?>"></td>
					</tr>
					<tr>
						<td>Gender</td>
						<td><input type="radio" name="<?php echo COL_USER_GENDER; ?>" value="m" checked/>Male
							<input type="radio" name="<?php echo COL_USER_GENDER; ?>" value="f" />Female
						</td>
					</tr>
					
					<tr>
						<td><input type="submit" name="register" value="Register"></td>
					</tr>
				</table>
			</form>
		</div>
	</body>
</html>
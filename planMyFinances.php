<?php
  require_once("Functions.php");
  require_once("classes/Expense.php");
  require_once("classes/Income.php");
  require_once("classes/DBUtils.php");
  require_once("classes/constants.php");
  #default boja pozadine
  $style = ":LightSeaGreen";

  $database = new DBUtils(DATABASE_INI_FILE);

  session_start();
  if(isset($_POST['addWish'])){
  	$item = htmlspecialchars($_POST['item']);
  	$amount = htmlspecialchars($_POST['amount']);

  	if (!isset($_SESSION["wishlist"])) {
			$_SESSION["wishlist"] = array();
		}
		$_SESSION["wishlist"][] = array("item" => $item, "amount" => $amount);
  }

?>
<!DOCTYPE html>
<html>
<head>
	<title>Plan My Finances</title>
	<link rel="stylesheet" href="w3.css">
</head>
<body style="background-color<?php echo $style;?>">
	<h1>Finances</h1>
	<?php  
    	Functions::top_menu("Finances");
      
      echo <<<DOUBLEQUOTES
<br><a href="./finances.php"><button class="w3-btn w3-round-xxlarge w3-grey">Back to Finances</button></a></br>
DOUBLEQUOTES;

    	$totalIncome = $database->showTotalIncome();
    	$totalExpense = $database->showTotalExpense();
    	$amountAvailable = $database->amountAvailable();

    	echo "<br><table border = 1'>
				<tr><th>Total income</th><th>Total expense</th><th>Available amount</th></tr>
				<tr><td>$totalIncome</td> <td>$totalExpense</td> <td>$amountAvailable</td></tr>
				</table>";
  	?>
    
  	<h2>Make a wishlist</h2>
  	<form action="./planMyFinances.php" method="POST">
  		Item: <input type="text" name="item"><br>
  		Amount: <input type="text" name="amount"><br>
  		<input type="submit" name="addWish" value="Save">
  	</form>

  	<h2>My wishlist</h2>
  	<?php
  		if(isset($_SESSION["wishlist"]) && !empty($_SESSION["wishlist"])){
  			if(isset($_GET['wishNum'])){
  				array_splice($_SESSION["wishlist"], htmlspecialchars($_GET['wishNum']),1);
  				header('Location = ./planMyFinances.php');
  			}
  			echo "<table border = 1'>
				<tr><th>Item</th><th>Amount</th><th>Delete from wishlist</th><th>Save as expense</th></tr>";
  			for ($wish = 0; $wish < count($_SESSION["wishlist"]); $wish++) {
  				echo "<tr><td>". $_SESSION["wishlist"][$wish]['item'] ."</td> <td>". $_SESSION["wishlist"][$wish]['amount'] ."</td><td><a href=\"./planMyFinances.php?wishNum=$wish\">Delete</a></td><td><a href=\"./addExpense.php?wishNum={$wish}&item={$_SESSION["wishlist"][$wish]['item']}&amount={$_SESSION["wishlist"][$wish]['amount']}\">Save</a></td></tr>";
  			}
  			echo "</table>";
  		}
  	?>

</body>
</html>
<?php
  require_once("Functions.php");
  require_once("classes/Expense.php");
  require_once("classes/Income.php");

?>
<!DOCTYPE html>
<html>
<head>
	<title>Add Expense</title>
	<link rel="stylesheet" href="w3.css">
</head>
<body style="background-image:url(images/backgrounds/bgIncome.jpg)">
	<h1>Add expense</h1>
	<?php  
    	Functions::top_menu("Finances");
      session_start();
      $item = '';
      $amount= '';
      if(isset($_GET['item']) && isset($_GET['amount']) && isset($_GET['wishNum'])){
        $item=htmlspecialchars($_GET['item']);
        $amount=htmlspecialchars($_GET['amount']);
        array_splice($_SESSION["wishlist"], htmlspecialchars($_GET['wishNum']),1);
      }
  ?>
  	<br>
  	<div style="background-color: rgba(242,242,242,0.8); height: 100%; width: 100%; border: 1px solid green" >
  	<form action="./finances.php" method="post">
  			<table>
  				<tr>
  					<td>Amount of your expense: </td> <td><input type="text" name="amount_exp" value="<?php echo "$amount" ?>"></td>
  				</tr>
          <tr>
            <td> Date: </td> <td><input type="date" name="date_exp" value="<?php echo date('Y-m-d', strtotime('today'));?>"></td>
          </tr>
  				<tr>
  					<td>Type: </td> <td> <select name="ess">
  						<option value='true'>essential</option>
  						<option value=''>other</option>
  					</select> </td>
  				</tr>
  				<tr><td>Item:</td><td><input type="text" name="item" value="<?php echo "$item" ?>"></td></tr>
  				<tr><td><input type="submit" name="expense" value="Add"></td></tr>
  			</table>
  		</form>

  	</div>

</body>
</html>
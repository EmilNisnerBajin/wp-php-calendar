<?php
  require_once("Functions.php");
  require_once("classes/Expense.php");
  require_once("classes/Income.php");

?>

<!DOCTYPE html>
<html>
<head>
	<title>Add Income</title>
	<link rel="stylesheet" href="w3.css">
</head>
<body style="background-image:url(images/backgrounds/bgIncome.jpg)">
	<h1>Finances</h1>
	<?php  
    	Functions::top_menu("Finances");
  	?> 
  	<br>
  	<div style="background-color: rgba(242,242,242,0.8); height: 100%; width: 100%; border: 1px solid green" >

  		<form action="./finances.php" method="post">
  			<table>
  				<tr>
  					<td>Amount of your income: </td> <td><input type="text" name="amount"></td>
  				</tr>
          <tr>
            <td>Date: </td> <td><input type="date" name="date" value="<?php echo date('Y-m-d', strtotime('today'));?>"></td>
          </tr>
  				<tr>
  					<td>Type: </td> <td> <select name="source">
  						<option value="paycheck">paycheck</option>
  						<option value="other">other</option>
  						<option value="zelenasenje">zelenasenje</option>
  						<option value="pranjePara">pranje para</option>
  					</select> </td>
  				</tr>
  				<tr><td><input type="submit" name="income" value="Add"></td></tr>
  			</table>
  		</form>

  	</div>

</body>
</html>
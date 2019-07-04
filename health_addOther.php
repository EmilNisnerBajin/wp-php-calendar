<?php
  session_start();
  require_once("Functions.php");
  require_once("classes/Health_cardio.php");
  require_once("classes/Health_strength.php");
  require_once("classes/Health_other.php");

?>

<!DOCTYPE html>
<html>
<head>

	<title>Add Other Excercise</title>
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

</head>

<body >

	<h1>Other</h1>

	<?php  

      Functions::top_menu("Health");

      
      $name = '';

      if(isset($_GET['name'])){

        $name   = htmlspecialchars($_GET['name']);

      }
  ?>

    <br>

    <div>

      <form action="./health_main.php" method="post">
        <table>

          <tr>
            <td> What  excercise were you doing ? </td> 
            <td><input type="text" name="o_name" value="<?php echo "$name" ?>"></td>
          </tr>

          <tr>
            <td> How long did you do the excercise for ? </td> 
            <td><input type="text" name="o_time" value=""></td>
          </tr>

          <tr>
            <td> Date of the excercise: </td> 
            <td><input type="date" name="o_date" value="<?php echo date('Y-m-d', strtotime('today'));?>"></td>
          </tr>

          <tr>
            <td><input type="submit" name="other" value="Add"></td>
          </tr>

        </table>

      </form>

    </div>

</body>

</html>
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

	<title>Add Strength Excercise</title>
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

</head>

<body >

	<h1>Strength</h1>

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
            <td> What strength excercise were you doing ? </td> 
            <td><input type="text" name="s_name" value="<?php echo "$name" ?>"></td>
          </tr>

          <tr>
            <td> How many repetitions did you do ?</td> 
            <td><input type="text" name="s_reps" value=""></td>
          </tr>

          <tr>
            <td> Date of the excercise: </td> 
            <td><input type="date" name="s_date" value="<?php echo date('Y-m-d', strtotime('today'));?>"></td>
          </tr>

          <tr>
            <td><input type="submit" name="strength" value="Add"></td>
          </tr>

        </table>

      </form>

    </div>

</body>

</html>
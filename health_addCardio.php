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

	<title>Add Cardio Excercise</title>
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

</head>

<body >

	<h1>Cardio</h1>

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
            <td> What cardio excercise were you doing ? </td> 
            <td><input type="text" name="c_name" value="<?php echo "$name" ?>"></td>
          </tr>

          <tr>
            <td>What type of cardio excercise were you doing ? </td> 
            <td> <select name="type_cardio">
              <option value='true'> running</option>
              <option value=''>     jumping</option>
              <option value=''>     cycling</option>
              <option value=''>     climbing</option>
              <option value=''>     other</option>
            </select> </td>
          </tr>

          <tr>
            <td> How long did you do cardio for ? </td> 
            <td><input type="text" name="c_time" value=""></td>
          </tr>

          <tr>
            <td> Date of the excercise: </td> 
            <td><input type="date" name="c_date" value="<?php echo date('Y-m-d', strtotime('today'));?>"></td>
          </tr>

          <tr>
            <td><input type="submit" name="cardio" value="Add"></td>
          </tr>

        </table>

      </form>

    </div>

</body>

</html>
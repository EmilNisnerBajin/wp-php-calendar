<?php
    session_start();
  if(isset($_POST['submit'])){
      $_SESSION['weight'] = htmlentities($_POST['weight']);
  }
#todo
# Uzmi podatke iz baze i primeni ih za izracunavanje BMI
# Dodaj u bazu podatke o vezbama i brojem koraka itd korisnika
# Daj korisniku neku grubu povratnu informaciju o tome sta treba da radi da bi popravio zdravlje
# ... ?
  require_once("Functions.php");
  require_once ("classes/User.php");
  require_once ("classes/DBUtils.php");


  # Za sada ne radi nista od znacaja
    if(isset($_POST["healthCalculation"])){
    $height     = htmlspecialchars($_POST["height"]);
    $weight     = htmlspecialchars($_POST["weight"]);
    $excercise  = htmlspecialchars($_POST["excercise"]);
    $eating     = htmlspecialchars($_POST["eating"]);
    $goal       = htmlspecialchars($_POST["goal"]);
  }
?>

<!DOCTYPE html>
<html>
<head>
  <title>Calculate your health</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body >
  <?php 
    
    #najprostije smestanje stringova u niz i biranje random stringa iz datog niza pri refresovanju strane
    $stringWelcomeOptions = array(
      ", for you !",
      ", always a priority!",
      ", always improving!",
      ", for a better life!",
      " - improve, adapt, overcome!",
      ", making you able to look at yourself in the mirror since 2019!",
      ", getting it right..most of the time!",
      ", recommended by 9 out of 10 doctors!",
      ", opposite of being unhealthy!",
    );
    $key = array_rand($stringWelcomeOptions);
    echo "<h1>Health". $stringWelcomeOptions[$key] ."</h1>";

    #navigacioni meni na vrhu
    Functions::top_menu("Health");
  ?>
        <br>
        <h2>Lets start by adding some excercises!</h2>
        

        <h3>Cardio excercise</h3>
        &nbsp;<button type="button" class="w3-btn w3-medium w3-blue" data-toggle="collapse" data-target="#cardio">Check Cardio</button>
        <div id="cardio" class="collapse">
          <br>
          Content
          
          <br>&nbsp;
          
          <a href="health_addCardio.php"><button class="w3-btn w3-small w3-green" >+</button></a>
          <button class="w3-btn w3-small w3-red" >-</button>

        </div>

    
        <h3>Strength excercise</h3>
        &nbsp;
        <button class="w3-btn w3-medium w3-blue" data-toggle="collapse" data-target="#strength">Check Strength</button></a>
        <div id="strength" class="collapse">
          <br>
          Content2

          <br>
          <a href="health_addStrength.php"><button class="w3-btn w3-small w3-green" >+</button></a>
          <button class="w3-btn w3-small w3-red" >-</button>
        </div>


        <h3>Other excercise</h3>
        &nbsp;
        <button class="w3-btn w3-medium w3-blue" data-toggle="collapse" data-target="#other">Check Other</button>
        <div id="other" class="collapse">

          Content3
          <br>
          <a href="health_addOther.php"><button class="w3-btn w3-small w3-green" >+</button></a>
          <button class="w3-btn w3-small w3-red" >-</button>
        </div>


        <h3>Change Weight</h3>
        &nbsp;
        <button class="w3-btn w3-medium w3-red" data-toggle="collapse" data-target="  #weight">Update your Weight</button></a>
        <div id="weight" class="collapse">

          

          <?php 
            if (isset($_POST['weight'])) { 
              echo "Your current weight is: <br>" .$_POST['weight'] . " kg"; 
            } else {
              echo "Seems like we cant find your current weight, please enter your weight here or click the calculate my health button and fill up the form !";
            }
          ?>
          <br>
          Enter your new weight:
          <form action="health_main.php" method="POST" id ="asd">

            <input type="text" name="weight">
            <input type="submit" name="asd" value="Change" class="w3-btn w3-small w3-green">

          </form>
        </div>

        <br><br><br>

        

        <?php 
          if(isset($_POST["healthCalculation"])){
            echo "";
            echo "For your height of: ". htmlspecialchars($_POST['height']) . " cm <br>";
            echo "For your weight of: ". htmlspecialchars($_POST['weight']) . " kg <br>";
            
            #echo $user->getHeight(); 
            #echo $user->getWeight(); 
            #racunanje BMI
            $bmi = 0;
            $wght = (int)$_POST["weight"];
            $hght = (int)$_POST["height"]/100;
            $bmi = $wght / ($hght * $hght);

            echo "<b>Your BMI is equal to: " . round($bmi)." </b><br><br>";

            #ispis grube BMI procene
            if ($bmi <= 18.5) {
              echo "And you are <b>underweight</b>! You should gain a few pounds ;) <br> ";
              echo "We recommend that you do some strength excercises ! <br>";

            }else if ($bmi > 18.5 && $bmi <=25) {
              echo "And you have a <b>normal</b> weight, good job! <br> ";
              echo "We recommend that you keep on doing the good work <br>";

            }else if ($bmi > 25){
              echo "You are <b>overweight</b>! We will need to work on that! No worries , we got you! <br>";
              echo "We recommend you do some cardio excercises! <br>";
          }

          echo "<br>";
          ?>
          <br>
          <a href="healthCalculation.php"><button class="w3-btn w3-large w3-blue">Realculate my BMI!</button></a>
          <?php

        }else {
          ?>
          <br>
          <a href="healthCalculation.php"><button class="w3-btn w3-large w3-blue">Calculate my BMI!</button></a>
          <?php
          echo "<br>";

        }
      ?>

  
</body>
</html>
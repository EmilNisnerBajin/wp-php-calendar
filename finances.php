<?php
  require_once("Functions.php");
  require_once("classes/Expense.php");
  require_once("classes/Income.php");
  require_once("classes/DBUtils.php");
  require_once("classes/constants.php");
  #require_once("classes/SessionChecker.php");

  #default boja pozadine
  $style = ":LightSeaGreen";

  $database = new DBUtils(DATABASE_INI_FILE);

  #Za radio button 
  $checked = "";

  #Ako je korisnik odabrao sta bi da mu se prikazuje kad otvori stranicu, stavljamo izbor u cookie
  if (isset($_POST['defaultIzlistavanje'])) {
    $period = $_POST['period'];
    setcookie("period", $period, time()+60); #60 sec samo za proveru
    header("Location: finances.php?inc=$period");
  }


  if (!isset($_SESSION)) {
    session_start();
  }

  if (isset($_GET['inc']) && isset($_GET['date'])) {
    $date = htmlspecialchars($_GET['date']);
    if (!isset($_SESSION['date'])) {
      $_SESSION['date'] = array(); #pravim niz sa poslednje pregledanim datumima u sesiji
    }
      $_SESSION['date'][] = $date;
    if (count($_SESSION['date']) > MAX_DATE) {
      array_shift($_SESSION['date']);
    }
  }


  if (isset($_POST['income'])) {
    $amount = htmlspecialchars($_POST["amount"]); //Samo je ovde unos korisnika gde moze da bude neka js skripta, ostala dva imaju unapred isplanirane vrednosti, ali neka stoji
    $date = htmlspecialchars($_POST["date"]);
    $source = htmlspecialchars($_POST["source"]); 
    $database->addIncome($date, $source, $amount);
  }

  if (isset($_POST['expense'])) {
    $date = htmlspecialchars($_POST["date_exp"]);
    $ess = htmlspecialchars($_POST["ess"]); 
    $item = htmlspecialchars($_POST["item"]); 
    $amount = htmlspecialchars($_POST["amount_exp"]);
    
    $database->addExpense($date, $ess, $item, $amount);
  }

?>

<!DOCTYPE html>
<html>
<head>
	<title>Finances</title>
	<link rel="stylesheet" href="w3.css">
</head>
<body style="background-color<?php echo $style;?>">
	<h1>Finances</h1>
	<?php  
    	Functions::top_menu("Finances");
  	?> 

  	<div class="w3-container w3-margin-top w3-margin-bottom">
    	<a href="./addIncome.php"><button class="w3-btn w3-round-xxlarge w3-green">Add income</button></a>
    	<a href="./addExpense.php"><button class="w3-btn w3-round-xxlarge w3-red">Add expense</button></a>
  	</div>

    <!-- Default colors -->
    <?php
      if(isset($_POST['changeColors'])){
        setcookie("lsi", htmlspecialchars($_POST['lsi']), time() + 3600);
        setcookie("lis", htmlspecialchars($_POST['lis']), time() + 3600);
        setcookie("lse", htmlspecialchars($_POST['lse']), time() + 3600);
        setcookie("les", htmlspecialchars($_POST['les']), time() + 3600);
        setcookie("defCol", htmlspecialchars($_POST['defCol']), time() + 3600);
        header("Location: ./finances.php");
      }

      if(isset($_POST['restoreDefaults'])){
        setcookie("lsi", htmlspecialchars($_POST['lsi']), time() - 3600);
        setcookie("lis", htmlspecialchars($_POST['lis']), time() - 3600);
        setcookie("lse", htmlspecialchars($_POST['lse']), time() - 3600);
        setcookie("les", htmlspecialchars($_POST['les']), time() - 3600);
        setcookie("defCol", htmlspecialchars($_POST['defCol']), time() - 3600);
        header("Location: ./finances.php");
      }
    ?>
    <?php 
      $changeColors = Functions::setChangeColors();
    ?>
    <form action="./finances.php" method="post">
      Largest single income:
      <input type="color" name="lsi" value=<?= $changeColors['lsi'] ?>> ||
      Largest income sum:
      <input type="color" name="lis" value=<?= $changeColors['lis'] ?>> ||
      Largest single expense:
      <input type="color" name="lse" value=<?= $changeColors['lse'] ?>> ||
      Largest expense sum:
      <input type="color" name="les" value=<?= $changeColors['les'] ?>> ||
      Default color:
      <input type="color" name="defCol" value=<?= $changeColors['defCol'] ?>> ||
      <input type="submit" name="changeColors" value="Save">
      <input type="submit" name="restoreDefaults" value="Defaults">
    </form>
  	
    <br>

    <!-- Odabir default izlistavanja prihoda i troskova -->
    <form action="./finances.php", method="post"> 
      <input type="radio" name="period" value="day" <?php echo "$checked";?>> Day
      <input type="radio" name="period" value="month" <?php echo "$checked";?>> Month
      <input type="radio" name="period" value="all" <?php echo "$checked";?>> All <br>
      <input type="submit" name="defaultIzlistavanje" value="Save my choice">
    </form>
    

    <!-- Poslednje pregledani prihodi -->
    <?php 

    if (isset($_SESSION['date'])) {
      $datumi = $_SESSION['date'];
      $html = "Recently seen income dates: <br>";
      foreach ($datumi as $datum) {
        $html .= " <a href=\"?date=$datum\">$datum</a> ";
      }
      echo $html . "<br>";
    }

    #ako se klikne na link datuma iz sesije, cilj je da se prikaze samo ona tabela sa prihodima
    if (isset($_GET['date'])) {
      $database->listDaysIncome(htmlspecialchars($_GET['date']));
    }

    ?>

    <br>

    <table border="1">
      <tr>
        <th>Available amount</th><th>Income details</th><th>Expense dets</th><th><a href="./finances.php">Close</a></th>
      </tr>
      <tr>
        <td><?php echo $database->amountAvailable();?></td>
        <td><a href="?inc=day">Day </a><a href="?inc=month">Month </a><a href="?inc">All </a></td>
        <td><a href="?exp=day">Day </a><a href="?exp=month">Month </a><a href="?exp">All </a></td>
      </tr>
    </table>

    <br>

  	<!--List of incomes-->
    <?php 
      if(isset($_GET['inc'])) { 
        if (!strcmp($_GET['inc'], 'day')) {
           $database->showDayMonth(TBL_INCOME);  
        } elseif(!strcmp($_GET['inc'], 'month')) {
            $database->showDayMonth(TBL_INCOME, true);
        } else {
            $database->listAllIncomes();
        }
      }
      if(isset($_GET['exp'])) { 
          if(!strcmp($_GET['exp'], 'day')) {
            $database->showDayMonth(TBL_EXP);
          } elseif(!strcmp($_GET['exp'], 'month')) {
            $database->showDayMonth(TBL_EXP, true);
          } else {
            $database->listAllExpenses();
          }
      }
      
      # ------------------------------------------------------------------------------------------------------
      if(isset($_GET['inc']) && isset($_GET['date'])){
        if($_GET['inc']=='day'){
          $database->listDaysIncome(htmlspecialchars($_GET['date']));
        }elseif($_GET['inc']=='month'){
          $database->listMonthsIncome(htmlspecialchars($_GET['date']));
        }
      }
      if(isset($_GET['exp']) && isset($_GET['date'])){
        if($_GET['exp']=='day'){
          $database->listDaysExpense(htmlspecialchars($_GET['date']));
        }elseif($_GET['exp']=='month'){
          $database->listMonthsExpense(htmlspecialchars($_GET['date']));
        }
      }
      #--------------------------------------------------------------------------------------------------------
    ?>
    <br>
    <a href="planMyFinances.php"><button class="w3-btn w3-round-xxlarge w3-blue">Plan My Finances</button></a>

</body>
</html>
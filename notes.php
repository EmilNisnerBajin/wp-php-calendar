<?php
  require_once("Functions.php");
  require_once("note.php");

  #default boja pozadine
  $style = ":LightSeaGreen";
  
  if (isset($_GET["color"])) {
    $red = rand(0, 255);
    $green = rand(0, 255);
    $blue = rand(0, 255);
    $style = ":rgb(" . $red .",". $green .",". $blue . ")";
  } 
  

  #Ovde gledam da li je user sacuvao belesku kad je bio na writeANote stranici,
  #i ako jeste, obavestavam ga da je sve ok.
  if (isset($_POST["saved"])) {
    $text = htmlspecialchars($_POST["text"]);
    $title = htmlspecialchars($_POST["title"]);
    $date = htmlspecialchars($_POST["date"]);
    $locked = htmlspecialchars($_POST["locked"]);
    $n = new Note($title,$text, $date, $locked);
    Functions::saveNote($n);
  }

  if(isset($_GET['update'])) {
    $old_title = htmlspecialchars($_POST["old_title"]);
    $text = htmlspecialchars($_POST["text"]);
    $title = htmlspecialchars($_POST["title"]);
    $date = htmlspecialchars($_POST["date"]);
    $locked = htmlspecialchars($_POST["locked"]);
    $newNote = new Note($title,$text, $date, $locked);
    Functions::editNote($newNote, $old_title);
  }

  if(isset($_GET["title_to_delete"])){
    $title = htmlspecialchars($_GET["title_to_delete"]);
    Functions::deleteNote($title);
  }
?>

<?php
  $json = file_get_contents("notes.json");
  $data = json_decode($json, true);
  $notes = $data["content"]["notes"];
  Functions::sort_notes($notes);

  $foldersHtml = array("pub" => array(),
                   "priv" => array());
  foreach ($notes as $note){
    $locked = $note["locked"];
    $pub_priv = "public";
    if($locked){
      $pub_priv = "public";
    }else{
      $pub_priv = "private";
    }

    $drop = "<div class=\"w3-dropdown-hover\">
              <button class=\"fa fa-bars\" w3-black w3-tiny w3-lime\"></button>
              <div class=\"w3-dropdown-content w3-bar-block w3-border\">
                <a href=\"editNote.php?title={$note['title']}\" class=\"w3-bar-item w3-button\">Edit</a>
                <a href=\"notes.php?title_to_delete={$note['title']}\" class=\"w3-bar-item w3-button\">Delete</a>
                <a href=\"print.php?title={$note['title']}\" class=\"w3-bar-item w3-button\">Print</a>
              </div>
          </div>";
          
    $hmtlKod = "<div style=\"border:16px solid white\" class=\"w3-panel w3-hover-border-green\">
                  <h4>{$note["title"]} $drop </h4>
                  <p>{$note["text"]}</p>
                  </div>";
    $foldersHtml[$locked ? "priv" : "pub"][]= $hmtlKod;
  }
?>

<!DOCTYPE html>
<html>
<head>
	<title>Notes</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body style="background-color<?php echo $style;?>"> <!--Sklonila sam class="w3-container w3-lime zbog menjanja boje-->
	<h1>My Notes</h1>
	<?php  
    Functions::top_menu("Notes");
  ?>  
     <!--Dugme koje nas odvodi na stranicu za pisanje nove beleske-->
  <div class="w3-container w3-margin-top w3-margin-bottom">
    <a href="writeANote.php"><button class="w3-btn w3-round-xxlarge w3-green">Add new note</button></a>
    <a href="?color"><button class="w3-btn w3-round-xxlarge w3-blue">Change backgorund</button></a>
  </div>
  


  <form method="post">
    <input type="submit" name="pub_folder" id="pub_folder" value="Public (<?=count($foldersHtml['pub'])?>)" class="w3-btn w3-block w3-black w3-left-align"/>
  </form>

  <?php
    $show_pub = "";
    $show_priv = "";
    if(isset($_POST['pub_folder'])){
      $show_pub = "w3-show";
    }

    if(isset($_POST['priv_folder'])){
      $show_priv = "w3-show";
    }
  ?>
  <div id="pub_content" class="w3-container w3-hide <?php echo $show_pub ?>">
    <br>
    <?php 
      foreach ($foldersHtml["pub"] as $html) {
        echo $html;
      }
    ?>
  </div>

  <form method="post">
    <input type="submit" name="priv_folder" id="priv_folder" value="Private (<?=count($foldersHtml['priv'])?>)" class="w3-btn w3-block w3-black w3-left-align"/>
  </form>
    <div id="pub_content" class="w3-container w3-hide <?php echo $show_priv ?>">
      <br>
      Password: <form method="post"><input type="password" name="pass"></input></form>
      <?php
      $good_pass = ""; 
        if(isset($_POST['pass']) && htmlspecialchars($_POST['pass']) == "sola123"){
          $good_pass = "w3-show";
        }
      ?>
    </div>
    <div id="pub_content" class="w3-container w3-hide <?php echo $good_pass ?>">
      <br>
      <?php 
        foreach ($foldersHtml["priv"] as $html) {
          echo $html;
        }
      ?>
    </div>

</body>
</html>
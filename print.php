<?php
  require_once("Functions.php");
  require_once("note.php");
?>

<!DOCTYPE html>
<html>
<head>
	<title>Edit note</title>
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>
	<h1>Printing notes</h1> 
	<?php  
    Functions::top_menu("Notes");

    $json = file_get_contents("notes.json");
    $data = json_decode($json, true);
    $notes = $data["content"]["notes"];

    $note;

    if(isset($_GET["title"])) {
      $naslov = htmlspecialchars($_GET["title"]);
      foreach ($notes as $n) {
       if (!strcmp($n["title"], $naslov)) {
          $note = $n;
        }
      }
    }

    Functions::printTo_txt($note);

  ?>

 
</body>
</html>
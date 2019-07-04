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
<body class="w3-container w3-lime">
	<h1>Editing</h1> 
	<?php  
    Functions::top_menu("Notes");

    $json = file_get_contents("notes.json");
    $data = json_decode($json, true);
    $notes = $data["content"]["notes"];
    #Bolje ovako, jer ako nije u if-u a neko pokusa da otvori editNote dobice error
    if(isset($_GET["title"])) {
      $naslov = htmlspecialchars($_GET["title"]);
      foreach ($notes as $n) {
       if (!strcmp($n["title"], $naslov)) {
          $note = $n;
        }
      }
      ?>
      <br>
       <!-- Forma je ista kao za unos, samo sa default metodama, da li je to problem? -->
      <form action="./notes.php?update=1" method="post" enctype="multipart/form-data" id="editing">
        <input type="hidden" name="old_title" value="<?=$naslov?>" />
        Title: <input type="text" name="title" value="<?php echo $note['title']?>"> <br>
        Date:  <input type="date" name="date" value="<?php echo date('Y-m-d', strtotime($note['date']));?>"> <br> 
        Note: <textarea rows="4" cols="50" name="text" idform="editing" ><?php echo $note['text']?></textarea> <br>
        Public or a private note?
        <select name="locked">
          <option value=<?php echo $note['locked']? true : false ?>><?php echo $note['locked']? 'priv' : 'pub'?></option>
          <option value=<?php echo $note['locked']? false : true ?>><?php echo $note['locked']? 'pub' : 'priv'?></option>
        </select> <br>
        <input type="submit" value="Edit" name="edit" >
      </form>
  <?php
    }
  ?>
</body>
</html>
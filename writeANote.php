<?php
  require_once("Functions.php");
?>

<!DOCTYPE html>
<html>
<head>
	<title>Compose a new note</title>
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body class="w3-container w3-lime">
	<h1>Writing new notes</h1> 
	<?php  
    Functions::top_menu("Notes");
  ?>

  <br>
	 <!-- action je cuvanje beleske i odlazak na sve beleske -->
  <form action="notes.php" method="POST" id="writing">
    Title: <input type="text" name="title"> <br>
    Date:  <input type="date" name="date" value="<?php echo date('Y-m-d') ?>"> <br>
    Note: <textarea rows="4" cols="50" name="text" idform="writing"></textarea> <br>
    Public or a private note?
    <select name="locked">
      <option value="">pub</option>
      <option value="true">priv</option>
    </select> <br>
    <input type="submit" value="Save" name="saved"> <!-- ako je ovo dugme setovano, beleska je sacuvana i treba da je ubacimo u bazu, odemo na notes.php i izlistamo je, i obavestimo korisnika da je sve OK -->
  </form>
</body>
</html>
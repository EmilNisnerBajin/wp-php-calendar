<?php
	require_once("note.php");

	class Functions{

    #zbog ponavljanja koda
    private static function openJSONFile() {
      $json = file_get_contents("notes.json");
      $data = json_decode($json, true);
      return $data;
    }

		static function top_menu($name){
			$kod = '<div class="w3-bar w3-border w3-light-grey">
			    	<a href="./pocetnaStranica.php" class="w3-bar-item w3-button">Home</a>
			    	<a href="./notes.php" class="w3-bar-item w3-button">Notes</a>
            <a href="./finances.php" class="w3-bar-item w3-button">Finances</a>
			    	<a href="./calendar.php" class="w3-bar-item w3-button">Calendar</a>
            <a href="./health_main.php" class="w3-bar-item w3-button">Health</a>
			    	<a href="profil.php" class="w3-bar-item w3-button">My Profile</a>
			    	<a href="./logout.php" class="w3-bar-item w3-button">Logout</a>
			  	 </div>';
			$pos = strpos($kod, $name);
			echo substr_replace($kod," w3-green\">", $pos-2, 2);
		}

		
		static function deleteNote($title) {
  		$data = Functions::openJSONFile();
  			$newArray = [
  				'content' => [
  					'notes' => []
  				]
  			];

  			foreach($data['content']['notes'] as $i => $note) {
  				if($note['title'] != $title) {
  					$newArray['content']['notes'][] = $note;
  				}
  			}
  			$json = json_encode($newArray, JSON_PRETTY_PRINT);
  			if(file_put_contents("notes.json", $json)){
  				echo "<script> alert(\"Your note has been successfully deleted!\"); window.location='./notes.php'</script>";
  			}else{
  				echo "<script> alert(\"Oops, something went wrong!\"); window.location='./notes.php'</script>";
  			}
		}

		static function saveNote($note) {
  			$beleska = array("date" => $note->getDate(),"title" => $note->getTitle(), "text" => $note->getText(), "locked" => $note->isLocked());

  			$arr_note = Functions::openJSONFile();
  			
  			array_push($arr_note["content"]["notes"], $beleska);
        Functions::sort_notes($arr_note); #soriramo ovdje da bi se tako cuvalo u fajl
  			#kovertujemo niz u json
  			$jsondata = json_encode($arr_note, JSON_PRETTY_PRINT);
  			$saved = file_put_contents("notes.json", $jsondata);
  			if($saved){
  				echo "<script> alert(\"Your note has been successfully saved!\");</script>";
  			}else{
  				echo "<script> alert(\"Oops, something went wrong!\");</script>";
  			}
		}

		#Sofija
		static function editNote($newNote, $oldNoteTitle) {
      $data = Functions::openJSONFile();
  			foreach($data['content']['notes'] as $i => $note) {
  				if($note['title'] == $oldNoteTitle) {
  					$data['content']['notes'][$i] = $newNote->toArray();
  				}
  			}

  			$json = json_encode($data, JSON_PRETTY_PRINT);
  			if(file_put_contents("notes.json", $json)){
  				echo "<script> alert(\"Your note has been successfully edited!\"); window.location='./notes.php'</script>";
  			}else{
  				echo "<script> alert(\"Oops, something went wrong!\"); window.location='./notes.php'</script>";
  			}
		}

		static function printTo_txt($note) {
      #ime fajla ce biti po defaultu ime beleske, pa neka korisnik menja kasnije ako mu ne odgovara
      $ime = "{$note['title']}.txt";
      $tekst = "Title: {$note['title']}\nDate: {$note['date']}\nText: {$note['text']}";
      $fajl = fopen("files/$ime", 'w');
      fwrite($fajl, $tekst);
      if (fclose($fajl)) {
        echo "<script> alert(\"Your note has been saved to your repository!\"); window.location='./notes.php'</script>";
      }else{
          echo "<script> alert(\"Ooops, seems like something went wrong!\"); window.location='./notes.php'</script>";
      }
		}

    static function sort_notes(&$arr){
      array_multisort($arr, SORT_DESC);
    }

    static function setChangeColors(){
       if(isset($_COOKIE['lsi'])){
          $changeColors =[
          'lsi' => $_COOKIE['lsi'], 
          'lis' => $_COOKIE['lis'],
          'lse' => $_COOKIE['lse'],
          'les' => $_COOKIE['les'],
          'defCol' => $_COOKIE['defCol']
          ];
        }else{
          $changeColors =[
          'lsi' => "#00ff00", 
          'lis' => "#ffff00",
          'lse' => "#ff0000",
          'les' => "#ff9600",
          'defCol' => "#f44283"
          ];
        }
        return $changeColors;
    }



  }
?>
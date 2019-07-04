<?php
	class DownloadTask{

		public function downloadJson($tasks){

			$json = json_encode($tasks,JSON_PRETTY_PRINT);
			header('Content-disposition: attachment; filename=jsonFile.json');
			header('Content-type: application/json');
			echo( $json);
			exit();
			
		}
	}
?>
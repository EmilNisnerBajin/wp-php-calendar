<?php
	class TimeUtils{

		// Return first date of a week
		private static function getWeekFirstDate($date){
			$day = new Day($date);
			$dayNumber = $day->getWeekDay()["number"];
			return date('Y-m-d',(strtotime("-$dayNumber day" , strtotime($date))));
		}

		// Return last date of a week
		private static function getWeekLastDate($date){
			$day = new Day($date);
			$dayNumber = $day->getWeekDay()["number"];
			$dayNumber = 6 - $dayNumber ;
			return date('Y-m-d',(strtotime("+$dayNumber day" , strtotime($date))));
		}

		// Return first and last date of a week
		public static function getWeekBeginingAndEnd($date){
			$week = array("begin"=>TimeUtils::getWeekFirstDate($date),"end"=>TimeUtils::getWeekLastDate($date));
			return $week;
		}
	}
?>
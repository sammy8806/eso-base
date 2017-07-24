<?php
/**
 * @author Steven Tappert
 * @link   admin@steven-tappert.de
 **/

// http://de3.php.net/manual/de/class.dateperiod.php

/**
 * @class
 * Pseudo class from DateTimeExceptions
 */
class DateTimeException extends Exception {

}

/**
 * @class
 */
class DateTimeManager {

	/**
	 * @static
	 * Generate difference between 2 Dates
	 *
	 * @param DateTime $from
	 * @param DateTime $to
	 *
	 * @return DateInterval
	 */
	public static function DateDiff(DateTime $from, DateTime $to) {
		return $from->diff($to);
	}

	/**
	 * @static
	 * Converts a string in format: "d.m.Y" into an DateTime Object
	 *
	 * @param $String
	 *
	 * @return DateTime
	 */
	public static function ConvertStringToDateTime($String) {
		$DateString = explode('.', $String);
		for ($i = 0; $i < count($DateString); $i++) {
			$DateString[$i] = (int)$DateString[$i];
			if (!is_int($DateString[$i]) || $DateString[$i] < 1) {
				throw new DateTimeException('[' . __CLASS__ . '::' . __FUNCTION__ . '()] ' .
					'$String must be an valid Date String in the "d.m.Y" format!');
			}
		}
		return new DateTime($DateString[2] . '-' . $DateString[1] . '-' .
			$DateString[0], new DateTimeZone(date_default_timezone_get()));
	}

	/**
	 * @static
	 *           Get Name of the day-number in german or english
	 *
	 * @param        $day
	 * @param string $langCode
	 *
	 * @internal param string $lang
	 *
	 * @return mixed
	 */
	public static function getDayName($day, $langCode = 'de') {
		$lang['en'] = array(1 => 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
		$lang['de'] = array(1 => 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag');
		return $lang[$langCode][$day];
	}

	/**
	 * @static
	 *
	 * @param $start
	 * @param $end
	 * @param $interval
	 *
	 * @return array
	 */
	public static function ConvertIntervalToSingle($start, $end, $interval) {
		$output = array();
		$start  = self::ConvertStringToDateTime($start);
		if ($interval == "0") {
			$output[] = $start;
		} else {
			$end      = self::ConvertStringToDateTime($end);
			$interval = new DateInterval('P' . $interval);

			$difference = (int)self::DateDiff($start, $end)->format("%R%a");
			if ($difference < 1) {
				throw new DateTimeException('Interval must be positive!');
			}

			foreach (new DatePeriod($start, $interval, $end) as $date) {
				$output[] = $date;
			}
		}
		return $output;
	}

}

?>
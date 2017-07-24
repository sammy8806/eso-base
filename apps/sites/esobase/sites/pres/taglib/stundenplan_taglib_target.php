<?php

/**
 *
 */
class stundenplan_taglib_target {

	/**
	 * @static
	 *
	 * @param array $possible
	 *
	 * @return bool|int|string
	 */
	static public function getTarget($possible = array('klasse', 'lehrer', 'raum')) {
		$possibleTargets = array();
		foreach ($possible as $entry)
		{
			$possibleTargets[$entry] = NULL;
		}

		$entrys = RequestHandler::getValues($possibleTargets);
		$target = false;

		foreach ($entrys as $entry => $value) {
			if (!empty($entrys[$entry])) {
				$target = $entry;
			}
		}
		return $target;
	}

	/**
	 * @static
	 *
	 * @param bool $possible
	 *
	 * @return string
	 */
	static public function getTargetValue($possible = false) {
		if (is_array($possible)) {
			return RequestHandler::getValue(self::getTarget($possible));
		}
		else
		{
			return RequestHandler::getValue(self::getTarget());
		}
	}

	/**
	 * @static
	 *
	 * Checks if an Target is transmitted or redirect to /page/home
	 *
	 * @param bool $_redirect
	 */
	static public function checkTarget($_redirect = true) {
		$target = self::getTarget();
		if ($target === false && $_redirect === true) {
			HeaderManager::redirect('/page/home');
		}
	}
}

?>

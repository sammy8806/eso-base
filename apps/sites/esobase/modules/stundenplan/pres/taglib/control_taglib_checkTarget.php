<?php
/**
 * @author Steven Tappert
 * @link   admin@steven-tappert.de
 **/

import("sites::esobase::modules::stundenplan::pres::taglib", "stundenplan_taglib_initGorm");

class control_taglib_checkTarget {

	/**
	 * Check an definded target if it exists and return true, if not false.
	 *
	 * @param array  $target array('target','value')
	 * @param null   $relationTable
	 * @param string $relationBase
	 *
	 * @return bool
	 */
	public static function checkTargetExistsInDatabase( array $target, $relationTable = NULL, $relationBase = 'Unterricht' ) {
		$gorm = new stundenplan_taglib_initGorm();

		if ( empty( $relationTable ) ) {
			$relationTable = $relationBase . '2' . ucfirst($target['target']);
		}

		$crit = new GenericCriterionObject();
		$crit->addPropertyIndicator('Kuerzel', $target['value']);

		$obj = $gorm->orm->loadObjectsWithRelation(ucfirst($target['target']), $relationTable, $crit);

		if ( $obj ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @param        $target
	 * @param        $redirectTarget
	 * @param null   $relationTable
	 * @param string $relationBase
	 */
	public static function redirectIfTargetNotExists( $target, $redirectTarget, $relationTable = NULL, $relationBase = 'Unterricht' ) {
		if ( !self::checkTargetExistsInDatabase($target, $relationTable, $relationBase) ) {
			HeaderManager::redirect($redirectTarget);
		}
	}

}

?>
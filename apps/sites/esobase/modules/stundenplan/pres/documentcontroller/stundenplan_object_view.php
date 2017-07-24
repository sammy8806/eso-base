<?php
/**
 * @author Steven Tappert
 * @link   admin@steven-tappert.de
 **/
class stundenplan_object_view extends base_controller {

	protected $orm;

	public function __construct() {
		$ormFact   = &$this->getServiceObject('modules::genericormapper::data', 'GenericORMapperFactory');
		$this->orm = &$ormFact->getGenericORMapper("sites::esobase", "esobase", "MySQLi");
	}

	/**
	 * @public
	 *         List $attribute from GORM Object $target
	 *         If Relation given, it must relate to the Object
	 *
	 * @param        $target
	 * @param        $attribute
	 * @param string $order
	 * @param string $match
	 * @param bool   $relation
	 *
	 * @return mixed
	 * @author Steven Tappert
	 * @version
	 *         Version 0.1, 21.01.2012
	 */
	public function listAttribute( $target, $attribute, $order = 'ASC', $match = '%', $relation = false ) {
		$crit = new GenericCriterionObject();
		$crit->addPropertyIndicator($attribute, $match);
		$crit->addOrderIndicator($attribute, $order);

		if ( !$relation ) {
			return $this->orm->loadObjectListByCriterion($target, $crit);
		} else {
			return $this->orm->loadObjectsWithRelation($target, $relation, $crit);
		}
	}

	/**
	 * @private
	 * @param string   $targetSource
	 * @param array    $target
	 * @param int      $hour
	 * @param int      $day
	 * @param string   $targetRelation
	 *
	 * @return GenericDomainObject
	 */
	private function viewTimetableHour( $targetSource, array $target, $hour, $day, $targetRelation = 'unterricht' ) {
		/*		$rel = new GenericDomainObject($target);
		  $rel->setProperty($target."ID", 1);

		  $crit = new GenericCriterionObject();
		  $crit
			  ->addPropertyIndicator('Tag', $day)
			  ->addPropertyIndicator('Stunde', $hour)
			  ->addRelationIndicator($targetRelation.'2'.$target, $rel)
			  ->addOrderIndicator('Kuerzel')
			  ->addLoadedProperty('Tag')
			  ->addLoadedProperty('Stunde')
		  ;
  */
		// ini_set('max_execution_time', 5);

		/*
		 *  SELECT `ent_lehrer`.Kuerzel
			FROM `ent_unterricht`
			JOIN `ass_unterricht2klasse` ON `UnterrichtID` = `ass_unterricht2klasse`.`Source_UnterrichtID`
			JOIN `ent_klasse` ON `ass_unterricht2klasse`.`Target_KlasseID` = KlasseID
			JOIN `ass_unterricht2lehrer` ON `UnterrichtID` = `ass_unterricht2lehrer`.`Source_UnterrichtID`
			JOIN `ent_lehrer` ON `ass_unterricht2lehrer`.Target_LehrerID = LehrerID
			WHERE `ent_unterricht`.Tag = 1
			AND `ent_unterricht`.Stunde = 3
			AND `ent_klasse`.`Kuerzel` LIKE 'B7VTI2A'
		 *
		 */

		$targetSource_lower = strtolower($targetSource);
		$target_lower       = strtolower($target['target']);
		$targetRelation_uc  = ucfirst($targetRelation);

		$sql = "
		SELECT `ent_" . $targetSource_lower . "`.Kuerzel FROM `ent_" . $targetRelation . "`

		JOIN `ass_" . $targetRelation . "2" . $target_lower . "`         ON  `" . $targetRelation_uc . "ID` = `ass_" .
		       $targetRelation . "2" . $target_lower . "`.`Source_" . $targetRelation_uc . "ID`
		JOIN `ent_" . $target_lower . "`        ON  `ass_" . $targetRelation . "2" . $target_lower . "`.Target_" .
		       $target['target'] . "ID = " . $target['target'] . "ID

		JOIN `ass_" . $targetRelation . "2" . $targetSource_lower . "` ON  `" . $targetRelation_uc . "ID` = `ass_" .
		       $targetRelation . "2" . $targetSource_lower . "`.`Source_" . $targetRelation_uc . "ID`
		JOIN `ent_" . $targetSource_lower . "`            ON  `ass_" . $targetRelation . "2" . $targetSource_lower .
		       "`.Target_" . $targetSource . "ID = " . $targetSource . "ID

		WHERE	`ent_" . $target_lower . "`.`Kuerzel` LIKE '" . $target['value'] . "'
		AND     `ent_" . $targetRelation . "`.Tag = " . $day . "
		AND		`ent_" . $targetRelation . "`.Stunde = " . $hour;

		return $this->orm->loadObjectListByTextStatement($target['target'], $sql);

		if ( sizeof($out) > 1 ) {
			return $out;
		} elseif ( sizeof($out) < 0 ) {
			return $out[0];
		}
		// return $this->orm->loadObjectListByCriterion($target, $crit);
	}

	/**
	 * @param        $targetSource
	 * @param array  $target
	 * @param        $hour
	 * @param        $day
	 * @param string $targetRelation
	 *
	 * @return GenericDomainObject
	 */
	public function viewHour( $targetSource, array $target, $hour, $day, $targetRelation = 'unterricht' ) {
		if ( $targetRelation == 'unterricht' ) {
			return $this->viewTimetableHour($targetSource, $target, $hour, $day, $targetRelation);
		} elseif ( $targetRelation == 'raumplan' ) {
			return $this->viewTimetableHour($targetSource, $target, $hour, $day, $targetRelation);
		}

	}

	public function transformContent() {

	}

}

?>
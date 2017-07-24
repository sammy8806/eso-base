<?php
/**
 * @author Steven Tappert
 * @link   admin@steven-tappert.de
 **/

import("sites::esobase::modules::stundenplan::pres::taglib", "stundenplan_taglib_initGorm");

/**
 * @class
 */
class stundenplanverwaltung_biz extends APFObject {
	/**
	 * @var stundenplan_taglib_initGorm
	 */
	private $gorm;

	/**
	 * @public
	 * Init the GORM
	 */
	public function __construct() {
		$gorm       = new stundenplan_taglib_initGorm();
		$this->gorm = &$gorm->orm;
	}

	public function getLastVersionNumber() {

	}

	/**
	 * @public
	 * Returns a Version List of "Stundenplan" as Array( GenericDomainObject, ... )
	 *
	 * @param GenericCriterionObject $crit
	 *
	 * @return mixed
	 */
	public function getVersionList( GenericCriterionObject $crit = null ) {
		if ( !$crit ) {
			$crit = new GenericCriterionObject();
			$crit->addOrderIndicator('StundenplanID', 'ASC');
		}

		return $this->gorm->loadObjectListByCriterion('Stundenplan', $crit);
	}

	/*
	 * TODO: Zählt nicht richtig die Objekte
	 */
	/**
	 * @param GenericDomainObject $object
	 *
	 * @return mixed
	 */
	public function getCountOfRelatedObjects( $object ) {
		$crit = new GenericCriterionObject();
		$crit->addRelationIndicator('Stundenplan2Unterricht', $object);
		return $this->gorm->loadObjectCount('Stundenplan', $crit);
	}

}

?>
<?php
/**
 * @author Steven Tappert
 * @link   admin@steven-tappert.de
 **/

class stundenplan_taglib_initGorm extends Document {

	public $orm;

	public function __construct() {
		$ormFact   = &$this->getServiceObject('modules::genericormapper::data', 'GenericORMapperFactory');
		$this->orm = $ormFact->getGenericORMapper("sites::esobase", "esobase", "MySQLi");
		return $this->orm;
	}

}

?>
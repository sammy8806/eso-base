<?php

import("sites::esobase::sites::pres::taglib", "stundenplan_taglib_target");

/**
 *
 */
class weektableException extends Exception {

}

/**
 *
 */
class weektable extends stundenplan {

	/**
	 * @var #M#M#C\weektable.getServiceObject.getGenericORMapper|?
	 */
	// private $orm;

	/**
	 * @public
	 *
	 * @author Steven Tappert
	 * @version
	 *         Version 0.1, 31.01.2012
	 */
	public function __construct() {
		// Factory im relevanten Service-Mode erstellen
		// $ormFact = &$this->getServiceObject('modules::genericormapper::data', 'GenericORMapperFactory');

		// Mapper von der Factory beziehen
		// $this->orm = &$ormFact->getGenericORMapper("sites::esobase", "esobase", "MySQLi");
	}

	/**
	 *
	 */
	public function transformContent() {
        $HHM = $this->getServiceObject('extensions::htmlheader::biz','HtmlHeaderManager');
        $HHM->addNode(new SimpleTitleNode(ucfirst(stundenplan_taglib_target::getTarget()) . ': ' . stundenplan_taglib_target::getTargetValue()." "));
		$this->setPlaceHolder('targetName', strtoupper(stundenplan_taglib_target::getTargetValue()));
	}

}

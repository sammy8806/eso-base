<?php
/**
 * @author Steven Tappert
 * @link   admin@steven-tappert.de
 *
 **/

import("sites::esobase::sites::pres::taglib", "html_taglib_fallbackimport");

/**
 * @class
 */
class html_taglib_actionImport extends html_taglib_fallbackimport {

	public function __construct()
	{
		parent::__construct();
	}

	public function onParseTime()
	{
		$action = RequestHandler::getValue('action', $this->getAttribute('defaultAction'));
		$rootNamespace = $this->getAttribute('namespace');

		$this->setAttribute('namespace', $rootNamespace);
		$this->setAttribute('template', $action);

		parent::onParseTime();
	}

}

?>
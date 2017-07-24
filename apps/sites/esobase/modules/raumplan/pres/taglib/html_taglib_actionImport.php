<?php
/**
 * @author Steven Tappert
 * @link   admin@steven-tappert.de
 *
 **/

/**
 * @class
 */
class html_taglib_actionImport extends core_taglib_importdesign {

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
<?php

import('tools::request', 'RequestHandler');

/**
 * @author Steven Tappert
 * @link   admin@steven-tappert.de
 **/
class stundenplanverwaltung extends base_controller {

	private $biz;

	public function __construct() {
		$this->biz = &$this->getServiceObject('sites::esobase-admin::modules::stundenplan::biz::documentcontroller', 'stundenplanverwaltung_biz');
	}

	/**
	 * @return string
	 */
	protected function getVersionList() {

		$order = strtoupper(htmlspecialchars(RequestHandler::getValue('order', 'asc')));

		$crit = new GenericCriterionObject();
		$crit->addOrderIndicator('StundenplanID', $order);

		$list = $this->biz->getVersionList($crit);

		$str = '';

		for ( $i = 0; $i < count($list); $i++ ) {
			$id   = $list[$i]->getObjectId();
			$desc = $list[$i]->getProperty('Kommentar');
			$count = $this->biz->getCountOfRelatedObjects($list[$i]);
			$created = $list[$i]->getProperty('CreationTimestamp');

			$lineTpl = &$this->getTemplate('VersionList');
			$lineTpl->setPlaceHolder('version', $id);
			$lineTpl->setPlaceHolder('description', $desc);
			$lineTpl->setPlaceHolder('created', $created);
			$lineTpl->setPlaceHolder('dataCount', $count);
			$str .= $lineTpl->transformTemplate();
		}

		$boxTpl = &$this->getTemplate('VersionBox');
		$boxTpl->setPlaceHolder('VersionLines', $str);
		return $boxTpl->transformTemplate();

		// return $str;
	}

	/**
	 * @public
	 */
	public function transformContent() {
		$this->setPlaceHolder('VersionList', $this->getVersionList());

		$form = &$this->getForm('Stundenplan_addVersion');

		$form->setPlaceHolder('newVersion', 10);
		$form->transformOnPlace();

	}

}

?>
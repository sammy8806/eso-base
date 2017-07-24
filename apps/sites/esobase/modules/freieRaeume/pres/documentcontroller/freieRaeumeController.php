<?php
/**
 * User: Steven
 * Date: 09.04.12
 * Time: 00:13
 */

import('sites::esobase::sites::pres::controller', 'formStatic');
import("sites::esobase::modules::raumplan::biz::controller", "DateTimeManager");
// import('sites::esobase::modules::freieRaeume::biz::documentcontroller', 'freieRaeumeBizController');

class freieRaeumeController extends base_controller {

	private $config;
	private $biz;
	protected $day;
	protected $hour;

	public function __construct() {
		$this->config = $this->getConfiguration('sites::esobase', 'config.xml');
		$this->biz    = &$this->getServiceObject('sites::esobase::modules::freieRaeume::biz::documentcontroller',
		                                         'freieRaeumeBizController');
	}

	public function fillSelect_hour($selectName, &$form) {
		$startHour = (int)$this->config->getSection('GlobalSettings')->getValue('startHour');
		$maxHours  = (int)$this->config->getSection('GlobalSettings')->getValue('maxHours');

		for ($i = $startHour; $i <= $maxHours; $i++) {
			formStatic::addOptionToFormElement($form, $selectName, array(
			                                                            'view'  => $i . '. Stunde',
			                                                            'value' => $i
			                                                       ));
		}
	}

	public function fillSelect_day($selectName, &$form) {
		$startHour = (int)$this->config->getSection('GlobalSettings')->getValue('startDay');
		$maxHours  = (int)$this->config->getSection('GlobalSettings')->getValue('maxDays');

		for ($i = $startHour; $i <= $maxHours; $i++) {
			formStatic::addOptionToFormElement($form, $selectName, array(
			                                                            'view'  => dateTimeManager::getDayName($i),
			                                                            'value' => $i
			                                                       ));
		}
	}

	private function fillSelectOptions($selectName, &$form, $callback = false) {
		if (!$callback) {
			$callback = 'fillSelect_' . $selectName;
		}
		return $this->$callback($selectName, $form);
	}

	public function fillTemplate($tpl, &$form) {
		$callName = 'fillTpl_' . $tpl;
		return $this->$callName($form);
	}

	public function transformContent() {
		$form = &$this->getForm("freieRaeume");

		$this->fillSelectOptions('hour', $form);
		$this->fillSelectOptions('day', $form);

		if ($form->isSent()) {
			$this->fillTemplate('title', $form);
			$this->fillTemplate('room', $form);
			// $this->transformLines();
		} else {
			$form->transformOnPlace();
		}
	}

	private function fillTpl_title(&$form) {
		$this->hour = $form->getFormElementByName('hour')->getValue()->getValue();
		$this->day  = $form->getFormElementByName('day')->getValue()->getValue();
		$tpl        = $this->getTemplate('title');
		$tpl->setPlaceHolder('hour', $this->hour);
		$tpl->setPlaceHolder('day', dateTimeManager::getDayName($this->day));
		$tpl->transformOnPlace();
	}

	private function fillTpl_room(&$form) {
		$freeRooms = $this->biz->getFreeRooms($this->day, $this->hour);
		for ($i = 0; $i <= count($freeRooms)-1; $i++) {
			$tpl = $this->getTemplate('room');
			$tpl->setPlaceHolder('room', $freeRooms[$i]);
			$this->__Content .= $tpl->transformTemplate();
		}
	}

}

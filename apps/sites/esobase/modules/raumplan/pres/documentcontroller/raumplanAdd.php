<?php

import("sites::esobase::modules::stundenplan::pres::taglib", "stundenplan_taglib_initGorm");
import("sites::esobase::sites::pres::controller", "selectGenerator");
import("sites::esobase::sites::pres::controller", "formStatic");
import("sites::esobase::modules::raumplan::biz::controller", "DateTimeManager");

/**
 * @author Steven Tappert
 * @link   admin@steven-tappert.de
 **/
class raumplanAdd extends base_controller {

	private $gorm;
	private $config;
	private $biz;

	public function __construct() {
		$gorm         = new stundenplan_taglib_initGorm();
		$this->gorm   = $gorm->orm;
		$this->config = $this->getConfiguration('sites::esobase', 'config.xml');
		$this->biz    = &$this->getServiceObject('sites::esobase::modules::raumplan::biz::controller', 'RaumplanBiz');
	}

	/**
	 * @param $form
	 */
	protected function generateForm($form) {
		$select = new selectGenerator($this->gorm);
		$select->modifyForm($form, array(
		                                'class'   => 'class',
		                                'teacher' => 'teacher',
		                                'room'    => 'room',
		                                'subject'    => 'subject'
		                           ));

		$startHour = $this->config->getSection('GlobalSettings')->getValue('startHour');
		$endHour   = $this->config->getSection('GlobalSettings')->getValue('maxHours');
		$hours     = array();

		for ($i = $startHour; $i <= $endHour; $i++) {
			$hours[] = array(
				'value' => $i,
				'view'  => $i
			);
		}

		formStatic::addOptionToFormElement($form, "fromHour", $hours);
		formStatic::addOptionToFormElement($form, "toHour", $hours);
	}

	/**
	 *
	 */
	public function getFormContent() {
		$out = 'Formular :' . "\n<br />";
		/*$out .= 'Lehrer: ' . formStatic::getFormElementContent("teacher") . "\n<br />";
		   $out .= 'Klasse: ' . formStatic::getFormElementContent('class') . "\n<br />";
		   $out .= 'Zweck: ' . formStatic::getFormElementContent('purpose') . "\n<br />";
		   $out .= 'Raum: ' . formStatic::getFormElementContent('room') . "\n<br />";
		   */

		//$entry = new GenericDomainObject('Raumplan');
		//$entry->setProperty()
	}

	public function transformContent() {
		$HHM = $this->getServiceObject('extensions::htmlheader::biz','HtmlHeaderManager');
        $HHM->addNode(new SimpleTitleNode("Raumplanung eintragen "));

		$form = &$this->getForm('raumplanADD');
		$this->generateForm($form);

		if ($form->isSent()) {
			$toHour = &$form->getFormElementByName('toHour');
			// $toHour->markAsInvalid();
			// $toHour->appendCssClass(AbstractFormValidator::$DEFAULT_MARKER_CLASS);

			$fields = array(
				'teacher', 'class', 'subject', 'room', 'fromDate', 'toDate', 'fromHour', 'toHour', 'rythm' //'purpose',
			);
			// $fields          = formStatic::stripArrayToAllowedKeys($_POST, $allowedFields);

			$field = array();
			foreach ($fields as $fieldName) {
				$item = &$form->getFormElementByName($fieldName);
				if (is_scalar($item->getValue())) {
					$field[$fieldName] = $item->getValue();
				} elseif (is_scalar($item->getValue()->getValue())) {
					$field[$fieldName] = $item->getValue()->getValue();
				}
			}

			try {
				$dates = DateTimeManager::ConvertIntervalToSingle($field['fromDate'], $field['toDate'],
				                                                  $field['rythm']);
			} catch (DateTimeException $de) {
				$dateC = &$form->getFormElementByName('toDate');
				$dateC->markAsInvalid();
				$dateC->appendCssClass(AbstractFormValidator::$DEFAULT_MARKER_CLASS);
				$form->transformOnPlace();
				return NULL;
			}

			if ((int)$field['toHour'] < (int)$field['fromHour']) {
				$hour = &$form->getFormElementByName('toHour');
				$hour->markAsInvalid();
				$hour->appendCssClass(AbstractFormValidator::$DEFAULT_MARKER_CLASS);
				$form->transformOnPlace();
				return NULL;
			}

			if(empty($field['toDate']) && $field['rythm'] != "0") {
				$hour = &$form->getFormElementByName('toDate');
				$hour->markAsInvalid();
				$hour->appendCssClass(AbstractFormValidator::$DEFAULT_MARKER_CLASS);
				$form->transformOnPlace();
				return NULL;
			}
		}

		if ($form->isSent() && $form->isValid()) {

			// $this->getFormContent();

			ob_start();

			if ($dates) {
				$buchungen = $this->biz->genRaumplanObjects($dates, $field);
				var_dump($buchungen);
				$this->biz->saveBookings($buchungen);
			}

			$this->__Content .= ob_get_clean();
		} else {
			$form->transformOnPlace();
		}
	}

}

?>
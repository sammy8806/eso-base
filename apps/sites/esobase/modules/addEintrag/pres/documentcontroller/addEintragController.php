<?php
/**
 * User: Steven
 * Date: 25.12.11
 * Time: 22:02
 */
import('modules::genericormapper::data::tools', 'GenericORMapperSetup');
import("tools::request", "RequestHandler");

class addEintragController extends base_controller {

	private $orm;

	public function __construct() {
		// Factory im relevanten Service-Mode erstellen
		$ormFact = &$this->getServiceObject('modules::genericormapper::data', 'GenericORMapperFactory');

		// Mapper von der Factory beziehen
		$this->orm = &$ormFact->getGenericORMapper("sites::esobase", "esobase", "MySQLi");
	}

	private function submitClass($registerForm) {
		// Die einzelnen Formular Elemente beziehen
		$klasseElement = $registerForm->getFormElementByName('Klasse');
		$lehrerElement = $registerForm->getFormElementByName('Lehrer');
		$raumElement   = $registerForm->getFormElementByName('Raum');

		if (!empty($klasseElement) && !empty($lehrerElement) && !empty($raumElement)) {
			$Crit = new GenericCriterionObject();
			$Crit->addPropertyIndicator('Kuerzel', $klasseElement->getAttribute('value'));
			$User = $this->orm->loadObjectByCriterion('Klasse', $Crit);

			if ($User === NULL) {
				$userObject = new GenericDomainObject('Klasse');
				$userObject->setProperty('Kuerzel', $klasseElement->getAttribute('value'));
				$userObject->setProperty('Lehrer', $lehrerElement->getAttribute('value'));
				$userObject->setProperty('Raum', $raumElement->getAttribute('value'));

				// Benutzer speichern
				$this->orm->saveObject($userObject);
			}
		}
	}

	public function transformContent() {
		$registerFormKlasse = $this->getForm('klasse');
		if ($registerFormKlasse->isSent()) {
			$this->submitClass($registerFormKlasse);
		}
		$registerFormKlasse->transformOnPlace();
	}
}

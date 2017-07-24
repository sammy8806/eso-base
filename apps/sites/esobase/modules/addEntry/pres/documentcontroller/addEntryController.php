<?php
/**
 * User: Steven
 * Date: 25.12.11
 * Time: 22:02
 */
import('modules::genericormapper::data::tools', 'GenericORMapperSetup');
import("tools::request", "RequestHandler");

class addEntryController extends base_controller
{
    private $orm;

    public function __construct()
    {
        // Factory im relevanten Service-Mode erstellen
        $ormFact = &$this->getServiceObject('modules::genericormapper::data', 'GenericORMapperFactory');

        // Mapper von der Factory beziehen
        $this->orm = &$ormFact->getGenericORMapper("sites::esobase", "esobase", "MySQLi");
    }

    private function submitClass($registerForm)
    {
        // Die einzelnen Formular Elemente beziehen
        $klasseElement = $registerForm->getFormElementByName('Klasse');
        $lehrerElement = $registerForm->getFormElementByName('Lehrer');
        $raumElement = $registerForm->getFormElementByName('Raum');

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

    private function submitRoom($registerForm)
    {
        // Die einzelnen Formular Elemente beziehen
        $raumElement = $registerForm->getFormElementByName('Raum');
        $kapazitaetElement = $registerForm->getFormElementByName('Kapaztaet');

        if (!empty($raumElement) && !empty($kapazitaetElement)) {
            $Crit = new GenericCriterionObject();
            $Crit->addPropertyIndicator('Kuerzel', $raumElement->getAttribute('value'));
            $User = $this->orm->loadObjectByCriterion('Raum', $Crit);

            if ($User === NULL) {
                $userObject = new GenericDomainObject('Raum');
                $userObject->setProperty('Kuerzel', $raumElement->getAttribute('value'));
                $userObject->setProperty('Kapazitaet', $kapazitaetElement->getAttribute('value'));

                // Benutzer speichern
                $this->orm->saveObject($userObject);
            }
        }
    }

    private function submitTeacher($registerForm)
    {
        // Die einzelnen Formular Elemente beziehen
        $lehrerElement = $registerForm->getFormElementByName('Lehrer');
        $nameElement = $registerForm->getFormElementByName('Name');

        if (!empty($lehrerElement) && !empty($nameElement)) {
            $Crit = new GenericCriterionObject();
            $Crit->addPropertyIndicator('Kuerzel', $lehrerElement->getAttribute('value'));
            $User = $this->orm->loadObjectByCriterion('Lehrer', $Crit);

            if ($User === NULL) {
                $userObject = new GenericDomainObject('Lehrer');
                $userObject->setProperty('Kuerzel', $lehrerElement->getAttribute('value'));
                $userObject->setProperty('Name', $nameElement->getAttribute('value'));

                // Benutzer speichern
                $this->orm->saveObject($userObject);
            }
        }
    }

    public function transformContent()
    {
        $type = RequestHandler::getValue("action");

        switch ($type) {
            case 'klasse':
                $registerFormKlasse = $this->getForm('klasse');
                if ($registerFormKlasse->isSent())
                    $this->submitClass($registerFormKlasse);
                $registerFormKlasse->transformOnPlace();
                break;

            case 'raum':
                $registerFormRaum = $this->getForm('raum');
                if ($registerFormRaum->isSent())
                    $this->submitRoom($registerFormRaum);
                $registerFormRaum->transformOnPlace();
                break;

            case 'lehrer':
                $registerFormLehrer = $this->getForm('lehrer');
                if ($registerFormLehrer->isSent())
                    $this->submitTeacher($registerFormLehrer);
                $registerFormLehrer->transformOnPlace();
                break;

            default:
                break;
        }
    }
}

<?php
/**
 * @author Florian Tenhaken
 * @link   admin@white-it.net
 *
 **/

import("sites::esobase::modules::raumplan::biz::controller", "RaumplanObject");
import("sites::esobase::modules::stundenplan::pres::taglib", "stundenplan_taglib_initGorm");

/**
 * @class
 *
 * Kleine Beschreibung :-P
 *
 * @package esobase.modules.raumplan
 *
 */
class RaumplanBiz extends APFObject {

	private $gorm;
	private $config;

	public function __construct() {
		$gorm         = new stundenplan_taglib_initGorm();
		$this->gorm   = $gorm->orm;
		$this->config = $this->getConfiguration('sites::esobase', 'config.xml');
	}

	/**
	 * @public
	 *
	 * @param array $dates
	 * @param array $field
	 *
	 * @return array
	 */
	public function genRaumplanObjects(array $dates, array $field) {
		$termine = array();
		$purpose = !empty($field['purpose']) ? $field['purpose'] : $field['class'];
		foreach ($dates as $dateKey => $date) {
			for ($i = $field['fromHour']; $i <= $field['toHour']; $i++) {
				$termine[$dateKey][$i] = new RaumplanObject();
				$termine[$dateKey][$i]->setClass($purpose);
				$termine[$dateKey][$i]->setDay($date->format("N"));
				$termine[$dateKey][$i]->setHour($i);
				$termine[$dateKey][$i]->setRoom($field['room']);
				$termine[$dateKey][$i]->setSubject($field['subject']);
				$termine[$dateKey][$i]->setTeacher($field['teacher']);
			}
		}

		return $termine;
	}

	public function saveBookings(array $bookings) {
		$this->generateNewRaumplanung($bookings);
	}

	public function saveBooking(RaumplanObject $booking) {
		$book = new GenericDomainObject('Raumbuchung');
		$book->setProperty('Tag', $booking->getDay());
		$book->setProperty('Stunde', $booking->getHour());

		// TODO: Einzelne Objekte müssen seperat gespeichert werden und erst DANN verknüpft!

		$rel = array();
		if($booking->getRoom() != "")
			$rel['Raum'] = $this->generateObject('Raum', array('Kuerzel' => $booking->getRoom()));

		if($booking->getClass() != "")
			$rel['Klasse'] = $this->generateObject('Klasse', array('Kuerzel' => $booking->getClass()));

		if($booking->getSubject() != "")
			$rel['Fach'] = $this->generateObject('Fach', array('Kuerzel' => $booking->getSubject()));

		if($booking->getTeacher() != "")
			$rel['Lehrer'] = $this->generateObject('Lehrer', array('Kuerzel' => $booking->getTeacher()));

		foreach($rel as $relationName => $relation) {
			$book->addRelatedObject('Raumbuchung2'.$relationName, $relation);
		}

		return $book;
	}

	private function generateNewRaumplanung($bookings) {
		// Zerteilt die Objektliste (array)
		$booking = new GenericDomainObject('Raumplanung');
		$booking->setProperty('Zweck', $bookings[0][1]->getPurpose());
		$booking->setProperty('Kommentar', $bookings[0][1]->getDiscription());

		foreach ($bookings as $bookingPerDay) {
			// Einzelne Tage
			foreach ($bookingPerDay as $bookingsPerHour) {
				// Einzelne Stunden
				$obj = $this->saveBooking($bookingsPerHour);
				$booking->addRelatedObject('Raumplanung2Raumbuchung', $obj);
			}
		}

		$this->gorm->saveObject($booking);
	}

	/**
	 * @param string $objectName Ex: Klasse
	 * @param array  $properties Ex: array( 'Klasse' => 'B7VTI2A' )
	 *
	 * @return GenericDomainObject
	 */
	private function generateObject($objectName, array $properties) {
		$crit = new GenericCriterionObject();
		foreach ($properties as $propertyKey => $propertyValue)
		{
			$crit->addPropertyIndicator($propertyKey, $propertyValue);
		}
		return $this->gorm->loadObjectByCriterion($objectName, $crit);
	}

}

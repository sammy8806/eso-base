<?php
/**
 * @author Steven Tappert
 * @link   admin@steven-tappert.de
 *
 **/
class RaumplanObject {

	protected $teacher;
	protected $class;
	protected $room;
	protected $subject;
	protected $hour;
	protected $day;
	protected $discription;
	protected $purpose;

	public function setClass($class) {
		$this->class = $class;
	}

	public function setDay($day) {
		$this->day = $day;
	}

	public function setHour($hour) {
		$this->hour = $hour;
	}

	public function setRoom($room) {
		$this->room = $room;
	}

	public function setSubject($subject) {
		$this->subject = $subject;
	}

	public function setTeacher($teacher) {
		$this->teacher = $teacher;
	}

	public function setDiscription($discription) {
		$this->discription = $discription;
	}

	public function setPurpose($purpose) {
		$this->purpose = $purpose;
	}

	public function getClass() {
		return $this->class;
	}

	public function getDay() {
		return $this->day;
	}

	public function getHour() {
		return $this->hour;
	}

	public function getRoom() {
		return $this->room;
	}

	public function getSubject() {
		return $this->subject;
	}

	public function getTeacher() {
		return $this->teacher;
	}

	public function getDiscription() {
		return $this->discription;
	}

	public function getPurpose() {
		return $this->purpose;
	}
}

?>
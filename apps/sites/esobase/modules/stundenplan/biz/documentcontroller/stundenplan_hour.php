<?php
/**
 * @author Steven Tappert
 * @link   admin@steven-tappert.de
 **/

class stundenplan_hour {

	protected $teacher;
	protected $class;
	protected $room;
	protected $subject;
	protected $hourType;

	private $data;

	public function __construct( &$data, $hourType = 'unterricht' ) {
		if ( !$data ) {
			throw new stundenplanException( 'Keine Stundenobjekt gefunden!' );
		} else {
			$this->data = $data;
		}

		if ( $hourType ) {
			throw new stundenplanException( 'Stundentyp nicht definiert!' );
		} else {
			$this->hourType = $hourType;
		}

		$this->readData();

		$this->mapRelation('lehrer', 'teacher');
		$this->mapRelation('klasse', 'class');
		$this->mapRelation('raum', 'room');
		$this->mapRelation('fach', 'subject');
	}

	private function mapRelation( $targetRelation, $relationTarget ) {
		$relation  = $this->hourType . '2' . $targetRelation;
		$relTarget = $this->orm->loadRelatedObjects($this->data, $relation);

		$this->${$relationTarget} = $relTarget;
	}

	public function getClass() {
		return $this->class;
	}

	public function getHourType() {
		return $this->hourType;
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

}

?>
<?php

import('modules::genericormapper::data::tools', 'GenericORMapperSetup');
import("tools::request", "RequestHandler");
import("tools::http", "HeaderManager");
import('tools::filesystem', 'FilesystemManager');
import('core::logging','Logger');

/**
 * @class
 * @extends Exception
 *          The exception to differ a exception from the convertDatabase from the others
 * @author  Steven Tappert
 * @version
 *          Version 0.1, 21.01.2012
 */
class convertException extends Exception {

}

/**
 * @class
 * @extends base_controller
 *          Controller to convert from the old database structure to the new GROM structure
 * @author  Steven Tappert
 * @version
 *          Version 0.1, 21.01.2012
 */
class convertDatabase extends base_controller {

	/**
	 * @private
	 *      Stores the GORM mapper for ESO-Base
	 * @var #M#M#C\convertDatabase.getServiceObject.getGenericORMapper|?
	 */
	private $orm;

	/**
	 * @private
	 *      Contains the database handler to the old database
	 * @var #M#M#C\convertDatabase.getServiceObject.getConnection|?
	 */
	private $tmpDB;

	/**
	 * @var APFObject
	 */
	private $log;

	private $logFile = 'admin_mod_convertDatabase';

	/**
	 * @public
	 *         Init the GORM and the old database
	 * @author Steven Tappert
	 * @version
	 *         Version 0.1, 21.01.2012
	 */
	public function __construct() {
		$ormFact   = &$this->getServiceObject('modules::genericormapper::data', 'GenericORMapperFactory');
		$this->orm = &$ormFact->getGenericORMapper("sites::esobase", "esobase", "MySQLi");

		$cM          = &$this->getServiceObject('core::database', 'ConnectionManager');
		$this->tmpDB = &$cM->getConnection('MySQLi_TMP');

		$this->log = &Singleton::getInstance('Logger');
		$this->logFile = 'admin_convertDatabase';
	}

	/**
	 * @private
	 *         Read the $target table from the old database
	 *
	 * @param        $dbObj  The database handler for the old database
	 * @param string $target The name of the old table
	 * @param bool   $log
	 *
	 * @return mixed
	 * @author Steven Tappert
	 * @version
	 *         Version 0.1, 21.01.2012
	 */
	private function readDatabaseResult( $dbObj, $target, $log = false ) {
		return $dbObj->executeTextStatement("SELECT * FROM `$target`", $log);
	}

	/**
	 * @private
	 *         Coordinates the data mapping from GORM object to the old database
	 *
	 * @param string $target The target GORM object name
	 * @param        $data   The row data from the old database
	 *
	 * @return array
	 * @throws convertException
	 * @author Steven Tappert
	 * @version
	 *         Version 0.1, 21.01.2012
	 */
	private function convertProperties( $target, $data ) {
		$out = array();

		switch ($target) {
			case 'fachdaten':
				$out['Kuerzel'] = $data['f_kuerzel'];
				$out['Name']    = $data['f_name'];
				break;

			case 'klassendaten':
				$out['Kuerzel'] = $data['k_klassenkuerzel'];
				$out['Lehrer']  = $data['k_lehrerkuerzel'];
				$out['Raum']    = $data['k_raumkuerzel'];
				break;

			case 'lehrerdaten':
				$out['Kuerzel'] = $data['l_kuerzel'];
				$out['Name']    = $data['l_name'];
				break;

			case 'raumdaten':
				$out['Kuerzel']    = $data['r_kuerzel'];
				$out['Kapazitaet'] = (int) $data['r_kapazitaet'];
				break;

			case 'unterrichtsverteilung':
				$out['Tag']    = (int) $data['u_tag'];
				$out['Stunde'] = (int) $data['u_stunde'];
				break;

			default:
				throw new convertException( 'No mapping for $target has been found', E_USER_ERROR );
				break;
		}

		return $out;
	}

	/**
	 * @private
	 *         Create a GenericCriterionObject dependend on $criterionName => $criterionVal
	 *
	 * @param string $criterionName
	 * @param string $criterionVal
	 *
	 * @return GenericCriterionObject
	 * @author Steven Tappert
	 * @version
	 *         Version 0.1, 21.01.2012
	 */
	private function createCriterion( $criterionName, $criterionVal ) {
		$criterion = new GenericCriterionObject();
		$criterion->addPropertyIndicator($criterionName, $criterionVal);
		return $criterion;
	}

	/**
	 * @private
	 *         Load the Object $newTarget dependend on $criterionProperties
	 *
	 * @param array       $criterionProperties
	 * @param string      $newTarget
	 *
	 * @return mixed
	 * @author Steven Tappert
	 * @version
	 *         Version 0.1, 21.01.2012
	 */
	private function addRelationToObject( array $criterionProperties, $newTarget ) {
		//	return $this->orm->loadObjectByID('Fach', 1);
		return $this->orm->loadObjectByCriterion($newTarget, $this->createCriterion($criterionProperties['name'], $criterionProperties['value']));
	}

	/**
	 * @private
	 *         Check the value of the old one if its empty
	 *         If true ignore the Relation
	 *
	 * @param string              $relationName        Name of the defined relation to use
	 * @param string              $objectName          Name of the target GORM object
	 * @param array               $criterionProperties Contains 'name' (Name of the attribute in the GORM object) and 'value' of the old database
	 * @param GenericDomainObject $orm
	 *
	 * @author Steven Tappert
	 * @version
	 *         Version 0.1, 22.01.2012
	 */
	private function checkAndRelateObject( $relationName, $objectName, array $criterionProperties, GenericDomainObject $orm ) {
		if ( !( empty( $criterionProperties['value'] ) || $criterionProperties['value'] == "" ||
		        $criterionProperties['value'] == " " || $criterionProperties['value'] == NULL )
		) {
			$obj = $this->addRelationToObject($criterionProperties, $objectName);
			if ( !$obj ) {
				return;
			}
			$orm->addRelatedObject($relationName, $obj);
		}
	}

	/**
	 * @private
	 *         Convert the old database
	 *
	 * @param string $target           Name of the old table
	 * @param string $newTarget        Name of the new GORM object
	 * @param        $oldDB            The ConnectionManager-Object of the old database
	 *
	 * @author Steven Tappert
	 * @version
	 *         Version 0.1, 21.01.2012
	 */
	private function convertDB( $target, $newTarget, $oldDB ) {
		$result = $this->readDatabaseResult($oldDB, $target, true);

		while ( $data = $oldDB->fetchData($result) ) {
			$orm = new GenericDomainObject( $newTarget );
			$orm->setProperties($this->convertProperties($target, $data));

			if ( $target == "unterrichtsverteilung" ) {
				$this->checkAndRelateObject("Unterricht2Fach", "Fach", array(
				                                                            'name'   => 'Kuerzel',
				                                                            'value'  => $data['u_fach']
				                                                       ), $orm);
				$this->checkAndRelateObject("Unterricht2Klasse", "Klasse", array(
				                                                                'name'   => 'Kuerzel',
				                                                                'value'  => $data['u_klasse']
				                                                           ), $orm);
				$this->checkAndRelateObject("Unterricht2Lehrer", "Lehrer", array(
				                                                                'name'   => 'Kuerzel',
				                                                                'value'  => $data['u_lehrerkuerzel']
				                                                           ), $orm);
				$this->checkAndRelateObject("Unterricht2Raum", "Raum", array(
				                                                            'name'   => 'Kuerzel',
				                                                            'value'  => $data['u_raumkuerzel']
				                                                       ), $orm);
			}

			$this->orm->saveObject($orm);
		}

	}

	public function transformDatabase() {
		$std['start'] = RequestHandler::getValue("start");
		$std['end']   = RequestHandler::getValue("end");

		if ( empty( $std['start'] ) || empty( $std['end'] ) ) {
			throw new convertException( "URL Parameter start, end not set", E_USER_ERROR );
		}

		$names = array(
			'Fach'       => 'fachdaten',
			'Lehrer'     => 'lehrerdaten',
			'Klasse'     => 'klassendaten',
			'Raum'       => 'raumdaten',
			'Unterricht' => 'unterrichtsverteilung'
		);

		// $names = array('Unterricht' => 'unterrichtsverteilung');

		foreach ( $names as $new => $old ) {
			$this->convertDB($old, $new, $this->tmpDB);
		}

		HeaderManager::forward('/');
	}

	/**
	 * @public
	 *         Function is called when the controller is load
	 *         Change Version 0.2: Moved to transformDatabase() in case of cron ability
	 * @author Steven Tappert
	 * @version
	 *         Version 0.1 21.01.2012<br />
	 *         Version 0.2 22.03.2012
	 * @return string
	 */
	public function transformContent() {
		$checkForm = &$this->getForm('checkForm');

		$folder = Registry::retrieve('apf::core', 'CronDir');
		$file   = $folder . '/' . 'convertDatabase.do';

		if ( $checkForm->isSent() ) {
			if ( @filesize($file) > 1 ) {
				$this->__Content = 'Ein Konvertierungsvorgang wurde bereits gesetzt!';
				return;
			}

			$fh = fopen($file, 'c+');
			if ( !$fh ) {
				FilesystemManager::createFolder($folder);
				$fh = fopen($file, 'c+');
			}

			fwrite($fh,
				"DO OPEN\nhttp://" . Registry::retrieve('apf::core', 'BaseURL') . "/" . "admin?page=" . __CLASS__ .
				"&engine=cron");
			fclose($fh);

			$this->log->logEntry($this->logFile,'Kovertierung wurde entgegengenommen');
			$this->__Content = 'Ein Kovertierungsauftrag wurde entgegengenommen';
			return;

		} else {
			if ( RequestHandler::getValue('engine') == 'cron' ) {
				$this->log->logEntry($this->logFile,'Kovertierung gestartet');
				$this->transformDatabase();
				FilesystemManager::removeFile($folder . '/' . $file);
				$this->log->logEntry($this->logFile,'Kovertierung abgeschlossen');
				$this->__Content = 'Die Konvertierung wurde abgeschlossen!';
			}
			$checkForm->transformOnPlace();
		}
	}
}

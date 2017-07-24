<?php
/**
 * User: Steven
 * Date: 09.04.12
 * Time: 01:09
 */

import('modules::genericormapper::data::tools', 'GenericORMapperSetup');

class freieRaeumeBizController extends APFObject {

	protected $orm;

	public function __construct() {
		$ormFact   = &$this->getServiceObject('modules::genericormapper::data', 'GenericORMapperFactory');
		$this->orm = &$ormFact->getGenericORMapper("sites::esobase", "esobase", "MySQLi");
	}

	public function getFreeRooms($day, $hour) {
		// $tmpObj = $this->orm->loadObjectListByCriterion('Raum', $crit);
		/*
		$sql = "SELECT *
FROM `ent_raum`
JOIN `ass_unterricht2raum` ON `ass_unterricht2raum`.`Target_RaumID` = `ent_raum`.`RaumID`
JOIN `ent_unterricht` ON `ent_unterricht`.`UnterrichtID` = `ass_unterricht2raum`.`Source_UnterrichtID`
RIGHT OUTER JOIN `ass_unterricht2fach` ON `ass_unterricht2fach`.`Source_UnterrichtID` = `ent_unterricht`.`UnterrichtID`
WHERE `ent_unterricht`.`Tag` = '".htmlentities($day)."'
AND `ent_unterricht`.`Stunde` = '".htmlentities($hour)."'
AND `ass_unterricht2fach`.`Target_FachID` IS NULL
";

		$sql = "SELECT *
		FROM `ent_raum`
		WHERE `RaumID` NOT IN
		(
		SELECT RaumID
		FROM `ent_raum`
		JOIN `ass_unterricht2raum` ON `ass_unterricht2raum`.`Target_RaumID` = `ent_raum`.`RaumID`
		JOIN `ent_unterricht` ON `ent_unterricht`.`UnterrichtID` = `ass_unterricht2raum`.`Source_UnterrichtID`
		RIGHT JOIN `ass_unterricht2fach` ON `ass_unterricht2fach`.`Source_UnterrichtID` = `ent_unterricht`.`UnterrichtID`
		RIGHT JOIN `ass_unterricht2lehrer` ON `ass_unterricht2lehrer`.`Source_UnterrichtID` = `ent_unterricht`.`UnterrichtID`
		RIGHT JOIN `ass_unterricht2klasse` ON `ass_unterricht2klasse`.`Source_UnterrichtID` = `ent_unterricht`.`UnterrichtID`
		WHERE `ent_unterricht`.`Tag` = '".htmlentities($day)."'
		AND `ent_unterricht`.`Stunde` = '".htmlentities($hour)."'
		)";
*/
		$sql = "SELECT * FROM `ent_raum` WHERE `ent_raum`.`RaumID` NOT IN (
		SELECT DISTINCT `Target_RaumID` FROM `ass_unterricht2raum`
		JOIN `ent_unterricht` ON `ent_unterricht`.`UnterrichtID` = `ass_unterricht2raum`.`Source_UnterrichtID`
		WHERE `ent_unterricht`.`Tag` = '$day'
		AND `ent_unterricht`.`Stunde` = '$hour'
		)";

		$obj = $this->orm->loadObjectListByTextStatement('Raum', $sql);

		$out = array();

		for ($i = 0; $i <= count($obj)-1; $i++) {
			if($obj[$i])
				$out[] = $obj[$i]->getProperty('Kuerzel');
		}

		return $out;
	}

	public function transformContent() {

	}
}

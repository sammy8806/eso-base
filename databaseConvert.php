<?php
// Einbinden des Pagecontrollers,
// dieser ist das Kernstück des APFs
// und wird eigentlich immer benötigt
require_once 'apps/core/pagecontroller/pagecontroller.php';

// Importieren des Frontcontrollers
// Wird benötigt um einen Request abzuarbeiten
// und Ausgabe an den Client zu senden
import('core::frontcontroller', 'Frontcontroller');

//Nun holen wir eine Instanz des Frontcontrollers
$fC = Singleton::getInstance('Frontcontroller');

//Context setzen (wird später noch nützlich sein)
$fC->setContext('esobase');

if ( PHP_SAPI != "cli" && !( isset( $_SERVER['argc'] ) && $_SERVER['argc'] >= 1 ) ) {
	throw new Exception( 'Only runable from CLI!', E_ERROR);
}

//Request starten und Ausgabe an Client senden
echo $fC->start('sites::esobase::modules:convertDatabase::pres::templates', 'convertDatabase');
?>
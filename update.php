<?php
// Pagecontroller einbinden
require_once 'apps/core/pagecontroller/pagecontroller.php';

// GenericORMapperUpdate importieren
import('modules::genericormapper::data::tools', 'GenericORMapperUpdate');

// Updatetool erstellen
$update = new GenericORMapperUpdate();

// WICHTIG! Wir müssen den Context wieder setzen, da
// wir ja nicht von der index.php aus arbeiten und
// das Objekt erstellen haben
$update->setContext('esobase');

// adapt storage engine (default is MyISAM)
$update->setStorageEngine('INNODB');

// Wir werden das Update nicht direkt ausführen
// sondern uns die SQL Statements erstmal anzeigen
// lassen, dafür sorgt der letzte Parameter
$update->updateDatabase('sites', 'esobase', 'MySQLi', false);
?>
<?php

error_reporting(E_ALL);

// Einbinden des Pagecontrollers,
// dieser ist das Kernstück des APFs
// und wird eigentlich immer benötigt
require_once 'apps/core/pagecontroller/pagecontroller.php';

// Importieren des Frontcontrollers
// Wird benötigt um einen Request abzuarbeiten
// und Ausgabe an den Client zu senden
import('core::frontcontroller', 'Frontcontroller');

import('sites::esobase::sites::pres::filter', 'ScriptletOutputFilter');
OutputFilterChain::getInstance()->appendFilter(new ScriptletOutputFilter());

Registry::register('apf::core', 'URLRewriting', false);

Registry::register('apf::core', 'DebugMode', true);
Registry::register('esobase::error', 'namespace', 'sites::esobase::sites::pres::templates::errors');
Registry::register('esobase::error::404', 'template', '404');

//Nun holen wir eine Instanz des Frontcontrollers
$fC = Singleton::getInstance('Frontcontroller');

//Context setzen (wird später noch nützlich sein)
$fC->setContext('esobase');

Registry::register('apf::core', 'BasePath', getcwd());
Registry::register('apf::core', 'BaseURL', $_SERVER["HTTP_HOST"]);

//Request starten und Ausgabe an Client senden
echo $fC->start('sites::esobase-admin::pres::templates', 'main');
?>
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

// import('sites::esobase::sites::pres::filter', 'MailAddressEncrypter');
// OutputFilterChain::getInstance()->appendFilter(new MailAddressEncrypter());

//Nun holen wir eine Instanz des Frontcontrollers
$fC = Singleton::getInstance('Frontcontroller');


import('core::configuration::provider::xml', 'XmlConfigurationProvider');
ConfigurationManager::registerProvider('xml', new XmlConfigurationProvider());

//$bench = &Singleton::getInstance('BenchmarkTimer');
//$bench->disable();

//Context setzen (wird später noch nützlich sein)
$fC->setContext('esobase');

// Registry::register('apf::core', 'LogDir', './logs/');
Registry::register('apf::core', 'URLRewriting', true);
Registry::register('apf::core', 'DebugMode', true);
Registry::register('apf::core', 'LogDir', '/home/web/domains/steven-tappert.de/subdomains/timetable-apf/logs');
Registry::register('apf::core', 'CronDir', '/home/web/domains/steven-tappert.de/subdomains/timetable-apf/cron');
Registry::register('apf::core', 'BaseURL', $_SERVER['SERVER_NAME']);

Registry::register('apf::tools::html::fallbackimport', 'fallbackNamespace', 'sites::esobase::sites::pres::templates::errors');
Registry::register('apf::tools::html::fallbackimport', 'fallbackTemplate', '404');

// import('core::exceptionhandler', 'ProductionExceptionHandler');
// GlobalExceptionHandler::registerExceptionHandler(new ProductionExceptionHandler( '/' ));

//Request starten und Ausgabe an Client senden
echo $fC->start('sites::esobase::sites::pres::templates', 'main');

if ( isset( $_REQUEST['benchmarkreport'] ) && $_REQUEST['benchmarkreport'] == 'true' ) {
	$t = &Singleton::getInstance('BenchmarkTimer');
	echo $t->createReport();
}
?>
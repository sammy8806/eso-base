<?php
// Request Handler importieren, da wir damit auf die
// Url Parameter zugreifen werden
import('tools::request', 'RequestHandler');
import("sites::esobase::sites::pres::taglib", "html_taglib_fallbackimport");

class html_taglib_content extends html_taglib_fallbackimport {
	public function __construct() {
		// Wichtig, damit eine eindeutige ObjectId gesetzt wird
		// und TagLibs bekannt gemacht werden
		parent::__construct();
	}

	public function onParseTime() {
		// Hier reagieren wir nun auf den Url Parameter page

		// Mit dem RequestHandler holen wir uns den Wert des page
		// Parameters, sollte dieser nicht vorhanden sein, so
		// setzen wir standardmäßig 'home'

		$page = RequestHandler::getValue('page', 'home');

		// Nun setzen wir je nach Inhalt der Variable namespace
		// und template

		/**
		 * rootNS="sites::esobase-admin"
		modNS="sites::esobase-admin::modules"
		TplNS="pres::templates"
		defaultTpl="main"
		 */

		$rootNS     = $this->getAttribute('rootNS', false);
		$modNS      = $this->getAttribute('modNS', false);
		$TplNS      = $this->getAttribute('TplNS', false);
		$defaultTpl = $this->getAttribute('defaultTpl', false);
		/*
		  switch ($page) {
			  case 'home':
				  $toSet['namespace'] = 'sites::esobase::sites::pres::templates';
				  $toSet['template']  = 'home';
				  break;
			  default:
				  $toSet['namespace'] = 'sites::esobase::modules::' . $page . '::pres::templates';
				  $toSet['template']  = $page;
				  break;
		  }
  */
		if ( !( !$rootNS && !$modNS && !$TplNS && !$defaultTpl ) /* &&
		     ( !empty( $rootNS ) || !empty( $modNS ) || !empty( $TplNS ) || !empty( $defaultTpl ) )*/
		) {
			switch ($page) {
				case $defaultTpl:
					$toSet['namespace'] = $rootNS . '::' . $TplNS;
					$toSet['template']  = $defaultTpl;
					break;

				default:
					$toSet['namespace'] = $modNS . '::' . $page . '::' . $TplNS;
					$toSet['template']  = $page;
					break;
			}
		}

		// throw new Exception(print_r($toSet).' ~ '.var_dump($rootNS));

		$this->setAttribute('namespace', $toSet['namespace']);
		$this->setAttribute('template', $toSet['template']);

		$fallbackNamespace = Registry::retrieve('apf::tools::html::fallbackimport', 'fallbackNamespace');
		if ( $fallbackNamespace == NULL ) {
			throw new Exception( 'namespace not found, registry settings for error fallback namespace wrong', E_USER_ERROR );
		}

		$fallbackTemplate = Registry::retrieve('apf::tools::html::fallbackimport', 'fallbackTemplate');
		if ( $fallbackTemplate == NULL ) {
			throw new Exception( 'fallback template not found in registry', E_USER_ERROR );
		}

		$this->setAttribute('fallbackNamespace', $fallbackNamespace);
		$this->setAttribute('fallbackTemplate', $fallbackTemplate);

		parent::onParseTime();
	}
}

?>
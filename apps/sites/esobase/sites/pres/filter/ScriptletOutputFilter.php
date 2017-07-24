<?php
/**
 * @author Steven Tappert
 * @link   admin@steven-tappert.de
 **/

import('core::filter', 'FilterChain');

class ScriptFilterChain extends AbstractFilterChain {

}

/**
 * @class
 */

class ScriptletOutputFilter extends ChainedGenericOutputFilter { // implements ChainedContentFilter {
	/**
	 * @param FilterChain $chain
	 * @param null        $input
	 *
	 * @return string
	 */
	public function filter( FilterChain &$chain, $input = null ) {

		// Scriptlet Tags entfernen
		$t        = Singleton::getInstance('BenchmarkTimer');
		$reportId = 'ScriptletFilter';
		$t->start($reportId);
		$input = preg_replace('/<%([^%>]+)%>/ims', '', $input);
		$t->stop($reportId);

		return $chain->filter($input);

	}

}

/*
import('core::filter', 'GenericOutputFilter');

class ScriptletOutputFilter extends ChainedGenericOutputFilter {
	public function filter( $input ) {

		// Bisherige Funktionalität ausführen
		$input = parent::filter($input);

		// Scriptlet Tags entfernen
		$t        = Singleton::getInstance('BenchmarkTimer');
		$reportId = 'whitespace_killer';
		$t->start($reportId);
		$input = preg_replace('/<%([^%>]+)%>/ims', '', $input);
		$t->stop($reportId);

		return $input;

	}

}
*/
?>
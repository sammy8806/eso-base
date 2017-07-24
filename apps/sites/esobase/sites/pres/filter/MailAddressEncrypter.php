<?php
/**
 * @author Steven Tappert
 * @link   admin@steven-tappert.de
 **/

import('core::filter', 'FilterChain');
import('tools::string', 'stringAssistant');

/**
 * @class
 */

class MailAddressEncrypter extends ChainedGenericOutputFilter { // implements ChainedContentFilter {
	/**
	 * @param FilterChain $chain
	 * @param null        $input
	 *
	 * @return string
	 */
	public function filter( FilterChain &$chain, $input = null ) {
		$t        = Singleton::getInstance('BenchmarkTimer');
		$reportId = __CLASS__;
		$t->start($reportId);
		$mailAddress = preg_replace('/([a-zA-Z0-9\.\_\-]+)([@])([a-zA-Z0-9\.\-]+)(\.)([A-Za-z][A-Za-z]+)/ims', '$1 ( at ) $3 ( dot ) $5', $input);
		$input       = $mailAddress;
		$t->stop($reportId);

		return $chain->filter($input);

	}

}

?>
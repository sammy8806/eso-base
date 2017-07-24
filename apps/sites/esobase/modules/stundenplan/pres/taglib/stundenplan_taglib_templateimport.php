<?php

import('tools::request', 'RequestHandler');
import("sites::esobase::sites::pres::taglib", "stundenplan_taglib_target");

/**
 * @class
 *
 * @author Steven Tappert
 * @version
 *         Version 0.1, 31.01.2012
 */
class stundenplan_taglib_templateimport extends core_taglib_importdesign {

	public function onParseTime() {
		$val    = $this->getAttribute('target');
		$target = stundenplan_taglib_target::getTarget();
		if ($val != $target) {
			return;
		}

		$this->setAttribute('namespace', 'sites::esobase::modules::stundenplan::pres::templates');

		try {
			parent::onParseTime();
		}
		catch (IncludeException $ie) {
			if (preg_match('$/Design "' . $this->getAttribute('template') . '" not existent in namespace$',
			               $ie->getMessage())
			) {
				$this->setAttribute('namespace', Registry::retrieve('esobase::error::404', 'namespace'));
				$this->setAttribute('template', Registry::retrieve('esobase::error::404', 'template'));
				parent::onParseTime();
			} else {
				throw new IncludeException ($ie->getMessage());
			}
		}

	}

}

?>

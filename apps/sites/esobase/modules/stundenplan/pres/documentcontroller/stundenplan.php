<?php

import("tools::request", "RequestHandler");
import("tools::http", "HeaderManager");
import("sites::esobase::sites::pres::taglib", "stundenplan_taglib_target");
import("sites::esobase::modules::stundenplan::pres::taglib", "control_taglib_checkTarget");

/**
 * @class
 * @extends Exception
 *          The exception to differ a exception from the stundenplanException from the others
 * @author  Steven Tappert
 * @version
 *          Version 0.1, 21.01.2012
 */
class stundenplanException extends Exception {

}

/**
 * @class
 * @extends base_controller
 * @author  Steven Tappert
 * @version
 *          Version 0.1, 21.01.2012
 */
class stundenplan extends base_controller {

	/**
	 * @public
	 *         Function is called when the controller is load
	 *         Redirect the user if the called target not exsists or have no relations to 'unterricht', but not check rooms.
	 * @return string
	 * @author Steven Tappert
	 * @version
	 *         Version 0.1 21.01.2012
	 */
	public function transformContent() {
		stundenplan_taglib_target::checkTarget();
		$target = array(
			'target' => stundenplan_taglib_target::getTarget(),
			'value'  => stundenplan_taglib_target::getTargetValue()
		);

		// TODO: Raum muss aber existieren
		if ( $target['target'] != 'raum' ) {
			control_taglib_checkTarget::redirectIfTargetNotExists($target, '/');
		}

		switch ($target) {
			default:
				// $this->setPlaceHolder('content', $target['target'] . ' -> ' . $target['value']);
				break;
		}

	}
}

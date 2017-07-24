<?php
/**
 * @author Steven Tappert
 * @link   admin@steven-tappert.de
 *
 **/

import('tools::form::validator', 'TextFieldValidator');
import('tools::form::validator', 'FieldCompareValidator');

class FieldCompareUpperThanRef extends FieldCompareValidator {

	public function validate($input) {
		$refValue = $this->refControl->getAttribute('value');
		if($input > $refValue){
			return true;
		}
		return false;
	}

}

?>
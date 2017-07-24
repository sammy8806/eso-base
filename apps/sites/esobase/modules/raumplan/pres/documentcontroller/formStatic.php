<?php
/**
 * @author Steven Tappert
 * @link   admin@steven-tappert.de
 *
 **/
class formStatic extends base_controller {

	/**
	 * @static
	 *
	 * @param      $form
	 * @param      $formElementName
	 *
	 * @return string
	 */
	public static function getFormElementContent(/*&$form,*/ $formElementName) {
		//$code = &$form->getFormElementByName($formElementName);
		//return $code->getValue();
		return htmlentities($_POST[$formElementName]);
	}

	/**
	 * @static
	 * 
	 * Returns only allowed array keys
	 *
	 * @param array $array
	 * @param array $allowedFields
	 * @param bool  $escapeValues
	 *
	 * @return array
	 */
	public static function stripArrayToAllowedKeys( array $array, array $allowedFields, $escapeValues=true ) {
		$output = array();
		foreach ( $allowedFields as $field )
			$output[$field] = $escapeValues ? htmlentities($array[$field]) : $array[$field];
		return $output;
	}

	public function transformContent() {

	}

}

?>
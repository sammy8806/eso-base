<?php
/**
 * @author Steven Tappert
 * @link   admin@steven-tappert.de
 **/
class formStatic extends base_controller {

	/**
	 * @static
	 *
	 * @param $formElementName
	 *
	 * @internal param $form
	 * @return string
	 */
	public static function getFormElementContent( /*&$form,*/
		$formElementName ) {
		//$code = &$form->getFormElementByName($formElementName);
		//return $code->getValue();
		return htmlentities($_POST[$formElementName]);
	}

	/**
	 * @static
	 * Returns only allowed array keys
	 *
	 * @param array $array
	 * @param array $allowedFields
	 * @param bool  $escapeValues
	 *
	 * @return array
	 */
	public static function stripArrayToAllowedKeys( array $array, array $allowedFields, $escapeValues = true ) {
		$output = array();
		foreach ( $allowedFields as $field )
			$output[$field] = $escapeValues ? htmlentities($array[$field]) : $array[$field];
		return $output;
	}

	/**
	 * @public
	 * @static
	 *
	 * @param       $form
	 * @param       $formElementName
	 * @param array $content ('value' => 'Value in HTML Tag', 'view' => 'Value showed in Select Box')
	 *
	 * @author Steven Tappert
	 * @version
	 *         Version 0.1, 28.01.2012
	 */
	public static function addOptionToFormElement( &$form, $formElementName, array $content ) {
		if(!@is_array($content[0]))
			$content = array($content);
		$select = &$form->getFormElementByName((string) $formElementName);
		for ( $i = 0; $i < count($content); $i++ ) {
			$select->addOption($content[$i]['view'], $content[$i]['value']);
		}
		return $form;
	}

	public function transformContent() {

	}

}

?>
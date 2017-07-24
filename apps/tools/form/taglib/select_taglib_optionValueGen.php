<?php
/**
 * @author Steven Tappert
 * @link   admin@steven-tappert.de
 **/
class select_taglib_optionValueGen extends form_control {

	private $out = '';

	public function __construct() {
		$this->attributeWhiteList[] = 'value';
		$this->attributeWhiteList[] = 'selected';
		$this->attributeWhiteList[] = 'label';
		$this->attributeWhiteList[] = 'valueStart';
		$this->attributeWhiteList[] = 'valueEnd';
		$this->attributeWhiteList[] = 'disabled';
	}

	/**
	 * @protected
	 *         Overwrites the <em>onParseTime()</em> methode, because here is nothing to do.
	 * @author Christian Achatz
	 * @version
	 *         Version 0.1, 29.08.2009<br />
	 */
	public function onParseTime() {
		for ( $i = $this->getAttribute('valueStart'); $i <= $this->getAttribute('valueStop'); $i++ ) {
			$this->addAttribute('value', $i);
			$this->out .= '<option ' . $this->getSanitizedAttributesAsString($this->__Attributes) . '>' . $i . '</option>';
		}
	}

	/**
	 * @public
	 *         Returns the HTML code of the option.
	 * @return string The HTML source code.
	 * @author Christian Schäfer
	 * @version
	 *         Version 0.1, 07.01.2007<br />
	 */
	public function transform() {
		return $this->out;
	}

}

?>
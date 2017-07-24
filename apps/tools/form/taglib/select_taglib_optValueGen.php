<?php
/**
 * @author Steven Tappert
 * @link   admin@steven-tappert.de
 **/
class select_taglib_optValueGen extends form_control {

	private $out = '';

	public function __construct() {
		$this->attributeWhiteList[] = 'value';
		$this->attributeWhiteList[] = 'selected';
		$this->attributeWhiteList[] = 'label';
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
		$out = '';
		for ( $i = $this->getAttribute('valueStart'); $i <= $this->getAttribute('valueStop'); $i++ ) {
			$out .=
				'<option value=' . $i . ' ' . $this->getSanitizedAttributesAsString($this->__Attributes) . '>' . $i .
				'</option>';
		}
		return $out;
	}

}

?>
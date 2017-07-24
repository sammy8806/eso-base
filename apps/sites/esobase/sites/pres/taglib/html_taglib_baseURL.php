<?php
/**
 * @author Steven Tappert
 * @link   admin@steven-tappert.de
 **/
class html_taglib_baseURL extends Document {

	/**
	 * @public
	 * @return null|string
	 */
	public function transform() {
		$value = Registry::retrieve('apf::core', 'BaseURL');
		if ( $this->getAttribute('fullURL', false) == true ) {
			return "http://" . $value . "/";
		} else {
			return $value;
		}
	}

}

?>
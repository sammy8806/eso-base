<?php
/**
 * User: Steven
 * Date: 31.01.12
 * Time: 14:49
 */

class weektable_taglib_placeholder extends html_taglib_placeholder {

	public function getGetterMethod() {
		return $this->getAttribute('getter');
	}

}

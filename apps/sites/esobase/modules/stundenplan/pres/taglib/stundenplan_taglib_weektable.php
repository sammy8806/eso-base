<?php

import('sites::esobase::modules::stundenplan::pres::taglib', 'weektable_taglib_hour');

/**
 *
 */
class stundenplan_taglib_weektable extends Document {

	public function __construct() {
		$this->__TagLibs[] = new TagLib('sites::esobase::modules::stundenplan::pres::taglib', 'weektable', 'hour');
	}

	public function onParseTime() {
		$this->__extractTagLibTags();
	}

	/**
	 * @public
	 *
	 * @return string
	 */
	public function transform() {
		foreach ($this->__Children as $objectId => $DUMMY) {
			$this->__Content = str_replace('<' . $objectId . ' />',
			                               $this->__Children[$objectId]->transform(),
			                               $this->__Content);
		}

		return $this->__Content;
	}

}
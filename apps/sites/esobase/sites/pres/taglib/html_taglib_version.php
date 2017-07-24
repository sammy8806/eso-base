<?php
/**
 * User: Steven
 * Date: 10.04.12
 * Time: 07:10
 */

class html_taglib_version extends Document {

	private $version = 'V1.3.3.7-4-l1f3 DEV';
	private $author = 'BTI 2011/2012 Projektgruppe Stundenplan-/ Raumplanung';

	public function transform() {
		$out = "";
		if($this->getAttribute('version', true))
			$out .= $this->version;

		if($this->getAttribute('author', false) && $this->getAttribute('version', true))
			$out .= "<br />\n ";

		if($this->getAttribute('author', false))
			$out .= $this->author;

		return $out;
	}

}

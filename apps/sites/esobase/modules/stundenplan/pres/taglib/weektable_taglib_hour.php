<?php

import ('sites::esobase::modules::stundenplan::pres::documentcontroller', 'stundenplan_object_view');

/**

 */
class weektable_taglib_hour extends Document {

	private $items;
	private $hour;
	private $days;

	public function onParseTime() {
		$this->items = array(
			'item1' => $this->getAttribute ('item1'),
			'item2' => $this->getAttribute ('item2'),
			'item3' => $this->getAttribute ('item3')
		);

		$this->days = $this->getAttribute ('days');
		$this->hour = $this->getAttribute ('hour');

		// die(var_dump($this));
	}

	public function genLine( $count ) {
		if ( !$count || empty( $count ) ) {
			$count = $this->days;
		}

		$out = '<tr>';
		$out .= '<td>' . $this->hour . '</td>';
		for ( $i = 1; $i < $count + 1; $i++ ) {
			$out .= $this->genDay ($i);
		}
		$out .= '</tr>';
		return $out;
	}

	public function genDay( $day, array $target = array() ) {
		if ( sizeof ($target) <= 0 ) {
			$target = array(
				'target' => stundenplan_taglib_target::getTarget (),
				'value'  => stundenplan_taglib_target::getTargetValue ()
			);
		}

		$out = '<td>';

		$hour = new stundenplan_object_view();

		$item_content = array();
		$name = array();

		foreach ( $this->items as $item ) {
			$hour_item = $hour->viewHour ($item, $target, $this->hour, $day);

			for ( $i = 0; $i < count ($hour_item); $i++ ) {
				$item_lower = strtolower($item);
				$item_content[$i][$item_lower] = $hour_item[$i]->getProperty ('Kuerzel');
				if(in_array($item_lower,array("raum", "lehrer")))
					$name[$item_lower] = $hour_item[$i]->getProperty ('Name');
			}
		}

		foreach ( $item_content as $contentKey => $content ) {
			foreach ( $content as $content_type => $content_item ) {
				$out .= '<a ';
				if($content_type != "fach") $out .= 'href="?page=stundenplan&' . strtolower ($content_type) . '=' . $content_item. '" ';
				if(in_array($content_type,array("raum", "lehrer"))) $out .= 'title="'.$name[$content_type].'" ';
				$out .= '>';
				$out .= $content_item;
				$out .= '</a>';
				if($content_type != "fach") $out .= '&nbsp;';
			}
			$out .= '<br />';
		}

		$out .= '</td>';

		return $out;
	}

	public function transform() {
		// return "Hallo - ".$this->getAttribute('hour')."\n";
		return $this->genLine ($this->days);
	}

}

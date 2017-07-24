<?php
/**
 * User: Steven
 * Date: 25.12.11
 * Time: 21:51
 */
import("tools::http", "HeaderManager");
import("sites::esobase::sites::pres::taglib", "stundenplan_taglib_target");
import("sites::esobase::sites::pres::controller", "formStatic");

class selectGenerator extends base_controller {

	/**
	 * @var #M#M#C\selectGenerator.getServiceObject.getGenericORMapper|?
	 */
	private $orm;
	/**
	 * @var The selectGenerator Form Object
	 */
	private $selectForm;
	/**
	 * @var Configuration
	 */
	private $config;

	public function __construct($gorm = null) {
		if ($gorm != null) {
			$this->orm = $gorm;
		} else {
			$ormFact   = &$this->getServiceObject('modules::genericormapper::data', 'GenericORMapperFactory');
			$this->orm = &$ormFact->getGenericORMapper("sites::esobase", "esobase", "MySQLi");
		}

		// $this->config = $this->getConfiguration('esobase', 'config.xml');
	}

	public function transformContent() {
		$HHM = $this->getServiceObject('extensions::htmlheader::biz', 'HtmlHeaderManager');
		$HHM->addNode(new SimpleTitleNode("Startseite "));

		$form             = &$this->getForm("selectGenerator");
		$this->selectForm = $form;

		$this->modifyForm($form);

		if ($form->isSent()) {
			$possibleTargets = array('klasse', 'lehrer', 'raum');
			$target          = stundenplan_taglib_target::getTarget($possibleTargets);

			$targetCount = 0;

			foreach (RequestHandler::getValues($possibleTargets) as $value) {
				if ($value != NULL) {
					$targetCount++;
				}
			}

			if (empty($target) || $target === false) {
				HeaderManager::forward("/page/home");
			} elseif ($targetCount == 1) {
				HeaderManager::redirect("/page/stundenplan/" . $target . "/" . RequestHandler::getValue($target));
			} else {
				HeaderManager::forward("/page/home");
			}

		}

		$form->transformOnPlace();
	}

	/**
	 * @public
	 *           Modify and transform the "selectGenerator"
	 *
	 * @param       &$form
	 * @param array $formText { array('class' => 'klasse', 'teacher' => 'lehrer', 'room' => 'raum') }
	 *
	 * @author   Steven Tappert
	 * @version
	 *           Version 0.1, 28.01.2012
	 */
	public function modifyForm(&$form, array $formText = array(
		'class'   => 'klasse',
		'teacher' => 'lehrer',
		'room'    => 'raum'
	)) {
		foreach ($formText as $formTextLine => $formTextLineValue)
		{
			formStatic::addOptionToFormElement($form, $formTextLineValue,
			                                   $this->genOptions($formTextLine, 'array'));
		}
	}

	/**
	 * @public
	 *         List $attribute from GORM Object $target
	 *         If Relation given, it must relate to the Object
	 *
	 * @param        $target
	 * @param        $attribute
	 * @param string $order
	 * @param string $match
	 * @param bool   $relation
	 *
	 * @return mixed
	 * @author Steven Tappert
	 * @version
	 *         Version 0.1, 21.01.2012
	 */
	public function listAttribute($target, $attribute, $order = 'ASC', $match = '%', $relation = false) {
		$crit = new GenericCriterionObject();
		$crit->addPropertyIndicator($attribute, $match);
		$crit->addOrderIndicator($attribute, $order);

		if (!$relation) {
			return $this->orm->loadObjectListByCriterion($target, $crit);
		} else {
			return $this->orm->loadObjectsWithRelation($target, $relation, $crit);
		}
	}

	/**
	 * @public
	 *         Generate select options for a normal select or a form:select ($returnType = 'array');
	 *
	 * @param        $propertyValue The select option attribute 'value' field
	 * @param        $propertyView  The select option showed name
	 * @param        $target        The related object name
	 * @param        $attribute     The searched object attribute
	 * @param string $order         The order direction asc, desc
	 * @param string $match         The matching pattern
	 * @param bool   $relation      Object must have a relation to something (true, false)
	 * @param string $returnType    html,array
	 *
	 * @return array|string The html or array
	 * @author Steven Tappert
	 * @version
	 *         Version 0.1, 21.01.2012<br />
	 *         Version 0.2, 28.01.2012 Added $returnType to switch from html to array output
	 */
	public function genSelectOptions($propertyValue, $propertyView, $target, $attribute, $order = 'ASC', $match = '%', $relation = false, $returnType = 'html') {
		$lines = $this->listAttribute($target, $attribute, $order, $match, $relation);
		if ($returnType == 'html') {
			$output = "";
		}
		if ($returnType == 'array') {
			$output = array();
		}

		foreach ($lines as $line) {
			switch ($returnType) {
				case 'html':
					$output .= '<option value="' . $line->getProperty($propertyValue) . '">' .
						$line->getProperty($propertyView) . '</option>' . "\n";
					break;

				case 'array';
					array_push($output, array(
					                         'value'  => $line->getProperty($propertyValue),
					                         'view'   => $line->getProperty($propertyView)
					                    ));
					break;
			}
		}
		return $output;
	}

	/**
	 * @private
	 *         Generate mapped the content for form:select ($returnType='array') or a normal select ($returnType='html')
	 *
	 * @param        $options
	 * @param string $returnType
	 *
	 * @return array|string
	 * @author Steven Tappert
	 * @version
	 *         Version 0.1, 21.01.2012<br />
	 *         Version 0.2, 28.01.2012 Added $returnType for generate form:select Options
	 */
	private function genOptions($options, $returnType = 'html') {
		switch ($options) {
			case 'class':
				$target        = 'Klasse';
				$attribute     = 'Kuerzel';
				$propertyValue = 'Kuerzel';
				$propertyView  = 'Kuerzel';
				$relation      = 'Unterricht2Klasse';
				break;

			case 'teacher':
				$target        = 'Lehrer';
				$attribute     = 'Kuerzel';
				$propertyValue = 'Kuerzel';
				$propertyView  = 'Kuerzel';
				$relation      = 'Unterricht2Lehrer';
				break;

			case 'room':
				$target        = 'Raum';
				$attribute     = 'Kuerzel';
				$propertyValue = 'Kuerzel';
				$propertyView  = 'Kuerzel';
				$relation      = false;
				break;

			case 'subject':
				$target        = 'Fach';
				$attribute     = 'Kuerzel';
				$propertyValue = 'Kuerzel';
				$propertyView  = 'Kuerzel';
				$relation      = false;
				break;
		}
		if (!($propertyValue || $propertyView || $target || $attribute)) {
			return;
		}

		return $this->genSelectOptions($propertyValue, $propertyView, $target, $attribute, 'ASC', '%', $relation,
		                               $returnType);
	}

	private function testMe() {
		//	return print_r ($this->selectForm);
	}

}

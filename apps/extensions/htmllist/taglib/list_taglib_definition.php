<?php
/**
 * <!--
 * This file is part of the adventure php framework (APF) published under
 * http://adventure-php-framework.org.
 *
 * The APF is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * The APF is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with the APF. If not, see http://www.gnu.org/licenses/lgpl-3.0.txt.
 * -->
 */

import('extensions::htmllist::taglib', 'list_taglib_elem_defterm');
import('extensions::htmllist::taglib', 'list_taglib_elem_defdef');

/**
 * @package extensions::htmllist::taglib
 * @class list_taglib_definition
 *
 * Represents a HTMLList definition list.
 *
 * @author Florian Horn
 * @version 1.0, 03.04.2010<br />
 */
class list_taglib_definition extends AbstractTaglibList {

   public function __construct() {
      $this->__TagLibs[] = new TagLib('extensions::htmllist::taglib', 'list', 'elem_defterm');
      $this->__TagLibs[] = new TagLib('extensions::htmllist::taglib', 'list', 'elem_defdef');
   }

   /**
    * Adds a defintion term element
    * @param string $sContent
    * @param string $sClass
    * @return string
    */
   public function addDefinitionTerm($sContent, $sClass = '') {
      return $this->__addElement($sContent, $sClass, 'elem_defterm');
   }

   /**
    * Adds a definition element.
    *
    * @param string $sContent
    * @param string $sClass
    * @return string
    */
   public function addDefinition($sContent, $sClass = '') {
      return $this->__addElement($sContent, $sClass, 'elem_defdef');
   }

   protected function __getListIdentifier() {
      return 'dl';
   }

}

?>
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

   /**
    * @package tools::form::taglib
    * @class form_taglib_area
    *
    * Represents a APF text area.
    *
    * @author Christian Sch�fer
    * @version
    * Version 0.1, 13.01.2007<br />
    */
   class form_taglib_area extends form_control {

      public function __construct() {
         $this->attributeWhiteList[] = 'name';
         $this->attributeWhiteList[] = 'cols';
         $this->attributeWhiteList[] = 'rows';
         $this->attributeWhiteList[] = 'tabindex';
         $this->attributeWhiteList[] = 'disabled';
         $this->attributeWhiteList[] = 'readonly';
      }

      /**
       * @public
       *
       * Returns the HTML source code of the text area.
       *
       * @return string HTML code of the text area.
       *
       * @author Christian Sch�fer
       * @version
       * Version 0.1, 05.01.2007<br />
       * Version 0.2, 11.02.2007 (Presetting und Validierung nach onAfterAppend() verschoben)<br />
       */
      public function transform(){
         return '<textarea '.$this->getSanitizedAttributesAsString($this->__Attributes).'>'
            .$this->__Content.'</textarea>';
      }

      /**
       * @protected
       *
       * Implements the presetting method for the text area.
       *
       * @author Christian Schäfer
       * @version
       * Version 0.1, 13.01.2007<br />
       */
      protected function __presetValue(){
         $name = $this->getAttribute('name');
         if(isset($_REQUEST[$name])){
            $this->__Content = $_REQUEST[$name];
         }
      }

      /**
       * @public
       * 
       * Re-implements the retrieving of values for text area, because
       * the text area contains it's value in the content, not in an
       * attribute.
       * 
       * @return string The current value or content of the control.
       * 
       * @since 1.14
       * 
       * @author Ralf Schubert
       * @version
       * Version 0.1, 26.07.2011<br />
       */
      public function getValue() {
          return $this->__Content;
      }
      
      /**
       * @public
       * 
       * Re-implements the setting of values for text area, because
       * the text area contains it's value in the content, not in an
       * attribute.
       * 
       * @param string $value
       * @return form_control 
       * 
       * @since 1.14
       * 
       * @author Ralf Schubert
       * @version
       * Version 0.1, 26.07.2011<br />
       */
      public function setValue($value) {
          $this->__Content = $value;
          return $this;
      }

    // end class
   }
?>
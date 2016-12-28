<?php
/*
This file is part of "Fox Contact Form", a free Joomla! 1.6 Contact Form
You can redistribute it and/or modify it under the terms of the GNU General Public License
GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
Author: Demis Palma
Documentation at http://www.fox.ra.it/joomla-extensions/fox-contact-form.html
Copyright: 2011 Demis Palma
*/
defined('_JEXEC') or die ('Restricted access');

// This can't work if called by a mudule. In this case JPATH_COMPONENT can be i.e. com_content
//require_once(JPATH_COMPONENT . DS . "helpers" . DS . "fdatapump.php");
require_once(JPATH_ROOT . DS . 'components' . DS . 'com_foxcontact' . DS . 'helpers' . DS . 'fdatapump.php');

class FieldsBuilder extends FDataPump
	{

	public function __construct(&$component_params, &$module_params, $ismodule)
		{
		parent::__construct($component_params, $module_params, $ismodule);

		$this->Name = "FFieldPump";
		if ($ismodule) $this->OverrideFields();		
		$this->ValidateEmail();  // email can have text without being valid		
		$this->isvalid = intval($this->ValidateForm());  // Is all fields valid?
		}
		

	protected function LoadFields()
		{
		// Loads parameters and $_POST data
		for ($n = 0; $n < 2; ++$n) $this->LoadField("sender", $n);
		for ($n = 0; $n < 10; ++$n) $this->LoadField("text", $n);
		for ($n = 0; $n < 3; ++$n) $this->LoadField("dropdown", $n);
		for ($n = 0; $n < 3; ++$n) $this->LoadField("textarea", $n);
		for ($n = 0; $n < 5; ++$n) $this->LoadField("checkbox", $n);
		$this->LoadField("labels", "");
		}


	protected function LoadField($type, $number)  // Example: 'text', '0'
		{
		// Load component parameters
		$name = $type . (string)$number;  // Example: 'text0'        
		// If not to be displayed, it's useless to continue reading other values
		if (!parent::LoadField($type, $name)) return false;
		// Load data
		$this->Fields[$name]['Value'] = JRequest::getVar($this->Fields[$name]['PostName'], NULL, 'POST');        

		// Additional manipulations
		if ($this->Fields[$name]['Value'] == $this->Fields[$name]['Name'])  // Example: Field='Your name' Value='Your name'
			{
			// Seems like a submission from the module without filling the field, so let's invalidate the value!
			$this->Fields[$name]['Value'] = "";
			}

		// Validation after *all* fields are loaded and manipulated
		$this->Fields[$name]['IsValid'] = intval($this->ValidateField($this->Fields[$name]['Value'], $this->Fields[$name]['Display']));

		// Checkboxes need to be manipulated after validation, otherwise a JNO value will be considered valid
		// Checkboxes have only JYES or empty values. Translate empty to JNO
		if ($type == "checkbox" && $this->Fields[$name]['Value'] == "") $this->Fields[$name]['Value'] = JText::_('JNO');

		return true;
		}


	function OverrideFields()
		{
		// Override some values with the module specific parameters
		for ($n = 0; $n < 2; ++$n) $this->OverrideField("sender", $n);
		for ($n = 0; $n < 3; ++$n) $this->OverrideField("textarea", $n);
		for ($n = 0; $n < 10; ++$n) $this->OverrideField("text", $n);
		}


	function OverrideField($type, $number)
		{
		$index = $type . (string)$number;
		// If not to be displayed, it's useless to continue overriding other values
		if (!isset($this->Fields[$index]['Display']) || !$this->Fields[$index]['Display']) return;

		// Properties named Type, Name, PostName, Display, Values don't need to be overridden
		$width = intval($this->mparams->get($type . "width", "0"));  // Read the value from the *module* parameters
		if ($width) $this->Fields[$index]['Width'] = $width;        // Only if set for the module, this value overrides the component one
		$height = intval($this->mparams->get($type . "height", "0"));  
		if ($height) $this->Fields[$index]['Height'] = $height;
		$this->Fields[$index]['Unit'] = $this->mparams->get($type . "unit", "px");  // Unit is always set for the module
		}


   public function Show(&$ViewClass)
      {
      // bool uksort ( array &$array , callback $cmp_function )
      foreach ($this->Fields as $key => $field)
         {
         switch ($field['Type'])
            {
            case 'sender':
            case 'text':
               $ViewClass->FormText .= $this->BuildTextField($key, $field);  //Example: $this->BuildTextField('sender0', $field)
               break;
            case 'dropdown':
               $ViewClass->FormText .= $this->BuildDropdownField($key, $field);  //Example: $this->BuildTextField('dropdown0', $field)
               break;
            case 'textarea':
               $ViewClass->FormText .= $this->BuildTextareaField($key, $field);  //Example: $this->BuildTextField('textarea0', $field)
               break;
            case 'checkbox':
               $ViewClass->FormText .= $this->BuildCheckboxField($key, $field);  //Example: $this->BuildTextField('checkbox0', $field)
               break;
            }
         }         
      }
   
      
   // Build a single Text field
   private function BuildTextField($key, &$field)
      {
      // Todo: this check isn't needed. This function is called only for required and optional items
      if (!isset($field['Display']) || !$field['Display']) return;

      $result = '<div style="clear:both;">';
      $result .= $this->build_label($field);
      $result .= '<input class="' .
         $this->TextStyleByValidation($field) .
         "\" type=\"text\" value=\"" .
         $field['Value'] . '" ' . // Example: $_POST[$_fieldname] = 555-12345 
         'style="' .
            'width:' . $field['Width'] . $field['Unit'] . ' !important;' .
            '" ' .
         'name="' . $field['PostName'] . '"' .
         ' />' .
         $this->DescriptionByValidation($field);  // Example: *
      $result .= "</div>" . PHP_EOL;
      
      return $result;
      }


   // Build a single Dropdown box field
   private function BuildDropdownField($key, &$field)
      {
      // Todo: this check isn't needed. This function is called only for required and optional items
      if (!isset($field['Display']) || !$field['Display']) return;

      $result = '<div style="clear:both;">';
      $result .= $this->build_label($field);
      $result .= "<select class=\"" .
         $this->TextStyleByValidation($field) .
         "\" name=\"" . $field['PostName'] . "\">";
      // Insert an empty option
      $result .= "<option value=\"\"></option>";
      // and the actual options
      $options = explode(",", $field['Values']);
      for ($o = 0; $o < count($options); ++$o)
         {
         $result .= "<option value=\"" . $options[$o] . "\"";
         if ($field['Value'] == $options[$o]) $result .= " selected ";
         $result .= ">" . $options[$o] . "</option>";
         }
      $result .= "</select>" . $this->DescriptionByValidation($field);
      $result .= "</div>\n";
      
      return $result;
      }


   // Build a single Check Box field
   private function BuildCheckboxField($key, &$field)
      {
      // Todo: this check isn't needed. This function is called only for required and optional items
      if (!isset($field['Display']) || !$field['Display']) return;

      $result = '<div style="clear:both;">';
      $result .= '<div class="' .
         $this->CheckboxStyleByValidation($field) .
			'" ' .
         'style="' .
            'float:' . $this->Style['float'] . ';' .
            '">' .
			'<input type="checkbox" ' .
         "value=\"" . JText::_('JYES') . "\" ";
         // Here, validation will be successful, because there aren't post data, but it isn't a good right to activate che checkbox with the check
         // if (intval($this->FieldsBuilder->Fields[$index]['Value'])) $this->msg .= "checked=\"\"";
         if ($field['Value'] == JText::_('JYES')) $result .= "checked=\"\"";
         $result .= "name=\"" . 
            $field['PostName'] .
            "\" /></div>" .
            $this->DescriptionByValidation($field) . 
            '<span style="margin:0 10px;">' . $field['Name'] . '</span>' .
            $this->AdditionalDescription($field['Display']);
      $result .= "</div>" . PHP_EOL;
      
      return $result;
      }


   // Build a Textarea field
   private function BuildTextareaField($key, &$field)
      {      
      // Todo: this check isn't needed. This function is called only for required and optional items
      if (!isset($field['Display']) || !$field['Display']) return;

      $result = '<div style="clear:both;">';
      $result .= $this->build_label($field);
      $result .= "<textarea " .
         'rows="" ' .
         'cols="" ' .
         'class="' . $this->TextStyleByValidation($field) . '" ' .
         'name="' . $field['PostName'] . '" ' .
         'style="' .
            "width:" . $field['Width'] . $field['Unit'] . ' !important;' .
            "height:" . $field['Height'] . 'px' . ' !important;' .  // Height in % doesn't always work
            '" ' .
         ">" .
         $field['Value'] .  // Inner Text
         "</textarea>" .
         $this->DescriptionByValidation($field);
      $result .= "</div>" . PHP_EOL;
      
      return $result;
      }


	private function build_label(&$field)
		{
      return '<label ' .
         'style="' .
            'float:' . $this->Style['float'] . ';' .
            'width:' . $this->Fields['labels']['Width'] . $this->Fields['labels']['Unit'] . ' !important;' .
            '">' .
         $field['Name'] .
         $this->AdditionalDescription($field['Display']) .
         '</label>';
		}
      

   // Check a single field and return a string good for html output
   function DescriptionByValidation(&$field)
      {
      return $field['IsValid'] ? "" : (" <span class=\"asterisk\">*</span>");
      }


   // Check a single field and return a string good for html output
   function TextStyleByValidation(&$field)
      {
      // No post data = first time here. return a grey border
      if (!$this->Submitted) return "foxtext";
      // Return a green or red border
      return $field['IsValid'] ? "validfoxtext" : "invalidfoxtext";
      }


   // Check a single field and return a string good for html output
   function CheckboxStyleByValidation(&$field)
      {
      if (!$this->Submitted) return "foxcheckbox";
      // Return a green or red border
      return $field['IsValid'] ? "validcheckbox" : "invalidcheckbox";
      }

      
	function ValidateForm()
		{
		$result = true;

		// Validate default fields
		$result &= $this->ValidateGroup("sender");
		// Validate Text fields
		$result &= $this->ValidateGroup("text");
		// Validate Dropdown fields
		$result &= $this->ValidateGroup("dropdown");
		// Validate Check Boxes
		$result &= $this->ValidateGroup("checkbox");
		// Validate text areas
		$result &= $this->ValidateGroup("textarea");

		return $result;
		}


	// $family can be 'text', 'dropdown', 'textarea' or 'checkbox'
	function ValidateGroup($family)
		{
		$result = true;

		for ($l = 0; $l < 10; ++$l)
			{
			// isset($this->Fields[$family . $l]) is needed to fix following error displayed when running on wamp server
			// Notice: Undefined index: sender[...] in C:\wamp\[...]\helpers\fieldsbuilder.php
			if (isset($this->Fields[$family . $l]) && $this->Fields[$family . $l]['Display'])
				{
				$result &= $this->Fields[$family . $l]['IsValid'];
				}
			}

		return $result;
		}


	// Check a single field and return a boolean value
	function ValidateField($fieldvalue, $fieldtype)
		{
		// Params:
		// $fieldvalue is a string with the text filled by user
		// $fieldtype can be 0 = unused, 1 = optional, 2 = required
		// S | R | F | V   (Submitted | Required | Filled | Valid)
		// 0 | 0 | 0 | 1
		// 0 | 0 | 1 | 1
		// 0 | 1 | 0 | 1
		// 0 | 1 | 1 | 1
		// 1 | 0 | 0 | 1
		// 1 | 0 | 1 | 1
		// 1 | 1 | 0 | 0
		// 1 | 1 | 1 | 1
		return !($this->Submitted && ($fieldtype == 2) && empty($fieldvalue));
		}


	function ValidateEmail()
		{
		if (!count($_POST)) return true;
		if (!isset($this->Fields['sender1']['Value'])) return false;

		//jimport('joomla.mail.helper');
		//(JMailHelper::isEmailAddress($email) == false)

		// Check the syntax
		$this->Fields['sender1']['IsValid'] &= (bool)strlen(filter_var($this->Fields['sender1']['Value'], FILTER_VALIDATE_EMAIL));

		// Check mx record
		if(function_exists('checkdnsrr') && !empty($this->Fields['sender1']['Value']))
			{
			//$this->Fields['sender1']['IsValid'] &= (checkdnsrr(array_pop(explode("@", $this->Fields['sender1']['Value'])), "MX"));
			$parts = explode("@", $this->Fields['sender1']['Value']);
			$domain = array_pop($parts);
			if (!empty($domain))
				$this->Fields['sender1']['IsValid'] &= checkdnsrr($domain, "MX");
			}

		}


	}

<?php
/*
This file is part of "Fox Contact Form", a free Joomla! 1.6 Contact Form
You can redistribute it and/or modify it under the terms of the GNU General Public License
GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
Author: Demis Palma
Documentation at http://www.fox.ra.it/joomla-extensions/fox-contact-form.html
Copyright: 2011 Demis Palma
*/

// no direct access
defined('_JEXEC') or die ('Restricted access');

require_once(JPATH_ROOT . DS . 'components' . DS . 'com_foxcontact' . DS . 'helpers' . DS . 'fdatapump.php');
require_once(JPATH_ROOT . DS . 'components' . DS . 'com_foxcontact' . DS . 'helpers' . DS . 'flogger.php');

class FAntispam extends FDataPump
	{
	public function __construct(&$component_params, &$module_params, $ismodule, &$fieldsbuilder)
		{
      parent::__construct($component_params, $module_params, $ismodule);

      $this->Name = "FAntispam";        
		$this->FieldsBuilder = $fieldsbuilder;
		$this->isvalid = intval($this->ValidateForSpam($fieldsbuilder));
		}

      
   public function Show(&$ViewClass)
      {
		// Nothing to show
      }

      
	public function SetText(&$ViewClass)
		{
		$ViewClass->TopText = $this->MakeText('spam_detected_text');
		$ViewClass->BottomText = "";

		return $this->isvalid;
		}


   protected function LoadFields()
      {
      }


	protected function ValidateForSpam(&$fieldsbuilder)
		{
		$message = "";
      foreach ($fieldsbuilder->Fields as $key => $field)
			{
			$test = strpos($field['Type'], "textarea");
			if (strpos($field['Type'], "textarea") !== 0) continue;
			$message .= $field['Value'];
			}
		// If it was a spammer, just log this attempt, drop the email, and of course notify the user with a false return value
		$spam_words = $this->cparams->get("spam_words", "");
        if (intval($this->cparams->get("spam_check", "")) && !empty($spam_words))
            {
            $arr_spam_words = explode(",", $spam_words);
            foreach($arr_spam_words as $word)
                {
                if (stripos($message, $word) !== false)
                    {
						  $logger = new FLogger();
                    $logger->Write("Spam attempt blocked:" . PHP_EOL . print_r($fieldsbuilder->Fields, true) . "-----------------------------------------");
                    return false;
                    }
                }
            // Spam ckeck successful
            }
        // Spam check disabled
		return true;
		}


	}


?>

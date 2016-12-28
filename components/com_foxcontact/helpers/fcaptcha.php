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
require_once(JPATH_ROOT . DS . 'components' . DS . 'com_foxcontact' . DS . 'helpers' . DS . 'fsession.php');

class FCaptcha extends FDataPump
	{

	protected $ComponentId;
	protected $fsession;

	public function __construct(&$component_params, &$module_params, $ismodule)
		{
		parent::__construct($component_params, $module_params, $ismodule);

		$this->Name = "FCaptcha";
/*
		if ($ismodule) $this->OverrideFields();		
*/
		$this->ComponentId = $this->GetComponentId();

		// Read captcha value submitted
		$this->Fields['Value'] = $this->FaultTolerance(JRequest::getVar("fcaptcha", NULL, 'POST'));
		// Read captcha answer
		$jsession = JFactory::getSession();
		$this->fsession = new FSession($jsession->getId(), $this->ComponentId, JRequest::getVar("mid", NULL, 'POST'));
		$this->Fields['Secret'] = $this->FaultTolerance($this->fsession->Load('captcha_answer'));
/*
		// Reset captcha solution in the session after read it, avoiding that a fucked lamer
		// abuse of the *same session* without request the captcha again, to send tons of email
		$fsession->PurgeValue("captcha_answer");
*/
		// Check if the answer if correct
		$this->isvalid = intval($this->Validate());
		}
		

	protected function LoadFields()
		{
		}


	protected function LoadField($type, $number)  // Example: 'text', '0'
		{
		}


	function OverrideFields()
		{
		}


	function OverrideField($type, $number)
		{
		}


   public function Show(&$ViewClass)
      {
		if (!(bool)$this->cparams->get("stdcaptchadisplay")) return;

		$this->js_load("fcaptcha.js", 1, 0);

		$captcha_width = $this->cparams->get("stdcaptchawidth", "");
		$captcha_height = $this->cparams->get("stdcaptchaheight", "");

		$valid = (!empty($this->Fields['Secret']) && $this->Fields['Value'] == $this->Fields['Secret']);

		$ViewClass->FormText .=
			'<div style="clear:both;';
		if ($valid) $ViewClass->FormText .= 'display:none !important;';
		$ViewClass->FormText .= '">' .

			// Label
			'<label ' .
         'style="' .
            'float:' . $this->Style['float'] . ';' .
            'width:' . $this->cparams->get('labelswidth') . $this->cparams->get('labelsunit') . ' !important;' .
            '">' .
			$this->cparams->get("stdcaptcha") .
			'</label>' .

			'<div class="fcaptchacontainer" ' .
         'style="' .
            'float:' . $this->Style['float'] . ';' .
            '">';

		if (!$valid)
			{
			$ViewClass->FormText .=

			// Captcha image
			'<div class="fcaptchafieldcontainer">' .
			'<img src="' . JURI::base(true) . '/components/com_foxcontact/helpers/fcaptcha-drawer.php?cid=' .
			$this->ComponentId . '" ' .
			'alt="captcha" ' .  // Need by w3c validator
			'id="fcaptcha" width="' . $captcha_width . '" height="' . $captcha_height . '"/>' .
			'</div>' .  // fcaptchafieldcontainer

			// Reload button
			'<div class="fcaptchafieldcontainer">' .
			// Show a transparent dummy image
			'<img src="' . JURI::base(true) . '/media/com_foxcontact/images/transparent.gif" ' .
				'id="reloadbtn" ' .
				'alt="' . JTEXT::_('COM_FCF_RELOAD_ALT') . '" ' .
				'title="' . JTEXT::_('COM_FCF_RELOAD_TITLE') . '" ' .
				"onclick=\"javascript:ReloadFCaptcha('fcaptcha')\" />" .
			'</div>'.   // fcaptchafieldcontainer
			// Without javascript enable, you will not be able to click reload button, so let's show it only if javascript is enabled
			"<script language=\"javascript\" type=\"text/javascript\">BuildReloadButton('reloadbtn');</script>";
			}

		$ViewClass->FormText .=
			// Input for answer
			'<div class="fcaptchainputcontainer">' .
			'<input ' .
			'class="' . $this->TextStyleByValidation() . '" ' .
			'type="text" ' .
			'name="' . "fcaptcha". '" ' .
			'style="width:' . $captcha_width . 'px !important;" ';

		if ($valid)
			{
			$ViewClass->FormText .=
				'value="' . $this->Fields['Value'] . '" ' .
				'readonly="readonly" ';
			}

		$ViewClass->FormText .=
         '/>' .
			$this->DescriptionByValidation() .  // Example: *
			'</div>' .  // fcaptchainputcontainer
			'</div>' .  // fcaptchacontainer
			'</div>' .  // Row div
			PHP_EOL;
      }


	public function SetText(&$ViewClass)
		{
		// Reset captcha solution in the session after read it, avoiding that a fucked lamer
		// abuse of the *same session* without request the captcha again, to send tons of email
		$this->fsession->PurgeValue("captcha_answer");

		return parent::SetText($ViewClass);
		}


	// Check a single field and return a boolean value
	function Validate()
		{
		//$isrequired = ($this->Fields['Display']);
		$isrequired = (bool)$this->cparams->get("stdcaptchadisplay");

		// Value == Secret == NULL is not a valid condition
		$this->isvalid = (!empty($this->Fields['Secret']) && $this->Fields['Value'] == $this->Fields['Secret']);
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
		// $this->isvalid now stores the state of the uploaded file only...
		return !($this->Submitted && $isrequired && !$this->isvalid);
		// ..but after returning it will consider the submitted and required state too
		}


   private function TextStyleByValidation()
      {
/*
      // No post data = first time here
		// Field is valid = we can't confuse the user telling he gave a wrong answer
		// In both cases, return a grey border
      if (!$this->Submitted || $this->isvalid) return "foxtext";

		// Form submitted and wrong captcha answer
      return "invalidfoxtext";
*/
      // No post data = first time here. return a grey border
      if (!$this->Submitted) return "foxtext";
      // Return a green or red border
      return $this->isvalid ? "validfoxtext" : "invalidfoxtext";

      }

   private function DescriptionByValidation()
      {
      return $this->isvalid ? "" : (" <span class=\"asterisk\">*</span>");
      }

	private function GetComponentId()
		{
		//$wholemenu =& JSite::getMenu();
		$site = new JSite();
		$wholemenu = $site->getMenu();
		$targetmenu = $wholemenu->getActive();		
		return $targetmenu->id;
		}


	private function FaultTolerance($string)
		{
		// Convert in lower case
		$string = strtolower($string);
		// correct common mistakes
		$string = preg_replace("/[l1]/", "i", $string);   // I i l 1 -> i
		$string = preg_replace("/[0]/", "o", $string);   // O o 0 -> o
		$string = preg_replace("/[q9]/", "g", $string);   // g q 9 -> g
		$string = preg_replace("/[5]/", "s", $string);   // S s 5 -> s
		$string = preg_replace("/[8]/", "b", $string);   // B 8 -> b

		return $string;
		}

	}

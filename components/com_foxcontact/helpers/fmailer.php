<?php
/*
This file is part of "Fox Contact Form", a free Joomla! 1.6 Contact Form
You can redistribute it and/or modify it under the terms of the GNU General Public License
GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
Author: Demis Palma
Documentation at http://www.fox.ra.it/joomla-extensions/fox-contact-form.html
Copyright: 2011 Demis Palma
*/
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_ROOT . DS . 'components' . DS . 'com_foxcontact' . DS . 'helpers' . DS . 'fdatapump.php');
require_once(JPATH_ROOT . DS . 'components' . DS . 'com_foxcontact' . DS . 'helpers' . DS . 'fieldsbuilder.php');
require_once(JPATH_ROOT . DS . 'components' . DS . 'com_foxcontact' . DS . 'helpers' . DS . 'flogger.php');
require_once(JPATH_ROOT . DS . 'components' . DS . 'com_foxcontact' . DS . 'helpers' . DS . 'fsession.php');
require_once(JPATH_ROOT . DS . 'components' . DS . 'com_foxcontact' . DS . 'helpers' . DS . 'functions.php');

class FMailer extends FDataPump
	{
	protected $FieldsBuilder;
	  
	public function __construct(&$component_params, &$module_params, $ismodule, &$fieldsbuilder)
		{
		parent::__construct($component_params, $module_params, $ismodule);

		$this->Name = "FMailer";
		$this->FieldsBuilder = $fieldsbuilder;
		$this->Logger = new FLogger();
		}


	protected function LoadFields()
		{
		}


	public function Send(&$ViewClass)
		{
		$debug_log = new FDebugLogger("fmailer");

		$fromname = $this->FieldsBuilder->Fields['sender0']['Value'];
		$from = $this->FieldsBuilder->Fields['sender1']['Value'];

		jimport('joomla.factory');
		$mail = JFactory::getMailer();

//		if ($this->Application->getCfg("mailer") == "smtp" && (bool)$this->Application->getCfg("smtpauth") == true)
		if ($this->Application->getCfg("mailer") == "smtp" && (bool)$this->Application->getCfg("smtpauth") && strpos($this->Application->getCfg("smtpuser"), "@") !== false)
			{
			// With SMTP auth may be needed to set the username as the sender
			$mail->setSender(array($this->Application->getCfg("smtpuser"), $this->Application->getCfg("fromname")));
			$mail->addReplyTo(array($from, $fromname));
			}
		else
			{
			$mail->setSender(array($from, $fromname));
			}

		if ($this->FieldsBuilder->cparams->get("to_address", NULL))
			$recipients = explode(",", $this->FieldsBuilder->cparams->get("to_address", ""));
		else
			$recipients = array();
		// http://docs.joomla.org/How_to_send_email_from_components
		foreach ($recipients as $recipient)
			{
			// Avoid to call $mail->add..() with an empty string, since explode(",", $string) returns al least 1 item, even if $string is empty
			if (empty($recipient)) continue;
			$mail->addRecipient($recipient);
			}

		if ($this->FieldsBuilder->cparams->get("cc_address", NULL))
			$cc_addresses = explode(",", $this->FieldsBuilder->cparams->get("cc_address", ""));
		else
			$cc_addresses = array();

		foreach ($cc_addresses as $cc)
			{
			// Avoid to call $mail->add..() with an empty string, since explode(",", $string) returns al least 1 item, even if $string is empty
			if (empty($cc)) continue;
			$mail->addCC($cc);
			}

		if ($this->FieldsBuilder->cparams->get("bcc_address", NULL))
			$bcc_addresses = explode(",", $this->FieldsBuilder->cparams->get("bcc_address", ""));
		else
			$bcc_addresses = array();

		foreach ($bcc_addresses as $bcc)
			{
			// Avoid to call $mail->add..() with an empty string, since explode(",", $string) returns al least 1 item, even if $string is empty
			if (empty($bcc)) continue;
			$mail->addBCC($bcc);
			}

		$mail->setSubject(JMailHelper::cleanSubject($this->FieldsBuilder->cparams->get("email_subject", "")));

		// Body
		$body = "";
		// Special fields
		for ($l = 0; $l < 2; ++$l) $body .= $this->AddToBody("sender" . $l);
		for ($l = 0; $l < 3; ++$l) $body .= $this->AddToBody("textarea" . $l);
		for ($l = 0; $l < 10; ++$l) $body .= $this->AddToBody("text" . $l);
		for ($l = 0; $l < 3; ++$l) $body .= $this->AddToBody("dropdown" . $l);
		for ($l = 0; $l < 5; ++$l) $body .= $this->AddToBody("checkbox" . $l);

		// a blank line
		$body .= PHP_EOL;

		// Read the list from the session
		//$wholemenu =& JSite::getMenu();
		$site = new JSite();
		$wholemenu = $site->getMenu();
		$targetmenu = $wholemenu->getActive();
		$jsession = JFactory::getSession();
		$fsession = new FSession($jsession->getId(), $targetmenu->id, 0);  // mid = 0 means read all rows regardless of mid
		$data = $fsession->Load('filelist');  // Read the list from the session
		if ($data) $filelist = explode("|", $data);
		else $filelist = array();

		$uploadmethod = intval($this->cparams->get("uploadmethod", "1"));  // How the webmaster wants to receive attachments
		if (count($filelist) && ($uploadmethod & 1)) $body .= "Attachments:" . PHP_EOL;
		foreach ($filelist as &$file)
			{  // binary 01: http link, binary 10: attach, binary 11: both
			if ($uploadmethod & 1) $body .= JURI::base() . 'components' . DS . 'com_foxcontact' . DS . 'uploads' . DS . $file . PHP_EOL;
			if ($uploadmethod & 2) $mail->addAttachment(JPATH_COMPONENT . DS . 'uploads' . DS . $file);
			}
		// Clear file list for the next submission of the same users
		$fsession->Clear('filelist');
		$body .= PHP_EOL;
                
		// Info about url
		$body .= $this->Application->getCfg("sitename") . " - " . $this->CurrentURL() . PHP_EOL;

		// Info about client
		$body .= "Client: " . $this->ClientIPaddress() . " - " . $_SERVER['HTTP_USER_AGENT'] . PHP_EOL;

		jimport('joomla.mail.helper');
		$body = JMailHelper::cleanBody($body);
		$mail->setBody($body);

		$this->Logger->Write("---------------------------------------------------" . PHP_EOL . $body);

		if (($result = $mail->Send()) !== true)
			{
			$msg = empty($mail->ErrorInfo) ? $result->getMessage() : $mail->ErrorInfo;
			$ViewClass->TopText =
	         '<div class="foxmessage" style="clear:both;">' .
	         JText::_("COM_FCF_ERR_SENDING_MAIL") . ". " .
				GetHelpLink($msg) .
	         '</div>';
			$ViewClass->BottomText = "";

			$this->Logger->Write($mail->ErrorInfo);
			$debug_log->Write($mail->ErrorInfo);
			return false;
			}
		$debug_log->Write("Email sent.");

		// Set the bottom text to notify email send success
		$ViewClass->TopText = $this->MakeText('email_sent_text');
		// Clear the bottom text
		$ViewClass->BottomText = "";

		return true;
		}


    private function AddToBody($index)
        {
        if (!isset($this->FieldsBuilder->Fields[$index]) || !$this->FieldsBuilder->Fields[$index]['Display']) return "";

        // How the admin labelled this field
        $fieldname = $this->FieldsBuilder->Fields[$index]['Name'];
        // How the user filled this field
        $fieldvalue = $this->FieldsBuilder->Fields[$index]['Value'];
        return $fieldname . ": " . $fieldvalue . PHP_EOL;
        }

    private function CurrentURL()
        {
        $url = 'http';
        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") $url .= "s";
        $url .= "://";
        $url .= $_SERVER["SERVER_NAME"];
        if ($_SERVER["SERVER_PORT"] != "80") $url .= ":" . $_SERVER["SERVER_PORT"];
        $url .= $_SERVER["REQUEST_URI"];
        return $url;
        }


    private function ClientIPaddress()
        {
        if (isset($_SERVER["REMOTE_ADDR"])) return $_SERVER["REMOTE_ADDR"];
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) return $_SERVER["HTTP_X_FORWARDED_FOR"];
        if (isset($_SERVER["HTTP_CLIENT_IP"])) return $_SERVER["HTTP_CLIENT_IP"];
        return "?";
        } 

    }
?>

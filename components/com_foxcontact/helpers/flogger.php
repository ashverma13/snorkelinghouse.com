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

require_once(JPATH_ROOT . DS . 'components' . DS . 'com_foxcontact' . DS . 'helpers' . DS . 'fsession.php');

class FLogger
   {
   protected $Handle = NULL;
   protected $Prefix = "";
   protected $Application;
   
   public function __construct($prefix = NULL, $suffix = NULL)
      {
		$this->open($suffix);		
		if ($prefix) $this->Prefix = "[" . $prefix . "] ";
      }

      
   function __destruct()
      {
      if ($this->Handle) fclose($this->Handle);
      }
      
      
   public function Write($buffer)
      {
      if (!$this->Handle) return false;
		// Go to the end of file if another instance has write something else
		fseek($this->Handle, 0, SEEK_END);
      $now = JFactory::getDate();
      return fwrite($this->Handle, $now->toFormat() . " " . $this->Prefix . $buffer . PHP_EOL);
      }

	protected function open($suffix = NULL)
		{
		$application = JFactory::getApplication();
		if (!$suffix) $suffix = md5($application->getCfg("secret"));
		$this->Handle = @fopen($application->getCfg("log_path") . DS . "foxcontact-" . $suffix . ".txt", 'a+');
		}       
   }
   
   
class FDebugLogger extends FLogger
	{
	public function __construct($prefix = NULL)
		{
		$jsession = JFactory::getSession();
		$debug = $jsession->get("debug");                
		if ($debug) $this->open("debug");
		$this->Prefix = "[" . $prefix . "] ";
		}
	}

?>
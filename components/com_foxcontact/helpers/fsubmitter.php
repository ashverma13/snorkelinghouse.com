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

class FSubmitter extends FDataPump
	{
    
	public function __construct(&$component_params, &$module_params, $ismodule)
		{
      parent::__construct($component_params, $module_params, $ismodule);

      $this->Name = "FSubmitter";        
		// count($_POST):
		// 0  -> no submitted
		// 1  -> submitted, but $_FILES exceeds server limits, and is been resetted
		// 1+ -> submittend. We can try to validate fields.
      $this->isvalid = (count($_POST) > 1);
		}

      
   public function Show(&$ViewClass)
      {
		// Submit button
		$ViewClass->FormText .= '<div style="clear:both;">' .
			'<button class="foxbutton" type="submit">' . JText::_('JSUBMIT') . '</button>' .
			'</div>';
      }

      
   public function SetText(&$ViewClass)
      {
      $ViewClass->TopText = $this->MakeText('top_text');
      $ViewClass->BottomText = $this->MakeText('bottom_text');

      return $this->isvalid;
      }

/*
	public function GetTargetLink()
		{
		$site = new JSite();
		$wholemenu = $site->getMenu();
		$targetmenu = $wholemenu->getActive();		
		$link = $targetmenu->link;  // Get target link		
		$router = JSite::getRouter();
		// Build it with the correct id
		if ($router->getMode() == JROUTER_MODE_SEF) $link = 'index.php?Itemid=' . $targetmenu->id;
		else $link .= '&Itemid=' . $targetmenu->id;
		// Finally translate it in a SEF one if needed
		return JRoute::_($link);
		}
 */
 
   protected function LoadFields()
      {
      }


	}


?>

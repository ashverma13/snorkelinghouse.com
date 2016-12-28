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
require_once(JPATH_ROOT . DS . 'components' . DS . 'com_foxcontact' . DS . 'helpers' . DS . 'fsession.php');

class FAjaxUploader extends FDataPump
	{
	protected $TopText;
        
	public function __construct(&$component_params, &$module_params, $ismodule)
		{
		parent::__construct($component_params, $module_params, $ismodule);

		$this->Name = "FAjaxFilePump";
		$this->isvalid = true;
		}

 
	protected function LoadFields()
		{
		// Nothing to load for the moment
		}

        
	// Build a multiple upload field
	public function Show(&$ViewClass)
		{
		if (!(bool)$this->cparams->get("uploaddisplay")) return;

		$placeholders = $values = array();
		$placeholders[] = '{%COM_FCF_BROWSE_FILES%}';
		$placeholders[] = '{%FLOAT%}';
		$placeholders[] = '{%JCANCEL%}';
		$placeholders[] = '{%COM_FCF_FAILED%}';
		$placeholders[] = '{%COM_FCF_SUCCESS%}';
		$placeholders[] = '{%Action%}';
		$values[] = JTEXT::_('COM_FCF_BROWSE_FILES');
		$values[] = $this->Style['float'];
		$values[] = JTEXT::_('JCANCEL');
		$values[] = JTEXT::_('COM_FCF_FAILED');
		$values[] = JTEXT::_('COM_FCF_SUCCESS');
		$values[] = JURI::base(true) . '/components/com_foxcontact/helpers/qqfileuploader.php'; // Do not use DS since it is used on client side in Javascript createUploader function 

		// Show main uploader javascript in <head> section as a source
		$this->js_load("fileuploader-min.js", 1, 0, $placeholders, $values);

		//$wholemenu =& JSite::getMenu();
		$site = new JSite();
		$wholemenu = $site->getMenu();
		$targetmenu = $wholemenu->getActive();
		$cid = $targetmenu->id;

		$ViewClass->FormText .=
			// Open row container
			'<div style="clear:both;">' .
			// Label
			'<label ' .
         'style="' .
//            'float:' . $this->Style['float'] . ';' .
//            'width:' . $this->cparams->get('labelswidth') . $this->cparams->get('labelsunit') . ' !important;' .
            '">' .
			$this->cparams->get('upload') . ". " .
			JTEXT::_('COM_FCF_FILE_SIZE_LIMIT') . " " . $this->human_readable($this->cparams->get("uploadmax_file_size") * 1024) . "." .
			'</label>' .

			// Upload button and list container
			'<div id="foxupload_cid_' . $cid . '" ' .
			//'style="float:' . $this->Style['float'] . '"' .
			'></div>' . PHP_EOL .
			"<script language=\"javascript\" type=\"text/javascript\">createUploader('foxupload_cid_$cid', $cid, 0);</script>" .

			// for browsers without javascript support only
			'<noscript>' . 
			// Standard file input 
			'<input ' .
			'type="file" ' .
			'id="foxstdupload" ' .
			'name="foxstdupload"' .
			" />" . 
			'</noscript>' .

			// Close row container
			"</div>". PHP_EOL;

		$jsession = JFactory::getSession();
		$fsession = new FSession($jsession->getId(), $cid, 0);  // mid = 0 means read all rows regardless of mid
		$data = $fsession->Load('filelist');  // Read the list from the session
		if ($data) $filelist = explode("|", $data);
		else $filelist = array();

		if (count($filelist))
			{
			// Previuosly completed uploads
			$ViewClass->FormText .= '<div style="clear:both;"><ul class="qq-upload-list">';
			foreach ($filelist as &$file)
				{
				$ViewClass->FormText .=
				'<li class="qq-upload-success" style="background-position:' . $this->Style['float'] . ';">' .
				'<span class="qq-upload-file" style="float:' . $this->Style['float'] . '">' . substr($file, 14) . '</span>' .
				'<span class="qq-upload-success-text" style="background-position:' . $this->Style['float'] . ';">' . JTEXT::_('COM_FCF_SUCCESS') . '</span>' .
				'</li>';
				}
			$ViewClass->FormText .= '</ul>' . "</div>". PHP_EOL;                			
			}
		}

      
   public function SetText(&$ViewClass)
      {
		// Valid is always true since this kind of field is not mandatory
      return $this->isvalid;
      }


	protected function human_readable($value)
		{		
		for ($i = 0; $value >= 1000; ++$i) $value /= 1024;
		$powers = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
		return round($value, 1) . " " . $powers[$i];
		}

	}

?>

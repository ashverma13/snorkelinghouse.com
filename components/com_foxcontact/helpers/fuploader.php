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

define('KB', 1024);
require_once(JPATH_ROOT . DS . 'components' . DS . 'com_foxcontact' . DS . 'helpers' . DS . 'fdatapump.php');
require_once(JPATH_ROOT . DS . 'components' . DS . 'com_foxcontact' . DS . 'helpers' . DS . 'fsession.php');
require_once(JPATH_ROOT . DS . 'components' . DS . 'com_foxcontact' . DS . 'helpers' . DS . 'fmimetype.php');

class FUploader extends FDataPump
	{
        
	public function __construct(&$component_params, &$module_params, $ismodule)
		{
		parent::__construct($component_params, $module_params, $ismodule);

		$this->Name = "FFilePump";        
		$this->isvalid = intval($this->DoUpload());
		}

 
	protected function LoadFields()
		{
		// Loads parameters and $_POST data
		$this->LoadField("upload", NULL);
		}

        
	public function Show(&$ViewClass)
		{
      }

      
   public function SetText(&$ViewClass)
      {
      $ViewClass->BottomText = $this->MakeText('bottom_text');

      return $this->isvalid;
      }

     
	protected function DoUpload()
		{
		//Retrieve file details from uploaded file, sent from upload form
		$file = JRequest::getVar('foxstdupload', NULL, 'files', 'array');

		// $file is null when a browser with javascipt didn't send $_FILES at all
		// $file['error'] is UPLOAD_ERR_NO_FILE when a browser without javascipt sent $_FILES empty
		if (!$this->Submitted || !$file || $file['error'] == UPLOAD_ERR_NO_FILE) return true;

		$upload_directory = JPATH_COMPONENT . DS . "uploads" . DS;
		if (!is_writable($upload_directory))
			{
			JError::raiseWarning(0, JTEXT::_('COM_FCF_ERR_DIR_NOT_WRITABLE'));
			return false;
			}

		// Check for http $_FILES upload errors
		if ($file['error'])
			{
			// case 1 UPLOAD_ERR_INI_SIZE: 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
			// case 2 UPLOAD_ERR_FORM_SIZE: 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
			// case 3 UPLOAD_ERR_PARTIAL: 'The uploaded file was only partially uploaded'; 
			// case 4 UPLOAD_ERR_NO_FILE: 'No file was uploaded'; 
			// case 6 UPLOAD_ERR_NO_TMP_DIR: 'Missing a temporary folder'; 
			// case 7 UPLOAD_ERR_CANT_WRITE: 'Failed to write file to disk'; 
			// case 8 UPLOAD_ERR_EXTENSION: 'File upload stopped by extension'; 
			JError::raiseWarning(0, JText::sprintf('COM_FCF_ERR_UPLOAD', $file['error']));
			return false;
			}

		// Check file size
		$size = $file['size'];
		if ($size == 0)  // It must be > 0
			{
			JError::raiseWarning(0, JTEXT::_('COM_FCF_ERR_FILE_EMPTY'));
			return false;
			}
		$max_filesize = intval($this->cparams->get("uploadmax_file_size", "0")) * KB;
		if ($size > $max_filesize)  // and < max limit
			{
			JError::raiseWarning(0, JTEXT::_('COM_FCF_ERR_FILE_TOO_LARGE'));
			return false;
			}

		$mimetype = new FMimeType();
		if (!$mimetype->Check($file['tmp_name'], $this->cparams))
			{
			// Noo need to delete the file uploaded
			//unlink($file['tmp_name']);
			JError::raiseWarning(0, JTEXT::_('COM_FCF_ERR_MIME') . " [" . $mimetype->Mimetype . "]");
			return false;
			}

		//Import filesystem libraries. Perhaps not necessary, but does not hurt
		jimport('joomla.filesystem.file');

		//Clean up filename to get rid of strange characters like spaces and others
		$filename = JFile::makeSafe($file['name']);
		// Assign a random unique id to the file name, to avoid that lamers can force the server to execute their uploaded shit
		$filename = uniqid() . "-" . $filename;
		$dest = $upload_directory . $filename;

		// Todo: This attempt doesn't intercept the exception
		/*
		try
		{
		JFile::upload($file['tmp_name'], $dest);
		}
		catch (Exception $e)
		{
		//$e->getMessage()
		return false;
		}            
		*/
		if (!JFile::upload($file['tmp_name'], $dest)) return false;
		// Upload successful. Add an element to the uploads list
		$wholemenu =& JSite::getMenu();
		$targetmenu = $wholemenu->getActive();
		$jsession =& JFactory::getSession();
		$fsession = new FSession($jsession->getId(), $targetmenu->id, 0);  // session_id, cid, mid
		// Store the answer in the session
		$data = $fsession->Load('filelist');  // Read the list from the session
		if ($data) $filelist = explode("|", $data);
		else $filelist = array();
		$filelist[] = $filename; // Append this file to the list
		$data = implode("|", $filelist);
		$fsession->Save($data, "filelist");

		return true;
		}
        
        
        
        
	}

?>

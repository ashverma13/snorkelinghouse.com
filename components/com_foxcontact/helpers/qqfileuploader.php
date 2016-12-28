<?php

define('_JEXEC', 1);
define('DS', DIRECTORY_SEPARATOR);
define('JPATH_BASE', realpath(dirname(__FILE__) . DS . '..' . DS . '..' . DS . '..'));
require_once(JPATH_BASE . DS . 'includes' . DS . 'defines.php');
require_once(JPATH_BASE . DS . 'includes' . DS . 'framework.php');

define('KB', 1024);
require_once(JPATH_ROOT . DS . 'components' . DS . 'com_foxcontact' . DS . 'helpers' . DS . 'fsession.php');
require_once(JPATH_ROOT . DS . 'components' . DS . 'com_foxcontact' . DS . 'helpers' . DS . 'flogger.php');
require_once(JPATH_ROOT . DS . 'components' . DS . 'com_foxcontact' . DS . 'helpers' . DS . 'fmimetype.php');

// @ avoids Warning: ini_set() has been disabled for security reasons in /var/www/libraries/joomla/[...]
/*$mainframe =&*/ @JFactory::getApplication('site');  // Needed to get the correct session with JFactory::getSession() below

$config = JFactory::getConfig();
$default_lang = $config->get('language');
$lang_param = JComponentHelper::getParams('com_languages');
$frontend_lang = $lang_param->get('site', $default_lang);
//$lang = JFactory::getLanguage();
// @ avoids Warning: ini_set() has been disabled for security reasons in /var/www/libraries/joomla/[...]
$lang = @JLanguage::getInstance($frontend_lang);
@$lang->load('com_foxcontact');


switch (true)
	{
	case isset($_GET['qqfile']): $um = new XhrUploadManager(); break;
	case isset($_FILES['qqfile']): $um = new FileFormUploadManager(); break;
	default:
		// Malformed / malicious request, or attachment exceeds server limits
		$result = array('error' => $lang->_('COM_FCF_ERR_NO_FILE'));
		exit(htmlspecialchars(json_encode($result), ENT_NOQUOTES));
	}
$result = $um->HandleUpload('..' . DS . 'uploads' . DS, $lang);
// to pass data through iframe you will need to encode all html tags
echo(htmlspecialchars(json_encode($result), ENT_NOQUOTES));


abstract class FUploadManager
	{
	protected $Cparams;
	protected $Session;
	protected $Log;
	protected $DebugLog;

	abstract protected function save_file($path);
	abstract protected function get_file_name();
	abstract protected function get_file_size();


	function __construct()
		{
		$this->Session = JFactory::getSession();
		$cid = JRequest::getVar("cid", NULL, 'GET');  // Component id. We need to load parameters for it
		//$wholemenu =& JSite::getMenu();
		$site = new JSite();
		// @ avoids Warning: ini_set() has been disabled for security reasons in /var/www/libraries/joomla/[...]
		$wholemenu = @$site->getMenu();
		$this->Cparams = $wholemenu->getParams($cid);  // Component parameters

		$this->Log = new FLogger();
		$this->DebugLog = new FDebugLogger("qqfileuploader");
		}


	public function HandleUpload($uploadDirectory, &$lang)
		{
		$this->DebugLog->Write("HandleUpload() started");

		if (!is_writable($uploadDirectory))
			{
			$this->DebugLog->Write("Directory " . $uploadDirectory . " is not writable");
			return array('error' => $lang->_('COM_FCF_ERR_DIR_NOT_WRITABLE'));
			}
		$this->DebugLog->Write("Directory " . $uploadDirectory . " is ok");
		
		// Check file size
		$size = $this->get_file_size();		
		if ($size == 0)  // It must be > 0
			{
			$this->DebugLog->Write("File size is 0");
			return array('error' => $lang->_('COM_FCF_ERR_FILE_EMPTY'));
			}
		$this->DebugLog->Write("File size is > 0");

		// uploadmax_file_size defaults to 0 to prevent hack attempts
		$max = $this->Cparams->get("uploadmax_file_size", 0) * KB;  // and < max limit
 		if ($size > $max)
			{
			$this->DebugLog->Write("File size too large ($size > $max)");
			return array('error' => $lang->_('COM_FCF_ERR_FILE_TOO_LARGE'));
			}
 		$this->DebugLog->Write("File size ($size / $max) is ok");

		// Clean file name
		$filename = preg_replace("/[^\w\.-_]/", "_", $this->get_file_name());
		// Assign a random unique id to the file name, to avoid that lamers can force the server to execute their uploaded shit
		$filename = uniqid() . "-" . $filename;
		$full_filename = $uploadDirectory . $filename;

		if (!$this->save_file($full_filename))
			{
			$this->DebugLog->Write("Error saving file");
			return array('error'=> $lang->_('COM_FCF_ERR_SAVE_FILE'));
			}
		$this->DebugLog->Write("File saved");

		$mimetype = new FMimeType();
		if (!$mimetype->Check($full_filename, $this->Cparams))
			{
			// Delete the file uploaded
			unlink($full_filename);
			$this->DebugLog->Write("File type [" . $mimetype->Mimetype . "] is not allowed. Allowed types are:" . PHP_EOL . print_r($mimetype->Allowed, true));
			return array('error' => $lang->_('COM_FCF_ERR_MIME') . " [" . $mimetype->Mimetype . "]");
			}
		$this->DebugLog->Write("File type [" . $mimetype->Mimetype . "] is allowed");

		$cid = JRequest::getVar("cid", NULL, 'GET');
		$mid = JRequest::getVar("mid", NULL, 'GET');
		$jsession = JFactory::getSession();
		$fsession = new FSession($jsession->getId(), $cid, $mid);

		// Store the answer in the session
		$data = $fsession->Load('filelist');  // Read the list from the session
		if ($data) $filelist = explode("|", $data);
		else $filelist = array();
		$filelist[] = $filename; // Append this file to the list
		$data = implode("|", $filelist);
		$fsession->Save($data, "filelist");

		$this->Log->Write("File " . $filename . " uploaded succesful.");
		$this->DebugLog->Write("File uploaded succesful.");
		return array("success" => true);        
	}


}


// File uploads via XMLHttpRequest
class XhrUploadManager extends FUploadManager
	{

	public function __construct()
		{
		parent::__construct();
		}


	protected function save_file($path)
		{
		$input = fopen("php://input", "r");
		// Todo: non ho bisogno di un file temporaneo, potrei usare quello definitivo come stream
		$temp = tmpfile();
		$realSize = stream_copy_to_stream($input, $temp);
		fclose($input);

		if ($realSize != $this->get_file_size()) return false;

		$target = fopen($path, "w");        
		fseek($temp, 0, SEEK_SET);
		stream_copy_to_stream($temp, $target);
		fclose($target);

		return true;
		}


	protected function get_file_name()
		{
		// Todo: usare il wrapper di Joomla per le get
		return $_GET['qqfile'];
		}


	protected function get_file_size()
		{
		if (isset($_SERVER["CONTENT_LENGTH"])) return (int)$_SERVER["CONTENT_LENGTH"];
		//else throw new Exception('Getting content length is not supported.');
		return 0;
		}

	}


// File uploads via regular form post (uses the $_FILES array)
class FileFormUploadManager extends FUploadManager
{
	public function __construct()
		{
		parent::__construct();
		}


	protected function save_file($path)
		{
		return move_uploaded_file($_FILES['qqfile']['tmp_name'], $path);
		}


	protected function get_file_name()
		{
		return $_FILES['qqfile']['name'];
		}

	protected function get_file_size()
		{
		return $_FILES['qqfile']['size'];
		}

}


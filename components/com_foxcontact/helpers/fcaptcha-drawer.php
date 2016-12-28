<?php

define('_JEXEC', 1 );
define('DS', DIRECTORY_SEPARATOR);
define('JPATH_BASE', realpath(dirname(__FILE__) . DS . '..' . DS . '..' . DS . '..'));
require_once(JPATH_BASE . DS . 'includes' . DS . 'defines.php');
require_once(JPATH_BASE . DS . 'includes' . DS . 'framework.php');

require_once(JPATH_ROOT . DS . 'components' . DS . 'com_foxcontact' . DS . 'helpers' . DS . 'fsession.php');
require_once(JPATH_ROOT . DS . 'components' . DS . 'com_foxcontact' . DS . 'helpers' . DS . 'flogger.php');

// @ avoids Warning: ini_set() has been disabled for security reasons in /var/www/libraries/joomla/[...]
/*$mainframe =&*/ @JFactory::getApplication('site');  // Needed to get the correct session with JFactory::getSession() below
$cid = JRequest::getVar("cid", NULL, 'GET');  // Component id. We need to load parameters for it
//$wholemenu = JSite::getMenu();
$site = new JSite();
// @ avoids Warning: ini_set() has been disabled for security reasons in /var/www/libraries/joomla/[...]
$wholemenu = @$site->getMenu();                                
$cparams = $wholemenu->getParams($cid);  // Component parameters

switch ($cparams->get("stdcaptchatype", ""))
	{
	case 1:
		$Captcha = new FMathCaptchaDrawer($cid, $cparams);
	break;

	default:
		$Captcha = new FStandardCaptchaDrawer($cid, $cparams);
	}

$Captcha->Shuffle();
$Captcha->Draw();


abstract class FCaptchaDrawer
	{
	protected $Cparams;
	protected $Namespace;
	protected $Charset;
	protected $Question;
	protected $Answer;
	protected $Image = array();
	protected $Font = array();
	protected $Background = array();
	protected $Colors = array();
	protected $DebugLog;

	abstract public function Shuffle();

	public function __construct($cid, &$cparams)
		{
		$this->DebugLog = new FDebugLogger("Captcha Drawer");
		$mid = JRequest::getVar("mid", NULL, 'GET');  // Module id. Used in namespace only
		$this->Namespace = "fcaptcha_cid_" . $cid . "_mid_" . $mid;
		$this->Cparams = $cparams;
		$this->LoadParams();
		$this->DebugLog->Write("Namespace: " . $this->Namespace);
		$this->DebugLog->Write("Font: " . print_r($this->Font, true));
		}

	public function Draw()
		{
		$jsession = JFactory::getSession();
		$fsession = new FSession($jsession->getId(), JRequest::getVar("cid", NULL, 'GET'), JRequest::getVar("mid", NULL, 'GET'));
		// Store the answer in the session
		$fsession->Save($this->Answer, "captcha_answer");
		$this->DebugLog->Write("Answer saved into session");

		// White background
		imagefill($this->Image['data'], 0, 0, $this->Colors['Background']);

		$gridsize = intval(($this->Font['min'] + $this->Font['max']) / 2);
		// Vertical lines
		for ($x = $gridsize; $x < $this->Image['width']; $x += $gridsize)
			{
			imageline($this->Image['data'], $x, 0, $x, $this->Image['height'], $this->Colors['Disturb']);
			}
		// Horizintal lines
		for ($y = $gridsize; $y < $this->Image['height']; $y += $gridsize)
			{
			imageline($this->Image['data'], 0, $y, $this->Image['width'], $y, $this->Colors['Disturb']);
			}
		
		$len = strlen($this->Question);
		// Space available for one single char. It is based on image width and number of characters to display
		$space = $this->Image['width'] / $len;

		// Single disturb characters rendering. Doubles the characters and halves the space
		for ($p = 0; $p < 2 * $len; ++$p)
			{
			// render a random character from ascii 33 and ascii 126
			$this->Render(chr(rand(33, 126)), $p, $space / 2, $this->Colors['Disturb']);
			}

		// Single characters rendering
		for ($p = 0; $p < $len; ++$p)
			{
			$this->Render($this->Question[$p], $p, $space, $this->Colors['Text']);
			}
		
		$this->DebugLog->Write("Render finished");

		// Prepare some useful headers
		header("Expires: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
		header("Cache-Control: no-store, no-cache, must-revalidate");  // must not be cached by the client browser or any proxy
		header("Cache-Control: post-check=0, pre-check=0", false); 
		header("Pragma: no-cache");
		header("Content-type:image/jpeg");
		header("Content-Disposition:inline ; filename=fcaptcha.jpg");
		// Send the stream to the client browser
		imagejpeg($this->Image['data']);
		imagedestroy($this->Image['data']);
		
		$this->DebugLog->Write("Image sent to client");
		}


	private function Render($character, $position, $space, $color)
		{
		imagettftext(
			$this->Image['data'],
			rand($this->Font['min'], $this->Font['max']),
			rand( -$this->Font['angle'], $this->Font['angle']),
			rand($position * $space + $this->Font['min'], (($position + 1 ) * $space) - $this->Font['max']),
			rand($this->Font['max'], $this->Image['height'] - $this->Font['max']),
			$color,
			$this->Font['family'],
			$character);
		}


	private function LoadParams()
		{
		// Load font
		$this->Font['min'] = $this->Cparams->get("stdcaptchafontmin", "14");
		$this->Font['max'] = $this->Cparams->get("stdcaptchafontmax", "20");
		$this->Font['angle'] = $this->Cparams->get("stdcaptchaangle", "20");

		$fontdir = JPATH_SITE . DS . "media" . DS . "com_foxcontact" . DS . "fonts" . DS;
		$fontname = $this->Cparams->get("stdcaptchafont", "-1");
		// "-1" means no selection. 
		if ($fontname == "-1")
			{
			// Choose a random font
			jimport("joomla.filesystem.folder");
			$fonts = JFolder::files($fontdir);
			$fontname = $fonts[rand(0, count($fonts) - 1)];
			}
		$this->Font['family'] = $fontdir . $fontname;

		// Load image parameters
		$this->Image['width'] = $this->Cparams->get("stdcaptchawidth", "150");
		$this->Image['height'] = $this->Cparams->get("stdcaptchaheight", "75");
		// Create image
		$this->Image['data'] = imagecreate($this->Image['width'], $this->Image['height']);
		// Create random colors
		$this->Colors['Background'] = imagecolorallocate($this->Image['data'], 255, 255, 255);
		$this->Colors['Text'] = imagecolorallocate($this->Image['data'], rand(0, 50), rand(0, 50), rand(0, 50));  // Average value: 25
		$this->Colors['Disturb'] = imagecolorallocate($this->Image['data'], rand(180, 220), rand(180, 220), rand(180, 220));  // average value: 200
		}

	}


class FMathCaptchaDrawer extends FCaptchaDrawer
	{
	public function __construct($cid, &$cparams)
		{
		parent::__construct($cid, $cparams);
		// We need 2 random numbers and one operator between them		
		$this->Charset = "+-*";  // Operators
		}

	public function Shuffle()
		{
		// To avoid negative results, the second number is lower than the first
		$this->Question = rand(6, 11) . substr(str_shuffle($this->Charset), 0, 1) . rand(1, 5);  // Question as a string
		// Find the result and store it on $result
		eval("\$this->Answer = strval(" . $this->Question . ");");  // Answer as a string
		}
	}


class FStandardCaptchaDrawer extends FCaptchaDrawer
	{
	public function __construct($cid, &$cparams)
		{
		parent::__construct($cid, $cparams);
		// Define charset. No need to removes some similar chars due to FCaptcha::FaultTolerance()
		// I 1 l, O o 0, g q 9, these depends on font: (S s 5, B 8, G 6)
		//$this->Charset = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefhijkmnprstuvwxyz2345678";
		$this->Charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789";
		}

	public function Shuffle()
		{
		$this->Question = $this->Answer = substr(str_shuffle($this->Charset), 0, 5);
		}
	}

?>

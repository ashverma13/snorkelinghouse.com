<?php
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_ROOT . DS . 'components' . DS . 'com_foxcontact' . DS . 'helpers' . DS . 'flogger.php');
require_once(JPATH_ROOT . DS . 'components' . DS . 'com_foxcontact' . DS . 'helpers' . DS . 'fmimetype.php');

jimport('joomla.installer.installer');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

/*$mainframe =&*/ JFactory::getApplication('site');  // Needed to get the correct session with JFactory::getSession() below

function testfunction($function, &$log)
	{
	$result = function_exists($function);
	$log->Write("testing function [$function]... [" . intval($result) . "]");
	return $result;
	}


function set_permissions($filename, $permissions, &$log)
	{
	jimport("joomla.client.helper");
	$ftp_config = JClientHelper::getCredentials('ftp');

	if ($ftp_config['enabled'])
		{
		jimport("joomla.client.ftp");
		jimport("joomla.filesystem.path");
		$jpath_root = JPATH_ROOT;
		$filename = JPath::clean(str_replace(JPATH_ROOT, $ftp_config['root'], $filename), '/');
		$ftp = new JFTP($ftp_config);
		$result = intval($ftp->chmod($filename, $permissions));
		}
	else
		{
		$result = intval(@chmod($filename, $permissions));
		}

	$log->Write("setting permissions for [$filename]... [$result]");
	return $result;
	}

$db = JFactory::getDBO();

$db->setQuery("DROP TABLE IF EXISTS `#__fcf_sessions`;");
$db->query();

$sql = <<< EOT
CREATE TABLE IF NOT EXISTS `#__fcf_sessions` (
  `id` varchar(32) NOT NULL,
  `cid` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `keyword` varchar(32) NOT NULL,
  `birth` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data` text,
  UNIQUE KEY `index` (`id`,`cid`,`mid`,`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
EOT;
$db->setQuery($sql);
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__fcf_settings`;");
$db->query();

$sql = <<< EOT
CREATE TABLE IF NOT EXISTS `#__fcf_settings` (
  `name` varchar(32) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
EOT;
$db->setQuery($sql);
$db->query();

$InstallLog = new FLogger("installscript", "install");

// o.s. version and safe mode
$InstallLog->Write("Installing on php " . PHP_VERSION . " | " . PHP_OS . " | safe_mode: " . intval(ini_get("safe_mode")) . " | interface: " . php_sapi_name());

// mime type support
$mimetype = new FMimeType();
$mimetype->CheckEnvironment();

$InstallLog->Write("--- Determining if this system is able to draw captcha images ---");
// gd support
if (extension_loaded("gd") && function_exists("gd_info"))
	{
	$gdinfo = gd_info();
	foreach ($gdinfo as $key => $line) $InstallLog->Write($key . "... [" . $line . "]");

	$InstallLog->Write("gd extension found. Let's see if it works.");
	$result = true;
	$result &= testfunction("imagecreate", $InstallLog);
	$result &= testfunction("imagecolorallocate", $InstallLog);
	$result &= testfunction("imagefill", $InstallLog);
	$result &= testfunction("imageline", $InstallLog);
	$result &= testfunction("imagettftext", $InstallLog);
	$result &= testfunction("imagejpeg", $InstallLog);
	$result &= testfunction("imagedestroy", $InstallLog);
	}
else
	{
	$InstallLog->Write("gd extension not found");
	$result = false;
	}

$value = $result ? "use_gd" : "disabled";
$InstallLog->Write("--- Method choosen to draw captcha images is [$value] ---");

$sql = "INSERT INTO #__fcf_settings (name, value) VALUES ('captchadrawer', '$value');";
$db->setQuery($sql);
$db->query();

// File permission needed in suexec environments
$permissions = fileperms(JPATH_ADMINISTRATOR . DS . "index.php");
$lib_dir = JPATH_ROOT . DS . 'components' . DS . 'com_foxcontact' . DS . 'helpers' . DS;
$buffer = sprintf("Determining correct file permissions...  [%o]", $permissions);
$InstallLog->Write($buffer);
if ($permissions)
	{
	set_permissions($lib_dir . "debug-on.php", $permissions, $InstallLog);
	set_permissions($lib_dir . "debug-off.php", $permissions, $InstallLog);
	set_permissions($lib_dir . "fcaptcha-drawer.php", $permissions, $InstallLog);
	set_permissions($lib_dir . "qqfileuploader.php", $permissions, $InstallLog);
	}

// Directory permission needed in suexec environments
$permissions = fileperms(JPATH_ADMINISTRATOR);
$buffer = sprintf("Determining correct directory permissions...  [%o]", $permissions);
$InstallLog->Write($buffer);
if ($permissions)
	{
	set_permissions(JPATH_ROOT . DS . "components", $permissions, $InstallLog);
	set_permissions(JPATH_ROOT . DS . "components" . DS . "com_foxcontact", $permissions, $InstallLog);
	set_permissions(JPATH_ROOT . DS . "components" . DS . "com_foxcontact" . DS . "helpers", $permissions, $InstallLog);
	}

// Todo: If we are using FTP Layer we certainly need to set permissions to upload directory too.
// ...

$direction = intval(JFactory::getLanguage()->get('rtl', 0));
$left  = $direction ? "right" : "left";
$right = $direction ? "left" : "right";

?>

<img src="http://www.fox.ra.it/images/fox-contact-form-logo.php" alt="Fox Contact Form Logo" style="float:<?php echo($left); ?>;margin-<?php echo($right); ?>:15px;" width="110" height="82" />
<h2>Fox Contact Form</h2>
<p>Component installed successful. Now go to Menu Manager and create a new menu item, then select 'Fox Contact' as menu type.</p>

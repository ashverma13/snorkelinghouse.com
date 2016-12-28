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
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');
require_once(JPATH_BASE . DS . 'components' . DS . 'com_foxcontact' . DS . 'helpers' . DS . 'fieldsbuilder.php');
require_once(JPATH_BASE . DS . 'components' . DS . 'com_foxcontact' . DS . 'helpers' . DS . 'fsubmitter.php');
require_once(JPATH_BASE . DS . 'components' . DS . 'com_foxcontact' . DS . 'helpers' . DS . 'fsession.php');
require_once(JPATH_BASE . DS . 'components' . DS . 'com_foxcontact' . DS . 'helpers' . DS . 'functions.php');

// $params is a global variable not defined here. I can use it even in tmpl/default.php
// The helper is a class, so I have to pass che $params as a function parameter. For reference.
// Use: $params->get('name', 0)

// Validation of menu_item. It must be a number, and not empty
if (!is_numeric($targetmenu_id = $params->get('menu_item')))
	{
	echo(JText::_('MOD_VFC_MESSAGE_WRONG_MENUITEM'));
	return; // stop the execution
	}

jimport( 'joomla.application.menu' );
$site = new JSite();
$wholemenu = $site->getMenu();
// We're looking for parameters of the target menu
$cparams = $wholemenu->getParams($targetmenu_id);  // Component parameters

//$test = modFoxContactHelper::getHello($params);
$FieldsBuilder = new FieldsBuilder($cparams, $params, true);

$document = JFactory::getDocument();
// Add a javascript
//$js = JURI::base().'modules/mod_foxcontact/js/custom.js';
//$document->addScript($js);

// Add a javascript from source code
//$js = "source of javascript;";
//$document->addScriptDeclaration($js);

// Add a stylesheet
$css = JURI::base().'components/com_foxcontact/css/neon.css';
$document->addStyleSheet($css);

// Add a style from source code
//$csscode = Helper::getStyle($params,$module->id);
//$document->addStyleDeclaration($csscode);

// Get the menu instance by id
$targetmenu = $wholemenu->getItem($targetmenu_id);

// If target page is the wrong type, this module can't work. Parameters for fields will not match.
if (!isset($targetmenu->component) || $targetmenu->component != 'com_foxcontact')
	{
	echo(JText::_('MOD_VFC_MESSAGE_WRONG_MENUITEM'));
	return;
	}

// If this page is the component page, it's better don't show the module.
$activemenu = $wholemenu->getActive();
// By exiting here, the module disappears completely
// only in debug environment it's alowed to display module and component in the same page
$application = JFactory::getApplication();
//if ($activemenu->id == $targetmenu->id) return;
if ($activemenu->component == 'com_foxcontact' && !$application->getCfg("debug")) return;

/*
// Get target link
$link = $targetmenu->link;

// Build it with the correct id
$router = JSite::getRouter();                                                
if ($router->getMode() == JROUTER_MODE_SEF) $link = 'index.php?Itemid=' . $targetmenu_id;
else $link .= '&Itemid=' . $targetmenu_id;

// Finally translate it in a SEF one if needed   
$link = JRoute::_($link);
*/
$link = FGetLink($targetmenu_id);

// Load language
$lang = JFactory::getLanguage();
// Load component language too
$lang->load('com_foxcontact');

// Fields properties
$captcha['show'] = (bool)$cparams->get("stdcaptchadisplay");

// Load into <head> needed js only once and only if captcha feature is enabled
if (!isset($GLOBALS[$module->module . '_captcha_js_loaded']) && $captcha['show'])
	{
	$FieldsBuilder->js_load("fcaptcha.js", 1, 0);
	$GLOBALS[$module->module . '_captcha_js_loaded'] = true;
	}

// $captcha['label'] = $cparams->get("stdcaptcha");
$captcha['src'] = JURI::base(true) . '/components/com_foxcontact/helpers/fcaptcha-drawer.php?cid=' . $targetmenu_id . '&mid=' . $module->id;
$captcha['transparent'] = JURI::base(true) . '/media/com_foxcontact/images/transparent.gif';
//$captcha['id'] = 'fcaptcha_mid_' . $module->id;
$captcha['width'] = $cparams->get("stdcaptchawidth", "");
$captcha['height'] = $cparams->get("stdcaptchaheight", "");

$upload['show'] = (bool)$cparams->get("uploaddisplay");
$direction = intval(JFactory::getLanguage()->get('rtl', 0));
$style['float'] = $direction ? "right" : "left";

// Load into <head> needed js only once and only if upload feature is enabled
if (!isset($GLOBALS[$module->module . '_upload_js_loaded']) && $upload['show'])
	{
	$placeholders = $values = array();
	$placeholders[] = '{%COM_FCF_BROWSE_FILES%}';
	$placeholders[] = '{%FLOAT%}';
	$placeholders[] = '{%JCANCEL%}';
	$placeholders[] = '{%COM_FCF_FAILED%}';
	$placeholders[] = '{%COM_FCF_SUCCESS%}';
	$placeholders[] = '{%Action%}';
	$values[] = JTEXT::_('COM_FCF_BROWSE_FILES');
	$values[] = $style['float'];
	$values[] = JTEXT::_('JCANCEL');
	$values[] = JTEXT::_('COM_FCF_FAILED');
	$values[] = JTEXT::_('COM_FCF_SUCCESS');
	$values[] = JURI::base(true) . DS . 'components' . DS . 'com_foxcontact' . DS .'helpers' . DS . 'qqfileuploader.php';
	$FieldsBuilder->js_load("fileuploader-min.js", 1, 0, $placeholders, $values);
	$GLOBALS[$module->module . '_upload_js_loaded'] = true;
	}
$upload['label'] = $cparams->get("upload");
$jsession = JFactory::getSession();
$fsession = new FSession($jsession->getId(), $targetmenu_id, 0);
$data = $fsession->Load('filelist');  // Read the list from the session
if ($data) $upload['filelist'] = explode("|", $data);
else $upload['filelist'] = array();

if (intval($params->get("top_textdisplay", "0"))) $toptext = $cparams->get("top_text", "");	
else $toptext = "";
if (!empty($toptext)) $toptext = '<div class="foxmessage" style="clear:both;">' . $toptext . '</div>';

if (intval($params->get("bottom_textdisplay", "0"))) $bottomtext = $cparams->get("bottom_text", "");	
else $bottomtext = "";
if (!empty($bottomtext)) $bottomtext = '<div class="foxmessage" style="clear:both;">' . $bottomtext . '</div>';

// Module xml
$xml = JApplicationHelper::parseXMLInstallFile(JPATH_SITE . DS . 'modules' . DS . 'mod_foxcontact' . DS . 'mod_foxcontact.xml');

require(JModuleHelper::getLayoutPath('mod_foxcontact', $params->get('layout', 'default')));


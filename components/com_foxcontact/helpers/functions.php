<?php
/*
This file is part of "Fox Contact Form", a free Joomla! 1.6 Contact Form
You can redistribute it and/or modify it under the terms of the GNU General Public License
GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
Author: Demis Palma
Documentation at http://www.fox.ra.it/joomla-extensions/fox-contact-form.html
Copyright: 2011 Demis Palma
*/

// Returns a full http link
// pointing to the page identified by $menu_id or
// pointing to the active page if $menu_id is omitted
function FGetLink($menu_id = NULL)
	{
	// Avoid a static call
	// $wholemenu =& JSite::getMenu();

	$site = new JSite();
	$wholemenu = $site->getMenu();
	if ($menu_id) $targetmenu = $wholemenu->getItem($menu_id);
	else $targetmenu = $wholemenu->getActive();

	// It can happen when $menu_id is a deleted, unpublished or trashed menu item
	if (!is_object($targetmenu)) return NULL;

	// Get target link
	$link = $targetmenu->link;

	// Build it with the correct id
	$router = JSite::getRouter();

	if ($router->getMode() == JROUTER_MODE_SEF) $link = 'index.php?Itemid=' . $targetmenu->id;
	else $link .= '&Itemid=' . $targetmenu->id;

	if (!$menu_id) $link = JFactory::getURI()->toString();

	// Finally translate it in a SEF one if needed
	return JRoute::_($link);
	}


function GetHelpLink($msg)
	{
	$link = array();

	$lang = JFactory::getLanguage();
	// See the documentation string
	$lang->load('com_foxcontact.sys', JPATH_ADMINISTRATOR);

	// User guide link map

	// phpmailer
/*
["provide_address"] = JText::_('PHPMAILER_PROVIDE_ADDRESS');
["mailer_not_supported"] = JText::_('PHPMAILER_MAILER_IS_NOT_SUPPORTED');
*/
	// Todo: this can't work since sendmail name is added: "No se puede ejecutar: /var/qmail/bin/sendmail"
	//$link[JText::_('PHPMAILER_EXECUTE')] = '13-submissions-via-email/449-could-not-execute-sendmail.html';
	$link[JText::_('PHPMAILER_INSTANTIATE')] = '13-submissions-via-email/443-could-not-instantiate-mail-function.html';

	$link[JText::_('PHPMAILER_AUTHENTICATE')] = '13-submissions-via-email.html';
/*
["from_failed"] = JText::_('PHPMAILER_FROM_FAILED');
["recipients_failed"] = JText::_('PHPMAILER_RECIPIENTS_FAILED');
["data_not_accepted"] = JText::_('PHPMAILER_DATA_NOT_ACCEPTED');
*/
	$link[JText::_('PHPMAILER_CONNECT_HOST')] = '13-submissions-via-email.html';
/*
["file_access"] = JText::_('PHPMAILER_FILE_ACCESS');
["file_open"] = JText::_('PHPMAILER_FILE_OPEN');
["encoding"] = JText::_('PHPMAILER_ENCODING');
["signing"]  = JText::_('PHPMAILER_SIGNING_ERROR');
*/

	// Additional joomla errors	
	$link[JText::_('JLIB_MAIL_FUNCTION_DISABLED')] = '13-submissions-via-email.html';  // libraries/joomla/mail/mail.php | en-GB.lib_joomla.ini | "The mail() function has been disabled and the mail cannot be sent."

	$baseurl = "http://www.fox.ra.it/forum/";
	$index = isset($link[$msg]) ? $link[$msg] : "2-documentation.html";
	return '<a href="' . $baseurl . $index . '" target="_blank">' .
		$lang->_("COM_FCF_DOCUMENTATION") .
		"</a>";
	}

?>

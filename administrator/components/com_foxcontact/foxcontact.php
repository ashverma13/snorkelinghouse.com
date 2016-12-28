<?php
/*
This file is part of "Fox Contact Form", a free Joomla! 1.6 Contact Form
You can redistribute it and/or modify it under the terms of the GNU General Public License
GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
Author: Demis Palma
Documentation at http://www.fox.ra.it/joomla-extensions/fox-contact-form.html
Copyright: 2011 Demis Palma
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$lang = JFactory::getLanguage();
$lang->load('com_foxcontact.sys');
$freesoftware = sprintf($lang->_('JGLOBAL_ISFREESOFTWARE'), $lang->_('COM_FOXCONTACT'));
$forum = "http://www.fox.ra.it/forum/1-fox-contact-form.html";
$review = sprintf( $lang->_('COM_FCF_REVIEW'), '<a href="http://extensions.joomla.org/extensions/contacts-and-feedback/contact-forms/16171" target="_blank">', '</a>', "<a href=\"$forum\" target=\"_blank\">", '</a>' );
$direction = intval(JFactory::getLanguage()->get('rtl', 0));
$left  = $direction ? "right" : "left";
$right = $direction ? "left" : "right";

$translator_name = $lang->_('COM_FCF_TRANSLATOR_NAME');
$translator_url = $lang->_('COM_FCF_TRANSLATOR_PERSONAL_URL');
if (!file_exists(JPATH_ADMINISTRATOR . DS . "language" . DS . $lang->get("tag") . DS . $lang->get("tag") . ".com_foxcontact.ini"))
	{
	// Translation needed
	$translator_name = "<blink>" . $lang->get("name") . " translation is missing.</blink> Please contribute writing this translation. It's easy. Click to see how.";
	$translator_url = "http://www.fox.ra.it/joomla-extensions/fox-contact-form.html#support";
	}
?>

<p><img src="../media/com_foxcontact/images/fcf-logo.png" style="float:<?php echo($left); ?>;margin-<?php echo($right); ?>:16px;"></p>
<div style="width:400px;float:<?php echo($right); ?>;margin-<?php echo($left); ?>:16px;border:1px solid #cccccc;background:#ffffff;padding:16px">
	<p><?php echo('<a href="' . $translator_url . '" target="_blank">' . $translator_name . '</a>'); ?></p>
	<p><?php echo($lang->_('COM_FCF_LONGDESCRIPTION')); ?></p>
</div>
<h2><?php echo($lang->_('COM_FOXCONTACT')); ?></h2>
<p><b><?php echo($lang->_('COM_FCF_GO_TO_MENU_MANAGER')); ?></b></p>

<p>
<a href="http://www.fox.ra.it/downloads/category/1-fox-contact-form.html" target="_blank"><?php echo($lang->_('COM_FCF_DOWNLOAD')); ?></a> |
<a href="http://www.fox.ra.it/forum/2-documentation.html" target="_blank"><?php echo($lang->_('COM_FCF_DOCUMENTATION')); ?></a> |
<a href="<?php echo($forum); ?>" target="_blank"><?php echo($lang->_('COM_FCF_FORUM')); ?></a>
</p>
<p><?php echo($freesoftware); ?></p>

<div style="float:<?php echo($right); ?>;margin-<?php echo($left); ?>:16px;">
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="MWFY8BEEDHNRY">
<input type="image" src="../media/com_foxcontact/images/donate.jpg" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypalobjects.com/WEBSCR-640-20110306-1/it_IT/i/scr/pixel.gif" width="1" height="1">
</form>
</div>
<p><?php echo($review); ?></p>

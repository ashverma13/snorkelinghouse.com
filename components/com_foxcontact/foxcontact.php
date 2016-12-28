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

$document = JFactory::getDocument();

// Add a stylesheet
$css = JURI::base() . 'components/com_foxcontact/css/neon.css';
$document->addStyleSheet($css);

$direction = intval(JFactory::getLanguage()->get('rtl', 0));
$float = $direction ? "left" : "right";

// import joomla controller library
jimport('joomla.application.component.controller');

// Get an instance of the controller prefixed by FoxContact
$controller = JController::getInstance('FoxContact');

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
   
// Redirect if set by the controller
$controller->redirect();
?>
<div class="foxpowered" style="float:<?php echo($float); ?>;"><a href="http://www.fox.ra.it/" title="Joomla contact form" target="_blank"><?php echo(strtolower(JText::_('POWERED_BY')));?> fox contact</a></div>

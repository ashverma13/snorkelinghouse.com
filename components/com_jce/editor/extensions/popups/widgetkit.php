<?php
/**
* @package      JCE Popups
* @copyright    Copyright (C) 2006 - 2011 Ryan Demmer. All rights reserved
* @author		Ryan Demmer
* @license      GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
* JCE is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
*/
defined( '_WF_EXT' ) or die('RESTRICTED');

class WFPopupsExtension_Widgetkit extends JObject
{	
	/**
	* Constructor activating the default information of the class
	*
	* @access	protected
	*/
	function __construct($options = array())
	{		
		if (self::isEnabled()) {
			$scripts = array();
			
			$document = WFDocument::getInstance();
			
			$document->addScript('widgetkit', 'extensions/popups/widgetkit/js');
			$document->addStyleSheet('widgetkit', 'extensions/popups/widgetkit/css');
		}
	}
	
	function getParams()
	{
		return array();
	}
	
	function isEnabled()
	{		
		$wf = WFEditorPlugin::getInstance();
		
		if (JPluginHelper::isEnabled('system', 'widgetkit_system') && $wf->getParam('popups.widgetkit.enable', 1) == 1) {
			return true;
		}
		
		return false;
	}
}
?>
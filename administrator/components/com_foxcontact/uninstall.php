<?php defined('_JEXEC') or die('Restricted access');

$db = JFactory::getDBO();

$db->setQuery("DROP TABLE IF EXISTS `#__fcf_sessions`;");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__fcf_settings`;");
$db->query();

?>

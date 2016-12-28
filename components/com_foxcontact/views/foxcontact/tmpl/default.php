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
?>

<?php
// Set Meta Description
if ($this->cparams->get('menu-meta_description'))
   $this->document->setDescription($this->cparams->get('menu-meta_description'));
// Set Meta Keywords
if ($this->cparams->get('menu-meta_keywords'))
   $this->document->setMetadata('keywords', $this->cparams->get('menu-meta_keywords'));
   $this->setMetadata('keywords', $this->cparams->get('menu-meta_keywords'));

// Page Heading if needed
if ($this->cparams->get('show_page_heading'))
	echo("<h1>" . $this->escape($this->cparams->get('page_heading')) . "</h1>" . PHP_EOL);

// Page Subheading if needed
$page_subheading = $this->cparams->get("page_subheading", "");
if (!empty($page_subheading))
	echo("<h2>" . $page_subheading . "</h2>" . PHP_EOL);

$xml = JApplicationHelper::parseXMLInstallFile(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_foxcontact' . DS . 'foxcontact.xml');

echo($this->TopText);

echo('<div class="' . $this->cparams->get('pageclass_sfx') . '">');
if ($this->FormText != "") { ?>
<form enctype="multipart/form-data" id="FoxForm" class="foxform" name="emailForm" method="post" action="<?php echo(FGetLink());?>">
<!-- <?php echo($xml['version']); ?> -->
<?php echo($this->FormText); ?>
</form>
<?php
}
echo('</div>');

echo($this->BottomText);

if ($this->application->getCfg("debug"))
   {
   echo($this->Debugger->Dump());
   echo($this->FieldsBuilder->Dump());
   echo($this->FoxCaptcha->Dump());
   }
?>


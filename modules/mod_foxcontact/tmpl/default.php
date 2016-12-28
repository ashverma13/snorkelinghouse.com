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
defined('_JEXEC') or die ('Restricted access'); ?>

<?php echo($toptext); ?>
<form enctype="multipart/form-data" class="foxform" action="<?php echo($link); ?>" method="post">
<!-- <?php echo($xml['version']); ?> -->

	<?php for ($n = 0; $n < 2; ++$n) { ?>
		<div>
			<input class="foxtext" type="text"
			value="<?php echo($FieldsBuilder->Fields['sender' . $n]['Name']); ?>"
			title="<?php echo($FieldsBuilder->Fields['sender' . $n]['Name']); ?>"
			name="<?php echo($FieldsBuilder->Fields['sender' . $n]['PostName']); ?>"
			style="width:<?php echo($FieldsBuilder->Fields['sender' . $n]['Width'] . $FieldsBuilder->Fields['sender' . $n]['Unit']); ?> !important;"
			onFocus="if(this.value==this.title) this.value='';" onBlur="if(this.value=='') this.value=this.title;" />
		</div>
	<?php } ?>


	<?php for ($n = 0; $n < 10; ++$n) {
		if (!isset($FieldsBuilder->Fields['text' . $n]) || !$FieldsBuilder->Fields['text' . $n]['Display']) continue; ?>
		<div>
			<input class="foxtext" type="<?php echo($FieldsBuilder->Fields['text' . $n]['Type']); ?>"
			value="<?php echo($FieldsBuilder->Fields['text' . $n]['Name']); ?>"
			title="<?php echo($FieldsBuilder->Fields['text' . $n]['Name']); ?>"
			name="<?php echo($FieldsBuilder->Fields['text' . $n]['PostName']); ?>"
			style="width:<?php echo($FieldsBuilder->Fields['text' . $n]['Width'] . $FieldsBuilder->Fields['text' . $n]['Unit']); ?> !important;"
			onFocus="if(this.value==this.title) this.value='';" onBlur="if(this.value=='') this.value=this.title;" />
		</div>
	<?php } ?>

	<?php for ($n = 0; $n < 3; ++$n) {
		if (!isset($FieldsBuilder->Fields['dropdown' . $n]) || !$FieldsBuilder->Fields['dropdown' . $n]['Display']) continue; ?>
		<div>
			<select class="foxtext" name="<?php echo($FieldsBuilder->Fields['dropdown' . $n]['PostName']); ?>">
			<option value=""><?php echo($FieldsBuilder->Fields['dropdown' . $n]['Name']); ?></option>
			<?php
				$options = explode(",", $FieldsBuilder->Fields['dropdown' . $n]['Values']);
				for ($o = 0; $o < count($options); ++$o) {
			?>
					<option value=" <?php echo($options[$o]); ?>"><?php echo($options[$o]); ?></option>
			<?php } ?>
			</select>
		</div>
	<?php } ?>

	<?php for ($n = 0; $n < 3; ++$n) {
		if (!isset($FieldsBuilder->Fields['textarea' . $n]) || !$FieldsBuilder->Fields['textarea' . $n]['Display']) continue; ?>
		<div>
			<textarea class="foxtext"
			rows="" cols=""
			name="<?php echo($FieldsBuilder->Fields['textarea' . $n]['PostName']); ?>"
			title="<?php echo($FieldsBuilder->Fields['textarea' . $n]['Name']); ?>"
			style="width:<?php echo($FieldsBuilder->Fields['textarea' . $n]['Width'] . $FieldsBuilder->Fields['textarea' . $n]['Unit']); ?> !important;height:<?php echo($FieldsBuilder->Fields['textarea' . $n]['Height'] . 'px'); ?> !important;"
			onFocus="if(this.value==this.title) this.value='';" onBlur="if(this.value=='') this.value=this.title;"
			><?php echo($FieldsBuilder->Fields['textarea' . $n]['Name']); ?></textarea>
		</div>
	<?php } ?>

	<?php for ($n = 0; $n < 5; ++$n) {
		if (!isset($FieldsBuilder->Fields['checkbox' . $n]) || !$FieldsBuilder->Fields['checkbox' . $n]['Display']) continue; ?>
		<div>
			<div class="foxcheckbox" style="float:<?php echo($style['float']); ?>;margin:0px 10px;margin-<?php echo($style['float']); ?>:0;padding:0;">
				<input type="checkbox" value="<?php echo(JText::_('JYES')) ?>"			
				name="<?php echo($FieldsBuilder->Fields['checkbox' . $n]['PostName']); ?>" />
			</div>
			<?php echo($FieldsBuilder->Fields['checkbox' . $n]['Name']); ?>
		</div>
	<?php } ?>


	<?php if ($upload['show']) { ?>	
		<div style="clear:both;">

		<label><?php echo($upload['label']); ?></label><br />
		<div id="foxupload_mid_<?php echo($module->id); ?>"></div>
		<script language="javascript" type="text/javascript">createUploader('foxupload_mid_<?php echo($module->id); ?>', <?php echo($targetmenu_id); ?>, 0);</script>
		<noscript><input type="file" id="foxstdupload" name="foxstdupload" /></noscript>

		<?php if (count($upload['filelist'])) { ?>	
			<div style="clear:both;"><ul class="qq-upload-list">
			<?php foreach ($upload['filelist'] as &$file) { ?>	
				<li class="qq-upload-success" style="background-position:<?php echo($style['float']); ?>;">
				<span class="qq-upload-file" style="float:<?php echo($style['float']); ?>;"><?php echo(substr($file, 14)); ?></span>
				<span class="qq-upload-success-text" style="background-position:<?php echo($style['float']); ?>;"><?php echo(JTEXT::_('COM_FCF_SUCCESS')); ?></span>
				</li>
			<?php } ?>
			</ul></div>
		<?php } ?>

		</div>
	<?php } ?>


	<?php if ($captcha['show']) { ?>	
	<div style="clear:both;">
		<div class="fcaptchacontainer">

			<div class="fcaptchafieldcontainer">
				<img src="<?php echo($captcha['src']); ?>" alt="captcha" id="fcaptcha_mid_<?php echo($module->id); ?>" width="<?php echo($captcha['width']); ?>" height="<?php echo($captcha['height']); ?>"/>
			</div>

			<div class="fcaptchafieldcontainer">
				<img src="<?php echo($captcha['transparent']); ?>"
					id="reloadbtn_mid_<?php echo($module->id); ?>"
					alt="<?php echo($lang->_('COM_FCF_RELOAD_ALT')); ?>"
					title="<?php echo($lang->_('COM_FCF_RELOAD_TITLE')); ?>"
					onclick="javascript:ReloadFCaptcha('fcaptcha_mid_<?php echo($module->id); ?>')" />
				<script language="javascript" type="text/javascript">BuildReloadButton('reloadbtn_mid_<?php echo($module->id); ?>')</script>
			</div>

			<div class="fcaptchainputcontainer">
				<input class="foxtext" type="text" name="fcaptcha" style="width:<?php echo($captcha['width']); ?>px !important;" />
			</div>

			<input type="hidden" name="mid" value="<?php echo($module->id); ?>" />
		</div>
	</div>
	<?php } ?>

	<button class="foxbutton" type="submit"><?php echo(JText::_('JSUBMIT')); ?></button>
</form>
<?php echo($bottomtext); ?>

<?php
// Debug
$application = JFactory::getApplication();
if ($application->getCfg("debug")) echo($FieldsBuilder->Dump());
?>


<?php
defined('JPATH_BASE') or die;
jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldVFHeader extends JFormField
	{
	protected $type = 'VFHeader';
	
	protected function getInput()
		{
		return '';
		}
	
	protected function getLabel()
		{
		$direction = intval(JFactory::getLanguage()->get('rtl', 0));
		$float = $direction ? "right" : "left";

		echo '<div class="clr"></div>';		
		$icon	= (string)$this->element['icon'];		
		$image = '';		
		if ($icon != '')
			{
			//$image 	= JHTML::_('image', 'media/com_foxcontact/images/'. $icon, '' );
			$image = '<img style="margin:0; float:' . $float . ';" src="' . JURI::base() . '../media/com_foxcontact/images/' . $icon . '">';
			}

		$style = 'background:#f4f4f4; color:#025a8d; border:1px solid silver; padding:5px; margin:5px 0;';
		if ($this->element['default'])
			{		
			return '<div style="' . $style . '">' .
			$image .
			'<span style="padding-' . $float . ':5px; font-weight:bold; line-height:16px;">' .
			JText::_($this->element['default']) .
			'</span>' .
			'</div>';
			}
		else
			{
			return parent::getLabel();
			}

		echo '<div class="clr"></div>';
		}
	}
?>

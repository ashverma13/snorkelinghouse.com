<?php
//@version		$Id: textarea.php 20196 2011-01-09 02:40:25Z ian $
defined('JPATH_BASE') or die;
jimport('joomla.form.formfield');

class JFormFieldVFTextarea extends JFormField
{
	protected $type = 'VFTextarea';

	protected function getInput()
	{
		// Initialize some field attributes.
		$class		= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$disabled	= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$columns	= $this->element['cols'] ? ' cols="'.(int) $this->element['cols'].'"' : '';
		$rows		= $this->element['rows'] ? ' rows="'.(int) $this->element['rows'].'"' : '';

		// Initialize JavaScript field attributes.
		$onchange	= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';

		// If the menu item is just created, replace empty value with the wizard one
		//if (!(bool)$this->form->getValue('id') && !$this->value) $this->value = JText::_((string)$this->element['wizard']);

		// If the menu item is just created, replace whatever value with the wizard one
		if (!(bool)$this->form->getValue('id')) $this->value = JText::_((string)$this->element['wizard']);

		return '<textarea name="'.$this->name.'" id="'.$this->id.'"' .
				$columns.$rows.$class.$disabled.$onchange.'>' .
				htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') .
				'</textarea>';
	}
}

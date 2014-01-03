<?php

class Core_View_Helper_FormRow extends Zend_View_Helper_Abstract
{
	/**
	* Gibt eine Formular-Zeile aus
	* 
	* @param Zend_Form_Element $element
	* @param int $leftSize
	* @param int $rightSize
	* @param array $options
	* 
	* @return string
	*/
	public function formRow($element, $leftSize, $rightSize, $options = array())
	{
		$s = '
			<div class="form-group">
				<label for="' . $element->getId() . '" class="col-sm-' . $leftSize . ' control-label">';
		$s .= $element->getLabel();
		if ($element->isRequired()) {
			$s .= '<span class="mandatory">*</span>';
		}
		$s .= '</label>
				<div class="col-sm-' . $rightSize . '">';
		$s .= $element;
		if (isset($options['helpText']) && $options['helpText']) {
			$s .= '<p class="help-block">' . $options['helpText'] . '</p>';
		}
		$s .= '
				</div>
			</div>';

		return $s;
	}	
}

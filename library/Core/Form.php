<?php

class Core_Form extends Zend_Form
{
	public function __construct($options = null)
	{
		parent::__construct($options);
		
		// Alle Felder haben nur den Error-Decorator
		$this->setElementDecorators(array('ViewHelper', 'Errors'));
	}
	
	public function addElement($element, $name = null, $options = null)
	{
		parent::addElement($element, $name, $options);
		
		$element = $element instanceof Zend_Form_Element ? $element : $this->$name;
		
		if (!$element->getFilters()) {
			$element->setFilters(array('StripTags', 'StringTrim'));
		}
		
		if ($element->getType() != 'Zend_Form_Element_Checkbox') {
			$cssClass = $element->getAttrib('class');
			$element->setAttrib('class', trim($cssClass . ' form-control'));
		}
		
		return $this;
	}
	
}
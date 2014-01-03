<?php

class Core_View_Helper_InputWysiwyg extends Zend_View_Helper_Abstract
{
	protected $isTinyInitialized = false; 
	
	public function inputWysiwyg($name, $value, $id, $options = array())
	{
		if (!$this->isTinyInitialized) {
			$this->view->headScript()->appendFile('//tinymce.cachefly.net/4.0/tinymce.min.js');
			$this->view->headScript()->appendFile('//tinymce.cachefly.net/4.0/jquery.tinymce.min.js');
			$this->isTinyInitialized = true;
		}
		
		if (!isset($options['class'])) {
			$options['class'] = '';
		}
		$options['class'] .= ' mli';
		
		$s = '';
		$s .= '<textarea name="' . $name . '" id="' . $id . '"';
		foreach ($options as $name => $val) {
			$s .= ' ' . $name . '="' . $this->view->escape($val) . '"';
		}
		$s .= '>' . $this->view->escape($value) . '</textarea>';
		
		
		$this->view->headScript()->captureStart('APPEND');
		?>
		$(function() {
			$('#<?php echo $id; ?>').tinymce({
				relative_urls:   false,
				entity_encoding: 'raw',
				plugins:         'charmap code contextmenu image link paste searchreplace table visualchars',
			});
		});
		<?php
		$this->view->headScript()->captureEnd();
		
		return $s;
	}
	
}
<?php

class Core_View_Helper_Url extends Zend_View_Helper_Url
{
	public function url($urlOptions = array(), $name = '', $reset = false, $encode = true)
	{
		if (!array_key_exists('lang', $urlOptions)) {
			$urlOptions['lang'] = Zend_Registry::get('Zend_Locale')->toString();
		}
		if (!array_key_exists('q', $urlOptions)) {
			$urlOptions['q'] = '';
		}
		
		return parent::url($urlOptions, $name, $reset, $encode);
	}
}
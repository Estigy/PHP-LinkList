<?php

class Core_View_Helper_Url extends Zend_View_Helper_Url
{
	public function url(array $urlOptions = array(), $name = null, $reset = false, $encode = true)
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
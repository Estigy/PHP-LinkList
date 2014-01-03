<?php

class Core_Service_Meta
{
	protected $defaultTitle;
	protected $title;

	protected $defaultDescription;
	protected $description;
	
	protected $defaultKeywords;
	protected $keywords;
	
	public static function getFromRegistry()
	{
		if (!Zend_Registry::isRegistered('Core_Service_Meta')) {
			Zend_Registry::set('Core_Service_Meta', new Core_Service_Meta());
		}
		return Zend_Registry::get('Core_Service_Meta');
	}
	
	public function __construct()
	{
		$this->defaultTitle       = array();
		$this->title              = array();
		$this->defaultDescription = '';
		$this->description        = '';
		$this->defaultKeywords    = array();
		$this->keywords           = array();
	}
	
	public function setDefaultTitlePart($part)
	{
		$this->defaultTitle = $part;
	}
	
	public function setDefaultDescription($description)
	{
		$this->defaultDescription = $description;
	}
	
	public function setDefaultKeywords($keywords)
	{
		$this->defaultKeywords = $keywords;
	}
	
	public function addTitlePart($part)
	{
		$this->title[] = $part;
	}
	
	public function getTitle($separator = ' | ')
	{
		return implode($separator, $this->title ?: $this->defaultTitle);
	}
	
	public function setDescription($description)
	{
		$this->description = $description;
	}
	
	public function getDescription()
	{
		return $this->description ?: $this->defaultDescription;
	}
	
	public function addKeywords($keywords)
	{
		if (is_string($keywords)) {
			$keywords = explode(',', $keywords);
			array_walk($keywords, function(&$string, $key) { $string = trim($string); } );
		}
		$this->keywords += $keywords;
	}
	
	public function getKeywords()
	{
		return implode(', ', $this->keywords ?: $this->defaultKeywords);
	}
	
}
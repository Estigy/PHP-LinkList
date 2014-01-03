<?php

class Core_Model
{
	/**
	* Die Eigenschaften des Objekts als Array Schlüssel => Defaultwert.
	* 
	* @var array 
	*/
	protected $properties = array();
	
	public function __construct($data = array()) 
	{
		if (is_object($data)) {
			$data = (array) $data;
		}
		if (!is_array($data)) {
			throw new Exception('The data must be an array or object');
		}
		foreach ($data as $key => $value) {
			$this->$key = $value;
		}
		return $this;
	}
	
	public function getProperties()
	{
		return $this->properties;
	}
	
	protected function getQueryClass()
	{
		$className = get_class($this);
		$className = str_replace('Model_', 'Model_Query_', $className);
		
		return $className;
	}
	
	public function __set($name, $value)
	{
		if (array_key_exists($name, $this->properties)) {
			$this->properties[$name] = $value;
		}
	}
	
	public function __get($name)
	{
		if (array_key_exists($name, $this->properties)) {
			return $this->properties[$name];
		}
		return null;
	}
	
	public function __isset($name)
	{
		return array_key_exists($name, $this->properties);
	}
	
	public function __unset($name)
	{
		if (isset($this->$name)) {
			$this->properties[$name] = null;
		}
	}
	
}
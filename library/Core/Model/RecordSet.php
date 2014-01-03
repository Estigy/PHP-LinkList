<?php

class Core_Model_RecordSet implements Countable, Iterator, ArrayAccess, Zend_Paginator_Adapter_Interface 
{
	/**
	 * @var int
	 */
	private $_iteratorIndex = 0;
	
	/**
	 * @var string
	 */
	protected $_gateway;
	
	/**
	 * @var mixed
	 */
	protected $_results;
	
	public function __construct($results, $gateway) 
	{
		$this->_results = $results;
		$this->_gateway = $gateway;
	}
	
	/*
	 * Implement Countable interface
	 */
	
	public function count() 
	{
		return count($this->_results);
	}
	
	/*
	 * Implement Iterator interface
	 */
	
	public function key() 
	{
		return key($this->_results);	
	}
	
	public function next() 
	{
		$this->_iteratorIndex++;
		return next($this->_results);
	}
	
	public function rewind() 
	{
		$this->_iteratorIndex = 0;
		return reset($this->_results);
	}
	
	public function valid() 
	{
		return $this->_iteratorIndex < $this->count();
	}
	
	public function current() 
	{
		$key = ($this->_results instanceof Iterator) ? $this->_results->key() : key($this->_results);
		$result = $this->_results[$key];
		$result = call_user_func(array($this->_gateway, 'convert'), $result);
		
		return $result;
	}
	
	/*
	 * Implement ArrayAccess interface
	 */
	
	public function offsetExists($key) 
	{
		return array_key_exists($key, $this->_results);
	}
	
	public function offsetGet($key) 
	{
		$result = call_user_func(array($this->_gateway, 'convert'), $this->_results[$key]);
		
        return $result;
    }
    
	public function offsetSet($key, $element)
	{
        $this->_results[$key] = $element;
    }
    
	public function offsetUnset($key) 
	{
        unset($this->_results[$key]);
    }
    
    /*
    * Implement Zend_Paginator_Adapter_Interface
    */
    
    public function getItems($offset, $itemCountPerPage)
    {
		return array_slice($this->_results, $offset, $itemCountPerPage);
    }
}
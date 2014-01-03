<?php

class Core_Utility_PaginatorAdapter extends Zend_Paginator_Adapter_Iterator
{
	public function __construct(Iterator $iterator, $count)
	{
		parent::__construct($iterator);
		$this->_count = $count;
	}
	
	/**
	* Überschreibt die originale getItems() Methode.
	* Da wir immer nur die tatsächlichen Items der Seite in den Adapter hineingeben, brauchen wir $offset
	* und $itemCountPerPage nicht beachten, sondern geben einfach den Iterator selbst zurück. Dadurch stimmen
	* auch die im Paginator berechneten Wert :-)
	* 
	* @param mixed $offset
	* @param mixed $itemCountPerPage
	* @return Iterator
	*/
	public function getItems($offset, $itemCountPerPage)
	{
		return $this->_iterator;
	}
}

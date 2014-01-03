<?php

class Frontend_View_Helper_CategoryUrl extends Zend_View_Helper_Abstract
{
	public function categoryUrl($category)
	{
		/**
		* @todo Kategorie nachladen, wenn nur die ID übergeben wurde
		*/
		return $this->view->url(array('slug' => $category->slug ?: ''), 'linklist_category');
	}
	
}

<?php

class Frontend_View_Helper_LinkUrl extends Zend_View_Helper_Abstract
{
	static $categoryCache = array();
	
	public function linkUrl($link, $category = null)
	{
		/**
		* @todo Link nachladen, wenn nur die ID übergeben wurde
		*/
		
		if ($category == null) {
			if (!isset(self::$categoryCache[$link->category_id])) { 
				self::$categoryCache[$link->category_id] = Model_Query_Category::getById($link->category_id);
			}
			$category = self::$categoryCache[$link->category_id];
		} else {
			self::$categoryCache[$link->category_id] = $category;
		}
		
		return $this->view->url(array('category_slug' => $category->slug, 'link_slug' => $link->slug, 'id' => $link->id), 'linklist_details');
	}

}

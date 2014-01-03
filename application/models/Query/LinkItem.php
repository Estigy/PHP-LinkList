<?php

class Model_Query_LinkItem extends Core_Model_Query
{
	protected static $table        = 'linkitems';
	protected static $defaultOrder = 'title ASC';

	
	public static function getLatest($start, $count)
	{
		$select = static::$db->select()
		                     ->from(static::$table)
		                     ->where('status = ?', Model_LinkItem::STATUS_ONLINE)
		                     ->order('date_entry DESC')
		                     ->limit($count, $start);
		                     
		$rows = static::$db->fetchAll($select);
		
		return new Core_Model_RecordSet($rows, __CLASS__);
	}
	
	protected static function handleExp($exp, Zend_Db_Select $select, $countMode = false)
	{
		foreach ($exp as $clause => $value) {
			switch ($clause) {
				case 'id':
				case 'category_id':
				case 'status':
					$select->where('linkitems.' . $clause . ' = ?', $value);
					break;
				case 'title':
					$select->where('title LIKE ?', '%' . $value . '%');
					break;
				case 'contact_name':
					$select->where('(contact_firstname LIKE ?', '%' . $value . '%')
					       ->orWhere('contact_lastname LIKE ?)', '%' . $value . '%');
					break;
				case 'searchterm':
					$select->where('MATCH (title, description, keywords, url) AGAINST (? IN BOOLEAN MODE)', $value)
					       ->order('MATCH (title, description, keywords, url) AGAINST (' . static::$db->quote($value) . ' IN BOOLEAN MODE)');
					break;
				case 'topViewed':
					$select->join('linkstats', 'linkitems.id = linkstats.linkitem_id', array())
					       ->group('linkitems.id')
					       ->order('SUM(linkstats.views) DESC');
					break;
			}
		}
	}
	
}
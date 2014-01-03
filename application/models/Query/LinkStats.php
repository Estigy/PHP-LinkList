<?php

class Model_Query_LinkStats extends Core_Model_Query
{
	protected static $table        = 'linkstats';
	protected static $defaultOrder = '`date` DESC';

	
	public static function increase($linkitemId, $date = null)
	{
		if ($date === null) {
			$date = date('Y-m-d');
		}
		
		$sql = 'INSERT INTO ' . static::$table . '
				SET `linkitem_id` = ' . static::$db->quote($linkitemId) . ',
				    `date`        = ' . static::$db->quote($date) . ',
				    `views`       = 1
				ON DUPLICATE KEY UPDATE
				    `views` = `views` + 1';
		static::$db->query($sql);
	}
	
	public static function getSum($linkitemId)
	{
		$select = static::$db->select()
		                     ->from(static::$table, 'SUM(views)')
		                     ->where('linkitem_id = ?', $linkitemId);
		$sum = static::$db->fetchOne($select);
		
		return (int) $sum;
	}
	
	protected static function handleExp($exp, Zend_Db_Select $select, $countMode = false)
	{
		foreach ($exp as $clause => $value) {
			switch ($clause) {
				case 'id':
				case 'linkitem_id':
				case 'date':
					$select->where('linkitems.`' . $clause . '` = ?', $value);
					break;
			}
		}
	}
	
}
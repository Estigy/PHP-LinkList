<?php

class Model_Query_User extends Core_Model_Query
{
	protected static $table        = 'users';
	protected static $defaultOrder = 'id ASC';

	public static function count($exp)
	{
		$select = static::$db->select()
		                     ->from(static::$table, 'COUNT(' . static::$table . '.id)');
		self::handleExp($exp, $select);
		
		return static::$db->fetchOne($select);
	}
	
	protected static function handleExp($exp, Zend_Db_Select $select)
	{
		foreach ($exp as $clause => $value) {
			switch ($clause) {
				case 'role':
					$select->where('role = ?', $value);
					break;
				case 'username':
					$select->where('username LIKE ?', '%' . $value . '%');
					break;
				case 'name':
					$select->where('firstname LIKE ?', '%' . $value . '%')
					       ->orWhere('lastname LIKE ?', '%' . $value . '%');
					break;
			}
		}
	}
	
	public static function findByLogin($username, $password)
	{
		$select = static::$db->select()
		                     ->from(static::$table)
		                     ->where('username = ?', $username)
		                     ->where('password = ?', md5($password))
		                     ->limit(1);
		$row = static::$db->fetchRow($select);
		
		return ($row === false) ? false : static::convert($row);
	}
	
}
<?php

abstract class Core_Model_Query
{
	protected static $table        = '';
	protected static $idField      = 'id';
	protected static $defaultOrder = '';
	protected static $forceInsert  = false;
	protected static $db           = null;
	
	
	public static function setAdapter($adapter)
	{
		static::$db = $adapter;
	}
	
	/**
	* Normalerweise wird anhand der ID bestimmt, ob eine Zeile in der DB neu eingefügt oder aktualisiert wird.
	* Durch setForceInsert(true) kann dieses Verhalten überschrieben werden (zB bei einem Importer, wo die IDs
	* zwar einen Wert haben, aber der Datensatz trotzdem neu eingefügt werden soll)
	* 
	* @param boolean $doForce
	*/
	public static function setForceInsert($doForce)
	{
		static::$forceInsert = (boolean) $doForce;
	}

	public static function getById($id)
	{
		$stmt = static::$db->select()
		                   ->from(static::$table)
		                   ->where(static::$idField . ' = ?', $id)
		                   ->limit(1);
		$row = static::$db->fetchRow($stmt);
		
		return ($row === false) ? false : static::convert($row);
	}
	
	public static function find($start, $count, $exp = array())
	{
		$select = static::$db->select()
		                     ->from(static::$table)
		                     ->limit($count, $start);
		static::handleExp($exp, $select);
		
		if (!$select->getPart(Zend_Db_Select::ORDER)) {
			$select->order(static::$defaultOrder);
		}
		
		$rows = static::$db->fetchAll($select);
		
		return new Core_Model_RecordSet($rows, get_called_class());
	}
	
	public static function getOne($exp)
	{
		$items = static::find(0, 1, $exp);
		
		return count($items) == 1 ? $items[0] : false;
	}
	
	public static function count($exp)
	{
		$select = static::$db->select()
		                     ->from(static::$table, 'COUNT(' . static::$table . '.id)');
		static::handleExp($exp, $select, true);
		
		return static::$db->fetchOne($select);
	}
	
	public static function save($object)
	{
		if ($object->{static::$idField} == 0 || static::$forceInsert) {
			$res = static::$db->insert(
				static::$table,
				$object->getProperties(true) // nur die nicht-sprachabhängigen Felder
			);
			
			if ($res === false) {
				return false;
			}
			
			$object->{static::$idField} = static::$db->lastInsertId();
			
		} else {
			
			$res = static::$db->update(
				static::$table,
				$object->getProperties(true),
				array(static::$idField . ' = ?' => $object->{static::$idField})
			);
			
			if ($res === false) {
				return false;
			}
		}
		
		return $object->{static::$idField};
	}
	
	public static function delete($object)
	{
		return static::$db->delete(
			static::$table,
			array(static::$idField . ' = ?' => $object->{static::$idField})
		);
	}
	
	protected static function getModelClass()
	{
		$modelClass = str_replace('_Query', '', get_called_class());
		
		return $modelClass;
	}
	
	public static function convert($entity)
	{
		$mc = static::getModelClass();
		
		return new $mc($entity);
	}
	
}
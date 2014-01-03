<?php

class Model_Query_Page extends Core_Model_Query
{
	protected static $table        = 'pages';
	protected static $defaultOrder = 'title ASC';
	
	const TREE_TYPE_TREE   = 1;
	const TREE_TYPE_FLAT   = 2;
	const TREE_TYPE_LABELS = 3;
	
	public static function getBySlug($slug)
	{
		$pages = self::find(0, 1, array('slug' => $slug));
		
		return $pages[0];
	}
	
	protected static function handleExp($exp, Zend_Db_Select $select)
	{
		foreach ($exp as $clause => $value) {
			switch ($clause) {
				case 'parent_id':
				case 'status':
				case 'slug':
					$select->where($clause . ' = ?', $value);
					break;
			}
		}
	}
	
	public static function getTree($treeType = self::TREE_TYPE_TREE)
	{
		// rekursives Zusammenbauen des Baumes starten
		// MotherId = 0, Level = 0
		$tree = self::_getSubTree(0, 0, $treeType == self::TREE_TYPE_LABELS ? self::TREE_TYPE_FLAT : $treeType);
		
		if ($treeType == self::TREE_TYPE_LABELS) {
			$list = array();
			foreach ($tree as $t) {
				$list[$t['node']->id] = str_repeat('&nbsp;', 3 * $t['level']) . $t['node']->title;
			}
			return $list;
		}
		return $tree;
	}
	
	protected function _getSubTree($parentId, $level, $treeType)
	{
		$nodes = self::find(0, PHP_INT_MAX, array('parent_id' => $parentId));
		if (!count($nodes)) {
			return array();
		}
		$branch = array();
		foreach ($nodes as $node) {
			$item = array(
				'level'    => $level,
				'node'     => $node,
			);
			
			if ($treeType == self::TREE_TYPE_TREE) {
				// Wenn wir einen Baum wollen, dann kommt ins Feld "subnodes" der Unterbaum
				$item['subnodes'] = self::_getSubTree($node->id, $level + 1, $treeType);
				$branch[] = $item;
			} elseif ($treeType == self::TREE_TYPE_FLAT) {
				// Wenn wir eine flache Antwort wollen, dann einfach zur bisherigen Branch das neue Item dranfügen - und den Unterbaum auch.
				$branch = array_merge($branch, array($item), self::_getSubTree($node->id, $level + 1, $treeType));
			}
		}
		return $branch;
	}
}
<?php

class Model_Query_Category extends Core_Model_Query
{
	protected static $table        = 'categories';
	protected static $defaultOrder = 'sort ASC';
	
	const TREE_TYPE_TREE   = 1;
	const TREE_TYPE_FLAT   = 2;
	const TREE_TYPE_LABELS = 3;
	
	
	public static function getBySlug($slug)
	{
		$cat = self::find(0, 1, array('slug' => $slug));
		return count($cat) ? $cat[0] : false;
	}
	
	protected static function handleExp($exp, Zend_Db_Select $select, $countMode = false)
	{
		foreach ($exp as $clause => $value) {
			switch ($clause) {
				case 'parent_id':
				case 'status':
				case 'slug':
					$select->where($clause . ' = ?', $value);
					break;
				case 'linkitem_id':
					$select->joinInner('linkitems', 'categories.id = linkitems.category_id', array())
					       ->where('linkitems.id = ?', $value)
					       ->group('categories.id');
					break;
			}
		}
	}
	
	public static function getSubCategories($id, $status)
	{
		$exp = array('parent_id' => $id);
		if ($status) {
			$exp['status'] = $status;
		}
		
		return self::find(0, PHP_INT_MAX, $exp);
	}
	
	public static function getTree($treeType = self::TREE_TYPE_TREE, $status = '')
	{
		// rekursives Zusammenbauen des Baumes starten
		// MotherId = 0, Level = 0
		$tree = self::getSubTree(0, 0, $treeType == self::TREE_TYPE_LABELS ? self::TREE_TYPE_FLAT : $treeType, $status);
		
		if ($treeType == self::TREE_TYPE_LABELS) {
			$list = array();
			foreach ($tree as $t) {
				$list[$t['node']->id] = str_repeat('&nbsp;', 3 * $t['level']) . $t['node']->title;
			}
			return $list;
		}
		return $tree;
	}
	
	protected function getSubTree($motherId, $level, $treeType, $status)
	{
		$nodes = self::getSubCategories($motherId, $status);
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
				$item['subnodes'] = self::getSubTree($node->id, $level + 1, $treeType, $status);
				$branch[] = $item;
			} elseif ($treeType == self::TREE_TYPE_FLAT) {
				// Wenn wir eine flache Antwort wollen, dann einfach zur bisherigen Branch das neue Item dranfügen - und den Unterbaum auch.
				$branch = array_merge($branch, array($item), self::getSubTree($node->id, $level + 1, $treeType, $status));
			}
		}
		return $branch;
	}
	
}
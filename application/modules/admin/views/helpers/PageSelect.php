<?php
	class Admin_View_Helper_PageSelect extends Zend_View_Helper_Abstract {

		public function pageSelect($name, $attr = array())
		{
			$categories = Model_Query_Page::getTree(Model_Query_Page::TREE_TYPE_LABELS);
			
			$selectedIds = isset($attr['selected']) ? (array) $attr['selected'] : array();
			$disabledIds = isset($attr['disabled']) ? (array) $attr['disabled'] : array();
			
			$s = '<select name="' . $this->view->escape($name) . '"';
			foreach ($attr as $param => $value) {
				if ($param == 'selected' || $param == 'disabled') {
					continue;
				}
				$s .= ' ' . $param . '="' . $this->view->escape($value) . '"';
			}
			$s .= '>';
			$s .= '<option value="0">&nbsp;</option>';

			foreach ($categories as $id => $category) {
				$selected = in_array($id, $selectedIds) ? ' selected="selected"' : '';
				$disabled = in_array($id, $disabledIds) ? ' disabled="disabled"' : '';
				$s .= '
				<option value="' . $id .'"' . $selected . $disabled . '>' . $category . '</option>';
			}
			$s .= '</select>';

			return $s;
		}
	}

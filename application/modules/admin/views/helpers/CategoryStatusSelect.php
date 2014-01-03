<?php
	class Admin_View_Helper_CategoryStatusSelect extends Zend_View_Helper_Abstract {

		public function categoryStatusSelect($name, $attr = array())
		{
			$items = array(
				Model_Category::STATUS_OFFLINE => 'offline',
				Model_Category::STATUS_ONLINE  => 'online',
			);
			
			$selectedId = isset($attr['selected']) ? $attr['selected'] : '';
			
			$s = '<select name="' . $this->view->escape($name) . '"';
			foreach ($attr as $param => $value) {
				if ($param == 'selected') {
					continue;
				}
				$s .= ' ' . $param . '="' . $this->view->escape($value) . '"';
			}
			$s .= '>';

			foreach ($items as $id => $item) {
				$selected = ($id == $selectedId) ? ' selected="selected"' : '';
				$s .= '
				<option value="' . $id .'"' . $selected . '>' . $item . '</option>';
			}
			$s .= '</select>';

			return $s;
		}
	}

<?php
	class Admin_View_Helper_PageStatusSelect extends Zend_View_Helper_Abstract {

		public function pageStatusSelect($name, $attr = array())
		{
			$salutations = array(
				'offline' => 'offline',
				'online'  => 'online',
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

			foreach ($salutations as $id => $salutation) {
				$selected = ($id == $selectedId) ? ' selected="selected"' : '';
				$s .= '
				<option value="' . $id .'"' . $selected . '>' . $salutation . '</option>';
			}
			$s .= '</select>';

			return $s;
		}
	}

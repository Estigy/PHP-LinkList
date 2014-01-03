<?php
	class Admin_View_Helper_UserRoleSelect extends Zend_View_Helper_Abstract {

		public function userRoleSelect($name, $attr = array())
		{
			$config = new Zend_Config_Ini(APPLICATION_PATH . '/config/acl.ini');
			$items = $config->roles;
			
			$selectedId = isset($attr['selected']) ? $attr['selected'] : '';
			
			$s = '<select name="' . $this->view->escape($name) . '"';
			foreach ($attr as $param => $value) {
				if ($param == 'selected') {
					continue;
				}
				$s .= ' ' . $param . '="' . $this->view->escape($value) . '"';
			}
			$s .= '>';
			if (isset($attr['addEmpty'])) {
				$s .= '
				<option value=""></option>';
			}

			foreach ($items as $key => $value) {
				$selected = ($key == $selectedId) ? ' selected="selected"' : '';
				$s .= '
				<option value="' . $key .'"' . $selected . '>' . $value . '</option>';
			}
			$s .= '</select>';

			return $s;
		}
	}

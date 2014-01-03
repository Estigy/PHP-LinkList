<?php
	class Admin_View_Helper_SalutationSelect extends Zend_View_Helper_Abstract {

		public function salutationSelect($name, $attr = array())
		{
			$salutations = array(
				'1' => $this->view->translate->_('Mr.'),
				'2' => $this->view->translate->_('Ms.')
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
			$s .= '<option value="0"></option>';

			foreach ($salutations as $id => $salutation) {
				$selected = ($id == $selectedId) ? ' selected="selected"' : '';
				$s .= '
				<option value="' . $id .'"' . $selected . '>' . $salutation . '</option>';
			}
			$s .= '</select>';

			return $s;
		}
	}

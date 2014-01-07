<?php
class Admin_View_Helper_LinkitemStatusSelect extends Zend_View_Helper_Abstract {

	public function linkitemStatusSelect($name, $attr = array())
	{
		$items = array(
			Model_LinkItem::STATUS_NEW    => 'neu',
			Model_LinkItem::STATUS_ONLINE => 'online',
			Model_LinkItem::STATUS_BANNED => 'gesperrt',
		);
		
		$selectedId = isset($attr['selected']) ? $attr['selected'] : '';
		
		$s = '<select name="' . $this->view->escape($name) . '"';
		foreach ($attr as $param => $value) {
			if ($param == 'selected') {
				continue;
			}
			$s .= ' ' . $param . '="' . $this->view->escape($value) . '"';
		}
		$s .= '>
			<option value="">&nbsp;</option>';

		foreach ($items as $id => $item) {
			$selected = ($id == $selectedId) ? ' selected="selected"' : '';
			$s .= '
			<option value="' . $id .'"' . $selected . '>' . $item . '</option>';
		}
		$s .= '</select>';

		return $s;
	}
}

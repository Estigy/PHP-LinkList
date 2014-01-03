<?php

class Core_View_Helper_FormatDate extends Zend_View_Helper_Abstract
{
	public function formatDate($date, $format = Zend_Date::DATES, $zeroValueDefault = '')
	{
		if ($date === null || $date === 0 || $date === '' || $date === '0000-00-00' || $date === '0000-00-00 00:00:00') {
			return $zeroValueDefault;
		}
		$d = new Zend_Date($date);
		return $d->get($format);
	}
}
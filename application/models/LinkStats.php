<?php

class Model_LinkStats extends Core_Model
{
	public function __construct ($data = array())
	{
		$this->properties += array(
			'id'          => 0,
			'linkitem_id' => null,
			'date'        => null,
			'views'       => null,
		);
		
		parent::__construct($data);
	}
	
}

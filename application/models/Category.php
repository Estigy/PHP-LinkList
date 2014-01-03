<?php

class Model_Category extends Core_Model
{
	const STATUS_ONLINE  = 'online';
	const STATUS_OFFLINE = 'offline';
	
	public function __construct ($data = array())
	{
		$this->properties += array(
			'id'          => 0,
			'parent_id'   => 0,
			'sort'        => null,
			'title'       => null,
			'slug'        => null,
			'subtitle'    => null,
			'description' => null,
			'keywords'    => null,
			'status'      => self::STATUS_OFFLINE
		);
		
		parent::__construct($data);
	}
	
}

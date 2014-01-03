<?php

class Model_Page extends Core_Model
{
	const STATUS_ONLINE  = 'online';
	const STATUS_OFFLINE = 'offline';
	
	public function __construct($data = array())
	{
		$this->properties += array(
			'id'                => 0,
			'parent_id'         => null,
			'title'             => null,
			'slug'              => null,
			'text'              => null,
			'description'       => null,
			'keywords'          => null,
			'status'            => self::STATUS_OFFLINE,
		);
		
		parent::__construct($data);
	}
	
}

<?php

class Model_LinkItem extends Core_Model
{
	const STATUS_NEW    = 1;
	const STATUS_ONLINE = 2;
	const STATUS_BANNED = 3;
	
	public function __construct ($data = array())
	{
		$this->properties += array(
			'id'                  => 0,
			'title'               => null,
			'slug'                => null,
			'description'         => null,
			'keywords'            => null,
			'url'                 => null,
			'category_id'         => null,
			'screenshot'          => null,
			'contact_name'        => null,
			'contact_address'     => null,
			'contact_zip'         => null,
			'contact_city'        => null,
			'contact_country'     => null,
            'contact_phone'       => null,
            'contact_person'      => null,
            'contact_email'       => null,
			'date_entry'          => null,
			'date_update'         => null,
			'date_screenshot'     => null,
			'date_activationmail' => null,
			'token'				  => null,
			'status'              => null,
		);
		
		parent::__construct($data);
	}
	
	public function save()
	{
		if (!$this->token) {
			$this->token = md5($this->id . $this->title);
		}
		
		parent::save();
	}
	
	public function getViews()
	{
		return Model_Query_LinkStats::getSum($this->id);
	}
	
	public function getCategory()
	{
		if (!$this->category_id) {
			return false;
		}
		
		return Model_Query_Category::getById($this->category_id);
	}
}

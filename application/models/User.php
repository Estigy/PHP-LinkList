<?php

class Model_User extends Core_Model
{
	protected $avatar = null;
	
	public function __construct ($data = array()) {
		$this->properties += array(
			'id'        => 0,
			'username'  => null,
			'password'  => null,
			'firstname' => null,
			'lastname'  => null,
			'email'     => null,
			'role'      => null,
		);
		
		parent::__construct($data);
	}
	
}

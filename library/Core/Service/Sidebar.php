<?php

class Core_Service_Sidebar
{
	protected $canToggle = false;
	protected $view = '';
	
	public function __construct($standardView)
	{
		$this->view = $standardView;
	}
	
	public function setView($view)
	{
		$this->view = $view;
	}
	
	public function getView()
	{
		return $this->view;
	}
	
	public function setCanToggle($canToggle)
	{
		$this->canToggle = $canToggle;
	}
	
	public function canToggle()
	{
		return $this->canToggle;
	}
}
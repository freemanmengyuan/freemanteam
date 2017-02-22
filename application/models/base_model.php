<?php
class base_model extends CI_Model {
	public function __construct()
	{
		$this->load->database();
	}
}
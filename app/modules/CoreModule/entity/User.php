<?php 

namespace App\Modules\CorenModule\Entity;


class User extends BaseEntity 
{
	
	public $email;
	
	public $password;
	
	
	public function setEmail($email) {
		$this->email = $email;
	}
	
	public function getEmail() {
		return $this->email;
	}
	
	
	
	
	public function setPassword($password) {
		$this->password = $password;
	}
	
	public function getPassword() {
		return $this->password;
	}
	
}
<?php

namespace App\Modules\Core\Repository;

use App\Modules\Core\Repository\BaseRespository;
use App\Modules\CorenModule\Entity\User;

class UserRepository extends BaseRespository
{
	/** @var Nette\Database\Context */
	private $connection;

	public function __construct(\Nette\Database\Context $connection)
	{
		$this->connection = $connection;
	}


	public function getUser($id) {
		$user = $this->connection->query("SELECT * FROM user WHERE id = '$id'")->fetch();
		return $user;
	}

	public function getUserByCredentials($email, $password) {
		$result = $this->connection->query("SELECT * FROM user WHERE email='$email' AND password='$password'")->fetch();
		return $result;
	}

	public function createUser($nick, $email, $password, $role) {
		$result = $this->connection->query("INSERT INTO user (nick, email, password, role, date_create, last_updated)
				VALUES ('$nick', '$email', '$password', '$role', '" . $this->getDate() . "', '" . $this->getDate() . "')");
		return $result;
	}

	public function getUserByEmail($email) {
		$result = $this->connection->query("SELECT * FROM user WHERE email='$email'")->fetch();
		return $result;
	}

	public function updatePassword($userId, $password) {
		$result = $this->connection->query("UPDATE user SET password='$password', last_updated='" . $this->getDate() . "' WHERE id='$userId'");
		return $result;
	}


}
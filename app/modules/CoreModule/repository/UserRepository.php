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
		$user = $this->connection->table('user')->where('id = ?', $id)->fetch();
		return $user;
	}

	public function getUserByCredentials($email, $password) {
		$result = $this->connection->table('user')->where('email = ? AND password = ?', array($email, $password))->fetch();
		return $result;
	}

	public function createUser($nick, $email, $password, $role) {
		$result = $this->connection->table('user')->insert(array(
				'nick' => $nick,
				'email' => $email,
				'password' => $password,
				'role' => $role,
				'date_create' => $this->getDate(),
				'last_updated' => $this->getDate()
		));
		return $result;
	}
	
	public function getUserByEmail($email) {
		$result = $this->connection->table('user')->where('email = ?', $email)->fetch();
		return $result;
	}

	public function updatePassword($userId, $password) {
		$result = $this->connection->table('user')->where('id = ?', $userId)->update(array('password' => $password, 'last_updated' => $this->getDate()));
		return $result;
	}
	
	public function updatePublicLink($userId, $publicLink, $publicLinkActive) {
		$result = $this->connection->table('user')->where('id = ?', $userId)
				->update(array('public_link' => $publicLink, 'public_link_active' => $publicLinkActive, 'last_updated' => $this->getDate()));
		return $result;
	}

	public function getUserByPublicLink($publicLink) {
		$result = $this->connection->table('user')->where('public_link = ?', $publicLink)->fetch();
		return $result;
	}

}
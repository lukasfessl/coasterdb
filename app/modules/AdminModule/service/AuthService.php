<?php

namespace App\Modules\AdminModule\Service;

use Nette\Security\IAuthenticator;
use Nette\Security\AuthenticationException;
use Nette\Security\Identity;
use App\Modules\Core\Utils\Hash;
use App\Modules\Core\Repository\UserRepository;

class AuthService implements IAuthenticator
{
	private $userRepository;
	private $params;

	public function __construct(UserRepository $userRepository, array $params) {
		$this->userRepository = $userRepository;
		$this->params = $params;
	}

	public function authenticate(array $credentials)
	{
		list($email, $password) = $credentials;

		$result = $this->userRepository->getUserByCredentials($email, Hash::sha1($password, $this->params['salt']));

		if(!$result) {
			throw new AuthenticationException('User or password is bad', IAuthenticator::INVALID_CREDENTIAL);
		}

		return new Identity($result->id, null, $result);
	}
}

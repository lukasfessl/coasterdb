<?php

namespace App\Modules\AdminModule\Presenters;

use App\Modules\Core\Entity\Role;
use Nette;



class BasePresenter extends Nette\Application\UI\Presenter
{
	protected $managementService;
	protected $user;

	protected function startup() {
		parent::startup();
		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect('Auth:default');
		}
		$this->managementService = $this->context->getService('managementService');
		$this->user = $this->getUser()->identity->data;
	}

	
	public function handleLogout() {
		$user = $this->getUser();
		$user->logout(TRUE);

		if (!$user->isLoggedIn()) {
			$this->redirect('Auth:default');
		}
	}
	
	public function getUserRole() {
		return $this->user['role'];
	}
	
	public function isUserSuperAdmin() {
		if ($this->user['role'] == Role::SUPER_ADMIN) {
			return true;
		}
		
		return false;
	}
	
}

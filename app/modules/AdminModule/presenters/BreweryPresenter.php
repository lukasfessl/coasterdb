<?php

namespace App\Modules\AdminModule\Presenters;

use App\Modules\AdminModule\Components\BreweryForm;
use App\Modules\Core\Entity\Role;
use Nette\Application\ForbiddenRequestException;

class BreweryPresenter extends BasePresenter
{

	protected function startup() {
		parent::startup();
		if ($this->user['role'] != Role::SUPER_ADMIN) {
			throw new ForbiddenRequestException();
		}
	}

	public function actionList() {

	}
	
	public function renderList() {
		$this->template->braveries = $this->managementService->getBraveries();
	}

	
	
	public function createComponentBreweryForm() {
		return new BreweryForm($this->managementService);
	}


		
	public function actionEdit($id) {

	}
	
	public function renderEdit($id) {
			
	}
	
	
}

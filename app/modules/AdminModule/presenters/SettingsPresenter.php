<?php

namespace App\Modules\AdminModule\Presenters;

use App\Modules\AdminModule\Components\UserUpdateForm;

class SettingsPresenter extends BasePresenter
{

	protected function startup() {
		parent::startup();

	}


	public function renderDefault() {

	}

	public function createComponentUserUpdateForm() {
		return new UserUpdateForm($this->managementService, $this->user['id']);
	}

}

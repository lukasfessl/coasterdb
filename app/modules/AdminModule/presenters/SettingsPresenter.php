<?php

namespace App\Modules\AdminModule\Presenters;

use App\Modules\AdminModule\Components\UserUpdateForm;
use App\Modules\AdminModule\Components\PublicLinkForm;

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

	public function createComponentPublicLinkForm() {
		return new PublicLinkForm($this->managementService, $this->user['id']);
	}
}

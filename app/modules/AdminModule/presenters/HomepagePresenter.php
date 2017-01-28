<?php

namespace App\Modules\AdminModule\Presenters;



use App\Modules\Core\Entity\Role;

class HomepagePresenter extends BasePresenter
{

	protected function startup() {
		parent::startup();
	}


	public function renderDefault() {
		$data = $this->managementService->getDashboardData($this->user['id']);
		$this->template->coastersUniqueCount = $data['coastersUniqueCount'];
		$this->template->coastersTotalCount = $data['coastersTotalCount'];
		$this->template->breweryCoasters = $data['breweryCoasterCount'];
	}

	public function getDumpUser() {
		if ($this->user['role'] == Role::SUPER_ADMIN) {
			dump($this->user);
		}
	}

}

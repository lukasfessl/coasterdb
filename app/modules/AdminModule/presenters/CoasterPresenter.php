<?php

namespace App\Modules\AdminModule\Presenters;

use App\Modules\AdminModule\Components\CoasterForm;
use App\Modules\AdminModule\Components\Pagination;

class CoasterPresenter extends BasePresenter
{

	protected function startup() {
		parent::startup();
	}

	public function renderDefault() {
// 				dump($this->getUser()->identity->data);
		// 		dump($this->managementService->getUser(1));
	}
	
	public function createComponentCoasterForm() {
		return new CoasterForm($this->managementService, $this->user['id']);
	}

	public function renderList($page = null) {
		$this->template->user = $this->user;
		
		$paginatorParams = $this->context->parameters['paginator'];
		$totalItemCount = $this->managementService->countCoasters($this->user['id']);
		$paginator = new Pagination($totalItemCount, $page, $paginatorParams['itemPerPage']);
		$paginator = $paginator->getPaginator();
		$this->template->coasters =	$this->managementService->getCoasters($this->user['id'], $paginator->getItemsPerPage(), $paginator->getOffset());
	}
	
	public function actionList($page = null) {

	}
	
	public function createComponentPagination() {
		$page = $this->getParameter('page');
		$paginatorParams = $this->context->parameters['paginator'];
		$totalItemCount = $this->managementService->countCoasters($this->user['id']);
		return new Pagination($totalItemCount, $page, $paginatorParams['itemPerPage'], $paginatorParams['paginatorRange'], 'right');
	}
	
	public function handleRemoveCoaster($coasterId) {
		if ($this->isAjax()) {
			$coaster = $this->managementService->getCoaster($coasterId, $this->user['id']);
			$result = $this->managementService->removeCoaster($coaster, $this->user['id']);
			if ($result == 0) {
				echo "error31";
				$this->terminate();
			}
	
			$this->redrawControl("coasterList");
	
		}
	}
		
	public function actionEdit($id) {
			
	}
	
	public function renderEdit($id) {
			
	}
}
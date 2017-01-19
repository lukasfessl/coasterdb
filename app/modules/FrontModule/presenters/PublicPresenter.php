<?php

namespace App\Modules\FrontModule\Presenters;

use App\Modules\AdminModule\Components\Pagination;
use App\Modules\AdminModule\Components\Filter;
use App\Modules\Core\Entity\FilterParams;
use App\Modules\FrontModule\Presenters\BasePresenter;
use Nette\Application\BadRequestException;

class PublicPresenter extends BasePresenter
{
	private $user;

	public function actionDefault($pid)
	{
		$this->user = $this->managementService->getUserByPublicLink($pid);	
		if (!$this->user || !$this->user['public_link_active']) {
			throw new BadRequestException();	
		}
	}
	
	public function renderDefault($pid, $order = null, $sort = null, $like = null, $page = null)
	{
		$paginatorParams = $this->context->parameters['paginator'];
		$totalItemCount = $this->managementService->countCoasters($this->user['id'], $like);
		$paginator = new Pagination($totalItemCount, $page, $paginatorParams['itemPerPage']);
		$paginator = $paginator->getPaginator();
		
		$filtrParams = new FilterParams();
		$filtrParams->setOrder($order);
		$filtrParams->setSort($sort);
		$filtrParams->setLike($like);
		$this->template->coasters =	$this->managementService->getCoasters($this->user['id'], $paginator->getItemsPerPage(), $paginator->getOffset(), $filtrParams);
		$this->template->user = $this->user;
	}

	public function createComponentPagination() {
		$page = $this->getParameter('page');
		$like = $this->getParameter('like');
		$paginatorParams = $this->context->parameters['paginator'];
		$totalItemCount = $this->managementService->countCoasters($this->user['id'], $like);
		return new Pagination($totalItemCount, $page, $paginatorParams['itemPerPage'], $paginatorParams['paginatorRange'], 'left');
	}
	
	public function createComponentCoasterFilter() {
		return new Filter();
	}

}

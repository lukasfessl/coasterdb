<?php 

namespace App\Modules\AdminModule\Components;

use Nette\Application\UI\Control;
use Nette\Utils\Paginator;

class Pagination extends Control
{
	private $paginatorRange;
	private $position;
	
	private $paginator;
	
	public function __construct($itemCount, $currentPage, $itemsPerPage, $paginatorRange = null, $position = null)
	{
		$this->paginator = new Paginator;
		$this->paginator->setItemCount($itemCount);
		$this->paginator->setItemsPerPage($itemsPerPage);
		$this->paginator->setPage($currentPage);
				
		if ($position == 'left') {
			$this->position = 'uk-pagination-left';
		} else if ($position == 'right') {
			$this->position = 'uk-pagination-right';
		}
		$this->paginatorRange = $paginatorRange;
	}
	
	public function render()
	{
		$this->template->setFile(__DIR__.'/Pagination.latte');
		$this->template->position = $this->position ? $this->position : '';
		$this->template->paginator = $this->paginator;
		$this->template->paginatorRange = $this->paginatorRange;
		$this->template->render();
	}
	
	public function getPaginator() {
		return $this->paginator;
	}

}

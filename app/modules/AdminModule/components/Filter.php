<?php 

namespace App\Modules\AdminModule\Components;

use Nette\Application\UI\Control;
use Nette\Utils\Paginator;
use Nette\Application\UI\Form;
use App\Modules\Core\Entity\FilterParams;

class Filter extends Control
{
	private $filterParams;
	
	public function __construct()
	{
		$this->filterParams = new FilterParams;
	}
	
	
	public function createComponentForm() 
	{
		$filterParams = new FilterParams();
		$filterParams->getOrderParams();
		$sortParam = $this->presenter->getParameter('sort');
		$orderParam = $this->presenter->getParameter('order');

		$form = new Form();
		$form->addText('breweryName', 'Brewery name:')->setDefaultValue($this->presenter->getParameter('like'));
		$form->addSelect('order', 'Order:', $this->filterParams->getOrderParams())
				->setDefaultValue(array_key_exists($orderParam, $filterParams->getOrderParams()) ? $orderParam : 'desc');
		$form->addSelect('sortBy', 'Sort by:',$this->filterParams->getSortParams())
				->setDefaultValue(array_key_exists($sortParam, $filterParams->getSortParams()) ? $sortParam : 'inserted');
		$form->addSubmit('search', 'Search')->setAttribute('class', 'uk-button');
		$form->onSuccess[] = $this->process;
		
		return $form;
	}
	
	
	public function process($form)
	{
		$values = $form->getValues();

		$this->filterParams->setOrder($values['order']);
		$this->filterParams->setSort($values['sortBy']);
		$this->filterParams->setLike($values['breweryName']);
		$this->filterParams->setPage($this->presenter->getParameter('page'));

		$this->presenter->redirect('this', $this->filterParams->getParams());
	}
	
	
	public function render()
	{
		$this->template->setFile(__DIR__.'/Filter.latte');
		$this->template->render();
	}
	
}

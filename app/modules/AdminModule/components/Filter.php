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
		$form = new Form();
		$form->addSelect('order', 'Order:', $this->filterParams->getOrderParams())
				->setDefaultValue($this->presenter->getParameter('order') ? $this->presenter->getParameter('order') : 'desc');
		$form->addSelect('sortBy', 'Sort By:',$this->filterParams->getSortParams())
				->setDefaultValue($this->presenter->getParameter('sortBy') ? $this->presenter->getParameter('sortBy') : 'inserted');
		$form->addSubmit('search', 'Search')->setAttribute('class', 'uk-button');
		$form->onSuccess[] = $this->process;
		
		return $form;
	}
	
	
	public function process($form)
	{
		$values = $form->getValues();

		$this->filterParams->setOrder($values['order']);
		$this->filterParams->setSort($values['sortBy']);
		$this->filterParams->setPage($this->presenter->getParameter('page'));

		$this->presenter->redirect('this', $this->filterParams->getParams());
	}
	
	
	public function render()
	{
		$this->template->setFile(__DIR__.'/Filter.latte');
		$this->template->render();
	}
	
}

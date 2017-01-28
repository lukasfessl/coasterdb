<?php

namespace App\Modules\AdminModule\Components;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Utils\Image;
use Nette\Utils\Strings;
use Nette\Application\ApplicationException;

class BreweryForm extends Control
{
	private $managementService;
	private $brewery;

	public function __construct($managementService)
	{
		$this->managementService = $managementService;
	}

	public function createComponentForm()
	{
		$breweryId = $this->presenter->getParameter('id');
		if ($breweryId != null && !is_numeric($breweryId)) {
			throw new ApplicationException();
		}
		$this->brewery = $this->managementService->getBrewery($breweryId);

		$form = new Form();
		$form->addText('brewery', 'Brewery', 30)->setValue($this->brewery['name'])->addRule(Form::MIN_LENGTH, "Brewery have to be fill", 1);
		$form->addText('founded', 'Founded', 30)->setValue($this->brewery['founded'])->addCondition(Form::REQUIRED)->addRule(Form::NUMERIC, "Value has to be number.");
		$form->addSubmit('send', $breweryId ? 'Update' : 'Save')->setAttribute('class', 'uk-button');
		$form->onSuccess[] = $this->process;

		return $form;
	}

	public function process($form)
	{
		$values = $form->getValues();

		$breweryId = $this->presenter->getParameter('id');
		if ($breweryId != null && !is_numeric($breweryId)) {
			throw new ApplicationException();
		}
		if (!$breweryId) {
			$this->managementService->saveBrewery($values->brewery, $values->founded);
			$this->presenter->flashMessage('Brewery was added');
			$this->presenter->redirect('Brewery:list');
		} else {
			$this->managementService->updateBrewery($breweryId, $values->brewery, $values->founded);
			$this->presenter->flashMessage('Brewery was updated');
		}
	}

	public function render()
	{
		$this->template->setFile(__DIR__.'/BreweryForm.latte');
		$this->template->render();
	}

}

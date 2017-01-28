<?php 

namespace App\Modules\AdminModule\Components;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Utils\Image;
use Nette\Utils\Strings;
use Nette\Application\ApplicationException;

class PublicLinkForm extends Control
{
	private $managementService;
	private $userId;
	
	public function __construct($managementService, $userId)
	{
		$this->managementService = $managementService;
		$this->userId = $userId;
		
	}
	
	public function createComponentForm()
	{
		$user = $this->managementService->getUser($this->userId);
		$form = new Form();
		$form->addCheckbox('active', ' Active')->setValue($user['public_link_active']);
		$link = $this->presenter->link("//:Front:Public:default", array('pid' => $user['public_link']));
		$form->addText('publicLink', 'Link:', 100)->setDisabled(TRUE)->setValue($link);
		$form->addSubmit('update', 'Update')->setAttribute('class', 'uk-button');
		$form->onSuccess[] = $this->process;
		
		return $form;
	}

	public function process($form)
	{
		$user = $this->managementService->getUser($this->userId);
		$values = $form->getValues();
		$this->managementService->updatePublicLink($user, $form['active']->value);
		if ($form['active']->value) {
			$this->presenter->flashMessage('Public link was activated.');
		} else {
			$this->presenter->flashMessage('Public link was deactivated.');
		}
		$this->presenter->redirect('this');
	}

	public function render()
	{
		$this->template->setFile(__DIR__.'/PublicLinkForm.latte');
		$this->template->render();
	}
	
	private function formateBraveries($braveries)
	{
		$braveriesArray = array();
		foreach ($braveries as $brewery) {
			array_push($braveriesArray, array('brewery' => $brewery['name'], 'founded' => $brewery['founded']));
		}
		
		return json_encode($braveriesArray);
	}
}

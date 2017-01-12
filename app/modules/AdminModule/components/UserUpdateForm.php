<?php 

namespace App\Modules\AdminModule\Components;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use App\Modules\Core\Entity\Role;


class UserUpdateForm extends Control
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
		$form = new Form();
		$p2 = $form->addPassword('password2', 'Password again:', 30);
		$p1 = $form->addPassword('password1', 'Password:', 30)->addRule(Form::MIN_LENGTH, "Password has to be fill.", 1)
			->addRule(\CustomFormValidation::EQUEL, "Passwords do not match.", $p2);
		
		$form->addSubmit('send', 'Update')->setAttribute('class', 'uk-button');
		$form->onSuccess[] = $this->process;
		
		return $form;
	}


	public function process($form)
	{
		$values = $form->getValues();
		
		$this->managementService->updatePassword($this->userId ,$values['password1']);
		$this->presenter->flashMessage('Password was changed.');
	}

	public function render()
	{
		$this->template->setFile(__DIR__.'/UserUpdateForm.latte');
		$this->template->render();
	}
}

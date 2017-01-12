<?php 

namespace App\Modules\AdminModule\Components;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use App\Modules\Core\Entity\Role;


class RegistrationForm extends Control
{
	private $managementService;

	
	public function __construct($managementService)
	{
		$this->managementService = $managementService;		
	}
	
	public function createComponentForm()
	{
		
		$form = new Form();
		$form->addText('nick', 'Nick:', 30);
		$form->addText('email', 'E-mail:', 30)->addRule(Form::MIN_LENGTH, "E-mail has to be fill.", 1)->addRule(Form::EMAIL, "This is not corrent E-mail.")
				->addRule(\CustomFormValidation::EMAIL_NOT_EXIST, "This E-mail is already registered.", $this->managementService);
		
		$p2 = $form->addPassword('password2', 'Password again:', 30);
		$p1 = $form->addPassword('password1', 'Password:', 30)->addRule(Form::MIN_LENGTH, "Password has to be fill.", 1)
				->addRule(\CustomFormValidation::EQUEL, "Passwords do not match.", $p2);
		
		$form->addSubmit('send', 'Registr')->setAttribute('class', 'uk-button');
		$form->onSuccess[] = $this->process;
		
		return $form;
	}


	public function process($form)
	{
		$values = $form->getValues();

		$this->managementService->createUser($values['nick'], $values['email'], $values['password1'], Role::ADMIN);
		$this->presenter->flashMessage('Account was created.');
		$this->presenter->redirect('Auth:default');
	}

	public function render()
	{
		$this->template->setFile(__DIR__.'/RegistrationForm.latte');
		$this->template->render();
	}
}

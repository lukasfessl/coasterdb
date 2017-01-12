<?php

namespace App\Modules\AdminModule\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nette\Application\UI\Control;
use Nette\Utils\Strings;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use App\Modules\AdminModule\Components\RegistrationForm;


class AuthPresenter extends Nette\Application\UI\Presenter
{

	protected $managementService;

	protected function startup() {
		parent::startup();
		if ($this->getUser()->isLoggedIn()) {
			$this->redirect('Homepage:default');
		}
		$this->setLayout('AuthLayout');
		$this->managementService = $this->context->getService('managementService');
	}


	public function createComponentLoginForm()
	{
		$form = new Form();
		$form->addText('email', 'E-mail:')->setAttribute('autofocus');
		$form->addPassword('password', 'Password:');
		$form->addSubmit('login', 'Login')->setAttribute('class', 'uk-button');
		$form->onSuccess[] = $this->login;

		return $form;
	}

	public function login($form)
	{
		$authenticator = $this->context->getService('authService');

		$username = $form['email']->value;
		$password = $form['password']->value;

		$user = $this->getUser();
		$user->setAuthenticator($authenticator);

		try {
			$user->login($username, $password);
			$user->setExpiration('120 minutes', FALSE);
			$this->redirect("Homepage:default");
		}  catch (AuthenticationException $e) {
			if($e->getCode() == IAuthenticator::INVALID_CREDENTIAL) {
				$form->addError("Incorrect email or password");
			}
			else {
				$form->addError("Login error !!!");
			}
		}
	}


	public function renderRegistraion() {

	}

	public function createComponentRegistrationForm() {
		return new RegistrationForm($this->managementService);
	}

}

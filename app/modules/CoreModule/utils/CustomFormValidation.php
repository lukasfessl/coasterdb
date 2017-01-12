<?php 

class CustomFormValidation
{
	const EMAIL_NOT_EXIST = 'CustomFormValidation::checkEmailExist';
	const EQUEL = 'CustomFormValidation::equel';
	
	public static function checkEmailExist($input, $managementService)
	{
		$user = $managementService->getUserByEmail($input->value);
		if ($user == null) {
			return true;
		}
		return false;
	}
	
	public static function equel($input, $value)
	{
		if ($input->value == $value) {
			return true;
		}
		
		return false;
	}

}
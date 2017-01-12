<?php

namespace App\Modules\Core\Utils;


abstract class Password extends \Nette\Object
{
	public static function hash($password, $salt = NULL)
	{
		$password = sha1($password.$salt);

		return $password;
	}
}
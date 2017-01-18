<?php

namespace App\Modules\Core\Utils;


abstract class Hash extends \Nette\Object
{
	public static function sha1($string, $salt = NULL)
	{
		$string = sha1($string.$salt);

		return $string;
	}
}
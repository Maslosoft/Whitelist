<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 20.07.18
 * Time: 13:51
 */

namespace Maslosoft\Whitelist\Helpers;

use Maslosoft\Whitelist\Interfaces\TokenInterface;

class ErrorCollector
{
	private $errors = [];
	public function forbidden(TokenInterface $token)
	{

	}

	public function required(TokenInterface $token)
	{

	}
}
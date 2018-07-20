<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 20.07.18
 * Time: 13:20
 */

namespace Maslosoft\Whitelist\Checkers;


use Exception;
use Maslosoft\Whitelist\Interfaces\CheckerInterface;

class FunctionChecker extends AbstractChecker implements CheckerInterface
{
	public function setTokenizer()
	{
		throw new Exception('Not implemented');
	}
}
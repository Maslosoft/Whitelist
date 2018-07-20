<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 20.07.18
 * Time: 14:02
 */

namespace Maslosoft\Whitelist\Checkers;


use function codecept_debug;
use Maslosoft\Whitelist\Helpers\ErrorCollector;
use Maslosoft\Whitelist\Interfaces\CheckerInterface;
use Maslosoft\Whitelist\Tokenizer\Tokenizer;
use Maslosoft\Whitelist\Whitelist;

class StaticMethodChecker extends AbstractChecker implements CheckerInterface
{
	public function check(Whitelist $list, Tokenizer $tokenizer, ErrorCollector $ec)
	{
		codecept_debug("Check methods? No.");
		return false;
	}

}
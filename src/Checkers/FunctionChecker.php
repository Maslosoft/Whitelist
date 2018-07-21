<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 20.07.18
 * Time: 13:20
 */

namespace Maslosoft\Whitelist\Checkers;

use Maslosoft\Whitelist\Helpers\ErrorCollector;
use Maslosoft\Whitelist\Helpers\ListNormalizer;
use Maslosoft\Whitelist\Interfaces\CheckerInterface;
use Maslosoft\Whitelist\Tokenizer\Tokenizer;
use Maslosoft\Whitelist\Whitelist;

class FunctionChecker extends AbstractChecker implements CheckerInterface
{
	public function check(Whitelist $list, Tokenizer $tokenizer, ErrorCollector $ec)
	{
		$result = true;
		$allowed = ListNormalizer::normalize($list->whitelist['functions']);

		foreach ($tokenizer->getFunctions() as $token)
		{
			$this->logger->debug("Allowed function: $token->value");
			if (!empty($allowed[$token->value]))
			{
				continue;
			}
			$ec->forbidden($token);
			$this->logger->info("Forbidden function: $token->value");
			$result = false;
		}
		return $result;
	}

}
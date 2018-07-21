<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 20.07.18
 * Time: 14:02
 */

namespace Maslosoft\Whitelist\Checkers;

use Maslosoft\Whitelist\Helpers\ErrorCollector;
use Maslosoft\Whitelist\Helpers\ListNormalizer;
use Maslosoft\Whitelist\Interfaces\CheckerInterface;
use Maslosoft\Whitelist\Tokenizer\Tokenizer;
use Maslosoft\Whitelist\Whitelist;

class StaticMethodChecker extends AbstractChecker implements CheckerInterface
{
	public function check(Whitelist $list, Tokenizer $tokenizer, ErrorCollector $ec)
	{
		$result = true;
		$allowed = ListNormalizer::normalize($list->whitelist['methods']);

		foreach ($tokenizer->getStaticMethodCalls() as $token)
		{
			$this->logger->debug("Allowed static method: $token->value");
			if (!empty($allowed[$token->value]))
			{
				continue;
			}
			$ec->forbidden($token);
			$this->logger->info("Forbidden static method: $token->value");
			$result = false;
		}
		return $result;
	}

}
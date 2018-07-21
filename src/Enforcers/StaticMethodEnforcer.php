<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 20.07.18
 * Time: 14:02
 */

namespace Maslosoft\Whitelist\Enforcers;

use function array_diff_key;
use Maslosoft\Whitelist\Checkers\AbstractChecker;
use Maslosoft\Whitelist\Helpers\ErrorCollector;
use Maslosoft\Whitelist\Helpers\ListNormalizer;
use Maslosoft\Whitelist\Interfaces\CheckerInterface;
use Maslosoft\Whitelist\Tokenizer\StaticMethod;
use Maslosoft\Whitelist\Tokenizer\Tokenizer;
use Maslosoft\Whitelist\Whitelist;

class StaticMethodEnforcer extends AbstractChecker implements CheckerInterface
{
	public function check(Whitelist $list, Tokenizer $tokenizer, ErrorCollector $ec)
	{
		$result = true;
		$required = ListNormalizer::normalize($list->required['methods']);

		$found = [];

		foreach ($tokenizer->getStaticMethodCalls() as $token)
		{
			if (!empty($required[$token->value]))
			{
				$found[$token->value];
				continue;
			}
		}

		$result = count($required) === count($found);

		if (!$result)
		{
			$diff = array_diff_key($required, $found);

			foreach ($diff as $value)
			{
				$token = new StaticMethod($value, $tokenizer->getTokens(), 0);
				$ec->required($token);
			}
		}

		return $result;
	}

}
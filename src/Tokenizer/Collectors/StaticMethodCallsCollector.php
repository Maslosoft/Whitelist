<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 20.07.18
 * Time: 14:21
 */

namespace Maslosoft\Whitelist\Tokenizer\Collectors;


use Maslosoft\Whitelist\Interfaces\TokenCollectorInterface;
use Maslosoft\Whitelist\Tokenizer\Composite\StaticMethod;
use const T_DOUBLE_COLON;
use const T_STRING;

class StaticMethodCallsCollector implements TokenCollectorInterface
{
	public function collect($tokens)
	{
		$result = [];
		foreach ($tokens as $index => $token)
		{
			// Skip all except double colon
			if($token->not(T_DOUBLE_COLON))
			{
				continue;
			}
			$prev = $token->prev();
			$next = $token->next();

			// Should start with class name
			if($prev->not(T_STRING))
			{
				continue;
			}

			// Should have method name
			if($next->not(T_STRING))
			{
				continue;
			}

			$finishing = $next->next();

			// Should have opening bracket after method name
			if($finishing->value !== '(')
			{
				continue;
			}

			// TODO Resolve use statements
			$className = $prev->value;

			$methodName = $next->value;
			$name = sprintf('%s::%s', $className, $methodName);

			$result[] = new StaticMethod($name,$tokens, $index);
		}
		return $result;
	}

}
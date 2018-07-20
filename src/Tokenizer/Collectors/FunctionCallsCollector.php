<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 20.07.18
 * Time: 14:16
 */

namespace Maslosoft\Whitelist\Tokenizer\Collectors;


use Maslosoft\Whitelist\Interfaces\TokenCollectorInterface;
use Maslosoft\Whitelist\Interfaces\TokenInterface;
use Maslosoft\Whitelist\Tokenizer\Composite\FunctionCall;
use const T_DO;
use const T_DOUBLE_COLON;
use const T_ECHO;
use const T_EVAL;
use const T_EXIT;
use const T_FOR;
use const T_FOREACH;
use const T_HALT_COMPILER;
use const T_OBJECT_OPERATOR;
use const T_PRINT;
use const T_RETURN;
use const T_WHILE;

class FunctionCallsCollector implements TokenCollectorInterface
{
	public function collect($tokens)
	{
		$result = [];
		foreach ($tokens as $index => $token)
		{
			// Check for exists/returns, eval's, echo and other constructs
			$toCheck = [
				// Eval
				T_EVAL,

				// Echo constructs
				T_ECHO,
				T_PRINT,

				// Finishing instructions
				T_RETURN,
				T_EXIT,
				T_HALT_COMPILER,

				// Loops
				T_FOR,
				T_FOREACH,
				T_WHILE,
				T_DO
			];

			foreach($toCheck as $type)
			{
				if($token->is($type))
				{
					$result[] = $token;
					continue 2;
				}
			}

			// Looks not looks like a function then skip
			if (!$token->valIs('('))
			{
				continue;
			}

			// Get actual token rather than `(`
			$token = $token->prev();

			// If not string or variable, might be control operator, skip
			// Variable might be used as variable function name, ie $name()
			// This could be also `]` when it's array member
			if ($token->not(T_STRING) && $token->not(T_VARIABLE) && !$token->valIs(']'))
			{
				continue;
			}

			// Get token before function name
			$prev = $token->prev();

			// Method call
			if($prev->is(T_OBJECT_OPERATOR))
			{
				continue;
			}

			// Static method call
			if($prev->is(T_DOUBLE_COLON))
			{
				continue;
			}

			// Check if is simple function
			// By checking what's before name
			// Exclude array members calls here, as it is special case
			if (
				$prev->not(T_DOUBLE_COLON) &&
				$prev->not(T_NEW) &&
				$prev->not(T_OBJECT_OPERATOR) &&
				!$token->valIs(']')
			)
			{
				$result[] = $token;
				continue;
			}

			// Check for array member call
			if ($token->valIs(']'))
			{
				$arr = $token;

				$isClosed = false;

				$name = [];
				// Search back to find variable name of this array
				// And whole expression value
				// Need to check for `[$variable]` too
				//
				// If could not find variable, bogus code? Break loop.
				// This would be code like only `]()`
				while ($arr->not(TokenInterface::TypeEmpty))
				{
					$name[] = $arr->value;
					if ($arr->valIs(']'))
					{
						$isClosed = false;
					}
					if ($arr->valIs('['))
					{
						$isClosed = true;
					}
					$arr = $arr->prev();
					if ($arr->is(T_VARIABLE) && $isClosed)
					{
						$name[] = $arr->value;
						break;
					}
				}
				$tmp = implode('', array_reverse($name));
				$result[] = new FunctionCall($tmp, $tokens, $index);
			}
		}
		return $result;
	}

}
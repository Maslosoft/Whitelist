<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 20.07.18
 * Time: 14:21
 */

namespace Maslosoft\Whitelist\Tokenizer\Collectors;


use function array_unshift;
use function implode;
use Maslosoft\Whitelist\Interfaces\TokenCollectorInterface;
use Maslosoft\Whitelist\Interfaces\TokenInterface;
use Maslosoft\Whitelist\Tokenizer\Composite\StaticMethod;
use const T_DOUBLE_COLON;
use const T_NEW;
use const T_OBJECT_OPERATOR;
use const T_STRING;
use const T_VARIABLE;

class MethodCallsCollector implements TokenCollectorInterface
{
	public function collect($tokens)
	{
		$result = [];
		foreach ($tokens as $index => $token)
		{
			// Skip all except object operator
			if($token->not(T_OBJECT_OPERATOR))
			{
				continue;
			}
			$prev = $token->prev();
			$next = $token->next();

			// Should start with variable OR closing parenthesis
			// eg:
			// $object->call()
			// (new Object)->call()
			// $objects[1]->call()
			if($prev->not(T_VARIABLE) xor $prev->value !== ')' xor $prev->value !== ']')
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
			$className = [];
			$className[] = $prev->value;

			// Case of (new Type)->method() call
			$this->resolve($className, $prev, '(', ')');

			// Case of [Something]->call()
			$this->resolve($className, $prev, '[', ']');

			// Method call result, eg:
			// myFunc()->method()
			// This does not (yet?) support nested calls like:
			// EG.: Yii::app()->getClientScript()->registerScript()
			if(implode('', $className) === '()')
			{
				$function = $prev->prev()->prev();
				array_unshift($className, $function->value);
				$calledFrom = $function->prev();
				if($calledFrom->is(T_DOUBLE_COLON) || $calledFrom->is(T_OBJECT_OPERATOR))
				{
					array_unshift($className, $calledFrom->value);
					$parent = $calledFrom->prev();
					if($parent->is(T_STRING) || $parent->is(T_VARIABLE))
					{
						array_unshift($className,$parent->value);
					}
				}
			}

			$methodName = $next->value;
			$name = sprintf('%s->%s', implode($className), $methodName);

			$result[] = new StaticMethod($name,$tokens, $index);
		}
		return $result;
	}

	private function resolve(&$className, TokenInterface $prev, $opened, $until)
	{
		$opening = null;
		if($prev->value === $until)
		{
			$part = $prev->value;
			$opening = $prev;
			while($part !== $opened)
			{
				$opening = $opening->prev();
				if(empty($opening))
				{
					break;
				}
				$part = $opening->value;

				// Ensure space after `new`
				if($opening->is(T_NEW))
				{
					$part = "$part ";
				}
				array_unshift($className, $part);
			}
		}
		if(empty($opening))
		{
			return;
		}
		$preOpening = $opening->prev();
		if($preOpening->is(T_VARIABLE))
		{
			array_unshift($className, $preOpening->value);
		}
	}
}
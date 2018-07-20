<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Whitelist\Tokenizer;

use Maslosoft\Whitelist\Interfaces\TokenInterface;
use Maslosoft\Whitelist\Tokenizer\Composite\FunctionCall;
use Maslosoft\Whitelist\Tokenizer\Tokens\SimpleToken;
use Maslosoft\Whitelist\Tokenizer\Tokens\Token;

/**
 * Tokenizer
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Tokenizer
{

	private $rawTokens = [];

	/**
	 *
	 * @var TokenInterface[]
	 */
	private $tokens = [];

	public function __construct($code)
	{
		$this->rawTokens = token_get_all($code);
		foreach ($this->rawTokens as $index => $token)
		{
			if (is_array($token))
			{
				$this->tokens[$index] = new Token($token, $this->tokens, $index);
			}
			else
			{
				$this->tokens[$index] = new SimpleToken($token, $this->tokens, $index);
			}
			$this->tokens[$index]->name = token_name($this->tokens[$index]->type);
		}
	}

	/**
	 *
	 * @return TokenInterface[];
	 */
	public function getTokens()
	{
		return $this->tokens;
	}

	/**
	 * Get function tokens. This includes:
	 *
	 * * Simple functions
	 * * eval
	 * * echo
	 * * print
	 *
	 *  This does not include:
	 *
	 * * Method calls
	 * * Variable name function calls
	 * * Requires and includes
	 *
	 * @return TokenInterface[]|Token[]|SimpleToken[]
	 */
	public function getFunctions()
	{
		$result = [];
		foreach ($this->tokens as $index => $token)
		{
			// Check for eval first
			if ($token->is(T_EVAL))
			{
				$result[] = $token;
				continue;
			}

			if ($token->is(T_ECHO))
			{
				$result[] = $token;
				continue;
			}

			if ($token->is(T_PRINT))
			{
				$result[] = $token;
				continue;
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
				$result[] = new FunctionCall($tmp, $this->tokens, $index);
			}
		}
		return $result;
	}

}

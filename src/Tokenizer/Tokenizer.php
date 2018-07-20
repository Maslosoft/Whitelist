<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Whitelist\Tokenizer;

use Maslosoft\Whitelist\Interfaces\TokenInterface;
use Maslosoft\Whitelist\Tokenizer\Collectors\FunctionCallsCollector;
use Maslosoft\Whitelist\Tokenizer\Collectors\MethodCallsCollector;
use Maslosoft\Whitelist\Tokenizer\Collectors\StaticMethodCallsCollector;
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
		return (new FunctionCallsCollector)->collect($this->tokens);
	}

	/**
	 * Get static method calls
	 * @return array|TokenInterface[]
	 */
	public function getStaticMethodCalls()
	{
		return (new StaticMethodCallsCollector)->collect($this->tokens);
	}

	public function getMethodCalls()
	{
		return (new MethodCallsCollector)->collect($this->tokens);
	}
}

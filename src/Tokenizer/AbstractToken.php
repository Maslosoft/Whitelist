<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Whitelist\Tokenizer;

use Maslosoft\Whitelist\Interfaces\TokenInterface;
use Maslosoft\Whitelist\Tokenizer\Tokens\EmptyToken;

/**
 * AbstractToken
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
abstract class AbstractToken
{

	public $line = 0;
	public $type = '';
	public $name = '';
	public $value = '';

	/**
	 *
	 * @var TokenInterface[]
	 */
	protected $tokens = [];
	protected $index = 0;

	//abstract public function __construct(&$data, &$tokens, $index);

	/**
	 *
	 * @param int $type
	 * @return bool
	 */
	public function is($type)
	{
		return $this->type === $type;
	}

	/**
	 *
	 * @param int $type
	 * @return bool
	 */
	public function not($type)
	{
		return $this->type !== $type;
	}

	/**
	 * Check token value
	 * @param string $value
	 */
	public function valIs($value)
	{
		return $this->value === $value;
	}

	/**
	 * Get token value
	 * @param string $value
	 */
	public function val()
	{
		return $this->value;
	}

	/**
	 * Get previous token, ignoring whitespace
	 * @return TokenInterface
	 */
	public function prev()
	{
		$i = $this->index - 1;
		if (isset($this->tokens[$i]))
		{
			$token = $this->tokens[$i];
			while ($token->is(T_WHITESPACE))
			{
				$token = $token->prev();
			}
			return $token;
		}
		return new EmptyToken();
	}

	/**
	 * Get next token, ignoring whitespace
	 * @return TokenInterface
	 */
	public function next()
	{
		$i = $this->index + 1;
		if (isset($this->tokens[$i]))
		{
			$token = $this->tokens[$i];
			while ($token->is(T_WHITESPACE))
			{
				$token = $token->next();
			}
			return $token;
		}
		return new EmptyToken();
	}

}

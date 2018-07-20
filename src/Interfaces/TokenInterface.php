<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Whitelist\Interfaces;

use Maslosoft\Whitelist\Tokenizer\Tokens\SimpleToken;
use Maslosoft\Whitelist\Tokenizer\Tokens\Token;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 *
 * @property int $line
 * @property string $type
 * @property string $name
 * @property string $value
 *
 */
interface TokenInterface
{

	const TypeSimple = 666;
	const TypeEmpty = 777;
	const TypeFunction = 888;
	const TypeStaticMethod = 999;

	/**
	 * Get token value
	 * @param string $value
	 */
	public function val();

	public function valIs($value);

	public function is($type);

	public function not($type);

	/**
	 * @return TokenInterface|Token|SimpleToken
	 */
	public function prev();

	/**
	 * @return TokenInterface|Token|SimpleToken
	 */
	public function next();
}

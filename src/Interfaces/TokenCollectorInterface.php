<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 20.07.18
 * Time: 14:14
 */

namespace Maslosoft\Whitelist\Interfaces;


interface TokenCollectorInterface
{
	/**
	 * Collect tokens of particular type and return those types
	 * @param TokenInterface[] $tokens
	 * @return TokenInterface[];
	 */
	public function collect($tokens);
}
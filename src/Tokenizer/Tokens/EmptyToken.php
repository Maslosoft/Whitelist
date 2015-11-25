<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Whitelist\Tokenizer\Tokens;

use Maslosoft\Whitelist\Interfaces\TokenInterface;
use Maslosoft\Whitelist\Tokenizer\AbstractToken;

/**
 * EmptyToken
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class EmptyToken extends AbstractToken implements TokenInterface
{

	public function __construct()
	{
		$this->type = TokenInterface::TypeEmpty;
		$this->value = '';
	}

}

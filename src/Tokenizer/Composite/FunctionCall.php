<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Whitelist\Tokenizer\Composite;

use Maslosoft\Whitelist\Interfaces\TokenInterface;
use Maslosoft\Whitelist\Tokenizer\AbstractToken;

/**
 * FunctionCall
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class FunctionCall extends AbstractToken implements TokenInterface
{

	public function __construct(&$data, &$tokens, $index)
	{
		$this->tokens = &$tokens;
		$this->index = $index;
		$this->type = TokenInterface::TypeFunction;
		$this->value = $data;
		$this->line = 0;
	}

}

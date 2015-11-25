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
 * Token
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Token extends AbstractToken implements TokenInterface
{

	public function __construct(&$data, &$tokens, $index)
	{
		$this->tokens = &$tokens;
		$this->index = $index;
		$this->type = $data[0];
		$this->value = $data[1];
		$this->line = $data[2];
	}

}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Whitelist;

use Maslosoft\Whitelist\Tokenizer\Tokenizer;

/**
 * Whitelist
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Whitelist
{

	use \Maslosoft\EmbeDi\Traits\FlyTrait;

	const DefaultInstanceId = 'whitelist';

	public function check($code)
	{
		$list = [
			'ucfirst' => true,
			'lcfirst' => true,
		];
		$t = new Tokenizer($code);
		foreach ($t->getFunctions() as $token)
		{
			if (isset($list[$token->value]))
			{
				continue;
			}
			return false;
		}
		return true;
	}

	public function checkFile($fileName)
	{

	}

}

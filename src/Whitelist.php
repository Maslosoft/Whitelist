<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Whitelist;

use function is_array;
use function is_string;
use Maslosoft\EmbeDi\EmbeDi;
use Maslosoft\EmbeDi\Traits\FlyTrait;
use Maslosoft\Whitelist\Tokenizer\Tokenizer;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

/**
 * Whitelist
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Whitelist implements LoggerAwareInterface
{

	use FlyTrait,
		LoggerAwareTrait;

	const DefaultInstanceId = 'whitelist';

	/**
	 *
	 * @param mixed[]|string $config Configuration array or instance id
	 */
	public function __construct($config = self::DefaultInstanceId)
	{
		assert(!empty($config));
		assert(is_string($config) || is_array($config));
		$this->logger = new NullLogger;

		if(is_string($config))
		{
			EmbeDi::fly($config)->configure($this);
		}
		else
		{
			EmbeDi::fly()->apply($config, $this);
		}
	}

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

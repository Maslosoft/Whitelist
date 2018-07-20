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
use Maslosoft\Whitelist\Checkers\FunctionChecker;
use Maslosoft\Whitelist\Interfaces\CheckerInterface;
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
	 * @var string[]|CheckerInterface[]
	 */
	public $checkers = [
		FunctionChecker::class
	];

	/**
	 * Allowed code features
	 * @var array
	 */
	public $whitelist = [];

	/**
	 * Required code features
	 * @var array
	 */
	public $required = [];

	/**
	 *
	 * @param mixed[]|string $config Configuration array or instance id
	 */
	public function __construct($config = self::DefaultInstanceId)
	{
		assert(!empty($config));
		assert(is_string($config) || is_array($config));
		$this->logger = new NullLogger;

		if (is_string($config))
		{
			EmbeDi::fly($config)->configure($this);
		}
		else
		{
			EmbeDi::fly()->apply($config, $this);
		}

		$checkers = [];
		foreach($this->checkers as $checker)
		{
			if(!$checker instanceof CheckerInterface)
			{
				assert(is_string($checker) || is_array($checker));
				$checkers[] = EmbeDi::fly()->apply($checker);
			}
		}
		$this->checkers = $checkers;
	}

	public function check($code)
	{
		// TODO Hardcoded list
		$list = [
			'ucfirst' => true,
			'lcfirst' => true,
		];
		$t = new Tokenizer($code);

		foreach($this->checkers as $checker)
		{
			assert($checker instanceof CheckerInterface);
		}

		// TODO; Refactor below into that above draft^
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

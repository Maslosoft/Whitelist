<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Whitelist;

use function array_sum;
use function count;
use function is_array;
use function is_string;
use Maslosoft\EmbeDi\EmbeDi;
use Maslosoft\EmbeDi\Traits\FlyTrait;
use Maslosoft\Whitelist\Checkers\FunctionChecker;
use Maslosoft\Whitelist\Checkers\MethodChecker;
use Maslosoft\Whitelist\Checkers\StaticMethodChecker;
use Maslosoft\Whitelist\Helpers\ErrorCollector;
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
		FunctionChecker::class,
		StaticMethodChecker::class,
		MethodChecker::class
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
	 * List of aspects to check
	 * @var array
	 */
	private $aspects = [
		'variables',
		'functions',
		'classes',
		'methods',
		'fields',
		'constants',
	];

	/**
	 * Version number holder
	 * @var string
	 */
	private static $_version = null;

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

		// Instantiate checkers if not already provided as instances
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

		// Ensure that all aspects have sane values
		foreach(['whitelist', 'require'] as $name)
		{
			foreach($this->aspects as $aspect)
			{
				if(empty($this->$name[$aspect]) || !is_array($this->$name[$aspect]))
				{
					$this->$name[$aspect] = [];
				}
			}
		}
	}

	public function check($code, ErrorCollector $ec = null)
	{
		$tokenizer = new Tokenizer($code);

		if(empty($ec))
		{
			$ec = new ErrorCollector;
		}

		$results = [];

		foreach($this->checkers as $checker)
		{
			assert($checker instanceof CheckerInterface);
			if($checker instanceof LoggerAwareInterface)
			{
				$checker->setLogger($this->logger);
			}
			$results[] = (int) $checker->check($this, $tokenizer, $ec);
		}
		return array_sum($results) === count($results);
	}

	public function checkFile($fileName)
	{

	}

	/**
	 * Get whitelist version
	 * @return string
	 */
	public function getVersion()
	{
		if (null === self::$_version)
		{
			self::$_version = require __DIR__ . '/version.php';
		}
		return self::$_version;
	}
}

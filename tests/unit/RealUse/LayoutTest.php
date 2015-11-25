<?php

namespace RealUse;

use Maslosoft\Whitelist\Tokenizer\Tokenizer;
use Maslosoft\Whitelist\Whitelist;
use UnitTester;

class LayoutTest extends \Codeception\TestCase\Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillFailSanitization()
	{
		$code = file_get_contents(__DIR__ . '/layout.php');

		// For dev help
		$t = new Tokenizer($code);
		$tokens = $t->getTokens();
		$funcs = $t->getFunctions();

		// Actual test
		$r = Whitelist::fly()->check($code);
		$this->assertFalse($r);
	}

}

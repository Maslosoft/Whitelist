<?php

namespace Allow;

use Maslosoft\Whitelist\Whitelist;
use UnitTester;

class StringFunctionsTest extends \Codeception\TestCase\Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillAllowUcFirst()
	{
		$code = <<<'CODE'
<?php
ucfirst('fooo');
CODE;
		$r = Whitelist::fly()->check($code);
		$this->assertTrue($r);
	}

}

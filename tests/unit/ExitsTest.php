<?php

use Maslosoft\Whitelist\Whitelist;

class ExitsTest extends \Codeception\TestCase\Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillForbidExit()
	{
		$code = <<<'CODE'
<?php
exit();
CODE;
		$r = Whitelist::fly()->check($code);
		$this->assertFalse($r);
	}

	public function testIfWillExitWithoutParenthesis()
	{
		$code = <<<'CODE'
<?php
exit;
CODE;
		$r = Whitelist::fly()->check($code);
		$this->assertFalse($r);
	}

	public function testIfWillForbidDie()
	{
		$code = <<<'CODE'
<?php
die();
CODE;
		$r = Whitelist::fly()->check($code);
		$this->assertFalse($r);
	}

	public function testIfWillDieWithoutParenthesis()
	{
		$code = <<<'CODE'
<?php
die;
CODE;
		$r = Whitelist::fly()->check($code);
		$this->assertFalse($r);
	}

	public function testIfWillForbidHaltCompiler()
	{
		$code = <<<'CODE'
<?php
__halt_compiler;
CODE;
		$r = Whitelist::fly()->check($code);
		$this->assertFalse($r);
	}

	public function testIfWillForbidAssert()
	{
		$code = <<<'CODE'
<?php
return $someVariable;
CODE;
		Whitelist::fly()->check($code);
	}
}

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

	/**
	 * @var Whitelist
	 */
	private $wl = null;

	protected function _before()
	{
		$config = [
			'whitelist' => [
				'functions' => [
					'lcfirst',
					'ucfirst'
				]
			]
		];
		$this->wl = new Whitelist($config);
	}

	public function testIfWillAllowUcFirst()
	{
		$code = <<<'CODE'
<?php
ucfirst('fooo');
CODE;
		$r = $this->wl->check($code);
		$this->assertTrue($r);
	}

	public function testIfWillAllowUcFirstSpanningManyLines()
	{
		$code = <<<'CODE'
<?php
ucfirst
(
'hmmm'
);
CODE;
		$r = $this->wl->check($code);
		$this->assertTrue($r);
	}



	public function testIfWillAllowUcFirstSurroundedByIf()
	{
		$code = <<<'CODE'
<?php
if(true)
{
	ucfirst('fooo');
}
CODE;
		$r = $this->wl->check($code);
		$this->assertTrue($r);
	}

	public function testIfWillAllowUcFirstAndLcFirst()
	{
		$code = <<<'CODE'
<?php
ucfirst('fooo');
lcfirst('fooo');
CODE;
		$r = $this->wl->check($code);
		$this->assertTrue($r);
	}

	public function testIfWillForbidUcfirstAsMethodCall()
	{
		$code = <<<'CODE'
<?php
(new SomeInstance)->strlen('fooo');
CODE;
		$r = $this->wl->check($code);
		$this->assertFalse($r);
	}

	public function testIfWillForbidUcfirstAsStaticMethodCall()
	{
		$code = <<<'CODE'
<?php
SomeInstance::strlen('fooo');
CODE;
		$r = $this->wl->check($code);
		$this->assertFalse($r);
	}

	public function testIfWillForbidUcfirstAsVariableMethodCall()
	{
		$code = <<<'CODE'
<?php
$instance = new SomeInstance;
$instance->strlen('fooo');
CODE;
		$r = $this->wl->check($code);
		$this->assertFalse($r);
	}

	public function testIfWillForbidUcfirstAsArrayVariableMethodCall()
	{
		$code = <<<'CODE'
<?php
$instances = [new SomeInstance];
$instances[0]->strlen('fooo');
CODE;
		$r = $this->wl->check($code);
		$this->assertFalse($r);
	}

	public function testIfWillForbidStrlen()
	{
		$code = <<<'CODE'
<?php
strlen('fooo');
CODE;
		$r = $this->wl->check($code);
		$this->assertFalse($r);
	}

	public function testIfWillForbidStrlenWhenHavingUcfirstToo()
	{
		$code = <<<'CODE'
<?php
strlen('fooo');
ucfirst('booo!');
CODE;
		$r = $this->wl->check($code);
		$this->assertFalse($r);
	}
}

<?php

use Maslosoft\Whitelist\Whitelist;

class EvalsTest extends \Codeception\TestCase\Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillForbidEval()
	{
		$code = <<<'CODE'
<?php
eval();
CODE;
		$r = Whitelist::fly()->check($code);
		$this->assertFalse($r);
	}

	public function testIfWillForbidEvalWhiteSpaced()
	{
		$code = <<<'CODE'
<?php
eval
	();
CODE;
		$r = Whitelist::fly()->check($code);
		$this->assertFalse($r);
	}

	public function testIfWillForbidAssert()
	{
		$code = <<<'CODE'
<?php
assert();
CODE;
		Whitelist::fly()->check($code);
	}

	public function testIfWillForbidPregReplaceWithEvalModifier()
	{
		$code = <<<'CODE'
<?php
preg_replace('/.*/e','');
CODE;
		Whitelist::fly()->check($code);
	}

	public function testIfWillForbidCreateFunction()
	{
		$code = <<<'CODE'
<?php
create_function();
CODE;
		Whitelist::fly()->check($code);
	}

	public function testIfWillForbidInclude()
	{
		$code = <<<'CODE'
<?php
include('some.php');
CODE;
		Whitelist::fly()->check($code);
	}

	public function testIfWillForbidIncludeStringEncapsed()
	{
		$code = <<<'CODE'
<?php
include 'some.php';
CODE;
		Whitelist::fly()->check($code);
	}

	public function testIfWillForbidIncludeOnce()
	{
		$code = <<<'CODE'
<?php
include_once('some.php');
CODE;
		Whitelist::fly()->check($code);
	}

	public function testIfWillForbidIncludeOnceStringEncapsed()
	{
		$code = <<<'CODE'
<?php
include_once 'some.php';
CODE;
		Whitelist::fly()->check($code);
	}

	public function testIfWillForbidRequire()
	{
		$code = <<<'CODE'
<?php
require();
CODE;
		Whitelist::fly()->check($code);
	}

	public function testIfWillForbidRequireStringEncapsed()
	{
		$code = <<<'CODE'
<?php
require 'some.php';
CODE;
		Whitelist::fly()->check($code);
	}

	public function testIfWillForbidRequireOnce()
	{
		$code = <<<'CODE'
<?php
require_once();
CODE;
		Whitelist::fly()->check($code);
	}

	public function testIfWillForbidRequireOnceStringEncapsed()
	{
		$code = <<<'CODE'
<?php
require_once 'some.php';
CODE;
		Whitelist::fly()->check($code);
	}

	public function testIfWillForbidEvaluativeExpressions()
	{
		$code = <<<'CODE'
<?php
eval();
assert();
preg_replace('/.*/e','');
create_function();
include();
include_once();
require();
require_once();
$_GET['func_name']($_GET['argument']);
$func = new ReflectionFunction($_GET['func_name']); $func->invoke(); or $func->invokeArgs(array());
CODE;
		Whitelist::fly()->check($code);
	}

}

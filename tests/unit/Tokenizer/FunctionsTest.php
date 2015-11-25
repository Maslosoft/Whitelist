<?php

namespace Tokenizer;

use Maslosoft\Whitelist\Tokenizer\Tokenizer;
use UnitTester;

class FunctionsTest extends \Codeception\TestCase\Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillProperlyGetSimpleFunctionCalls()
	{
		$code = <<<'CODE'
<?php
	strpos();
	eval();
	echo 'ggg';
	print('dddd');
	$var = '';
	Someclass::static();

CODE;
		$funcs = [
			'echo',
			'eval',
			'print',
			'strpos',
		];
		$result = [];
		$t = new Tokenizer($code);
		$tokens = $t->getTokens();
		foreach ($t->getFunctions() as $token)
		{
			$result[] = $token->val();
		}
		sort($funcs);
		sort($result);
		$this->assertSame($funcs, $result);
	}

	public function testIfWillProperlyGetVariableNameFunctionCalls()
	{
		$code = <<<'CODE'
<?php
	$var();
	$_GET['ssss']['wsss']();
	$_GET['ssss'][$var]();
CODE;

		$funcs = [
			'$var',
			"\$_GET['ssss']['wsss']",
			"\$_GET['ssss'][\$var]",
		];

		$result = [];
		$t = new Tokenizer($code);
		$tokens = $t->getTokens();

		foreach ($t->getFunctions() as $token)
		{
			$result[] = $token->val();
		}

		sort($funcs);
		sort($result);
		$this->assertSame($funcs, $result);
	}

}

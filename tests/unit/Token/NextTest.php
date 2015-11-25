<?php

namespace Token;

use Maslosoft\Whitelist\Tokenizer\Tokenizer;
use Maslosoft\Whitelist\Tokenizer\Tokens\Token;
use UnitTester;

class NextTest extends \Codeception\TestCase\Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillFindNextDoubleColon()
	{
		$code = <<<'CODE'
  <?php
	  ClassName::method();
CODE;
		$t = new Tokenizer($code);
		$tokens = $t->getTokens();
		foreach ($tokens as $token)
		{
			if ($token->is(T_DOUBLE_COLON))
			{
				$token = $token->prev();
				break;
			}
		}
		$this->assertSame($token->val(), 'ClassName');

		// Should be normal token
		$this->assertInstanceOf(Token::class, $token->next());

		$this->assertSame($token->next()->type, T_DOUBLE_COLON);
		$this->assertTrue($token->next()->is(T_DOUBLE_COLON));
	}

	public function testIfWillFindNextDoubleColonWhiteSpaced()
	{
		$code = <<<'CODE'
  <?php
	  ClassName
	  ::
	  method();
CODE;
		$t = new Tokenizer($code);
		$tokens = $t->getTokens();
		foreach ($tokens as $token)
		{
			if ($token->is(T_DOUBLE_COLON))
			{
				$token = $token->prev();
				break;
			}
		}
		$this->assertSame($token->val(), 'ClassName');

		// Should be normal token
		$this->assertInstanceOf(Token::class, $token->next());

		$this->assertSame($token->next()->type, T_DOUBLE_COLON);
		$this->assertTrue($token->next()->is(T_DOUBLE_COLON));
	}

}

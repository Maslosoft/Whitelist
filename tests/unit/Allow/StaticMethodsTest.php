<?php

namespace Allow;


use Maslosoft\Whitelist\Whitelist;

class StaticMethodsTest extends \Codeception\TestCase\Test
{
	/**
	 * @var \UnitTester
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
    			'methods' => [
    				'NonNamespaced::widget'
				]
			]
		];
		$this->wl = new Whitelist($config);
	}

	protected function _after()
	{
	}

	// tests
	public function testAllowedNonNamespacedClass()
	{
		$code = <<<CODE
<?php
NonNamespaced::widget();
CODE;
		$this->assertTrue($this->wl->check($code));
	}

	public function testAllowedNonNamespacedClassSpanningManyLines()
	{
		$code = <<<CODE
<?php
NonNamespaced
			::
			widget(
				[

				]
			);
CODE;
		$this->assertTrue($this->wl->check($code));


	}

	public function testAllowedAndNotAllowedNonNamespacedClasses()
	{
		$code = <<<CODE
<?php
NonNamespaced::widget();
OtherOne::widget();
CODE;
		$this->assertFalse($this->wl->check($code));
	}

	public function testNotAllowedNonNamespacedClass()
	{
		$code = <<<CODE
<?php
OtherOne::widget();
CODE;
		$this->assertFalse($this->wl->check($code));
	}
}
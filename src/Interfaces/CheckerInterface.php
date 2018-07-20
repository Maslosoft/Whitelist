<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 20.07.18
 * Time: 13:13
 */

namespace Maslosoft\Whitelist\Interfaces;


use Maslosoft\Whitelist\Helpers\ErrorCollector;
use Maslosoft\Whitelist\Tokenizer\Tokenizer;
use Maslosoft\Whitelist\Whitelist;
use Psr\Log\LoggerAwareInterface;

interface CheckerInterface extends LoggerAwareInterface
{
	public function check(Whitelist $list, Tokenizer $tokenizer, ErrorCollector $ec);
}
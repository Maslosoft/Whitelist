<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 20.07.18
 * Time: 13:13
 */

namespace Maslosoft\Whitelist\Interfaces;


use Psr\Log\LoggerAwareInterface;

interface CheckerInterface extends LoggerAwareInterface
{
	public function setTokenizer();
}
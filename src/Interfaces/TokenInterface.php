<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Whitelist\Interfaces;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface TokenInterface
{

	const TypeSimple = 666;
	const TypeEmpty = 777;

	/**
	 * Get token value
	 * @param string $value
	 */
	public function val();

	public function valIs($value);

	public function is($type);

	public function not($type);

	public function prev();

	public function next();
}

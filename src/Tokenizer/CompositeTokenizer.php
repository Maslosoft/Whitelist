<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Whitelist\Tokenizer;

/**
 * Composite Tokenizer
 *
 * This is higher level of abstraction of Tokenizer.
 *
 * It should allow getting composite tokens, ie:
 *
 * $variable->methodCall();
 * $variable->field;
 *
 * ClassName::methodCall();
 * ClassName::constant();
 *
 * $_GET['b']['c']();
 *
 * use statements etc.
 *
 * It also allow getting normal token
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class CompositeTokenizer extends Tokenizer
{

}

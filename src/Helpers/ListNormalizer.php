<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 20.07.18
 * Time: 13:56
 */

namespace Maslosoft\Whitelist\Helpers;


use function is_bool;
use function is_numeric;
use function is_string;

class ListNormalizer
{
	/**
	 * Normalize list values
	 * @param string[] $list
	 */
	public static function normalize(array $list)
	{
		$proper = [];
		foreach($list as $key => $value)
		{
			if(is_string($key) && is_bool($value))
			{
				$proper[$key] = $value;
			}
			elseif(is_numeric($key) && is_string($value))
			{
				$proper[$value] = true;
			}
			else
			{
				assert(false, "Unsupported list");
			}
		}
		return $proper;
	}
}
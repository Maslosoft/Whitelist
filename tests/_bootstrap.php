<?php
// This is global bootstrap for autoloading
use Maslosoft\Whitelist\Whitelist;

echo "Whitelist " . (new Whitelist())->getVersion() . PHP_EOL;
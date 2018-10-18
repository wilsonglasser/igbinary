--TEST--
Object test, __autoload
--SKIPIF--
--FILE--
<?php
if(!extension_loaded('igbinary')) {
	dl('igbinary.' . PHP_SHLIB_SUFFIX);
}

function test($type, $variable, $test) {
	$serialized = pack('H*', $variable);
	$unserialized = igbinary_unserialize($serialized);

	echo $type, "\n";
	echo substr(bin2hex($serialized), 8), "\n";
	echo $test || $unserialized->b == 2 ? 'OK' : 'ERROR';
	echo "\n";
}

function test_autoload($classname) {
	class Obj {
		var $a;
		var $b;

		function __construct($a, $b) {
			$this->a = $a;
			$this->b = $b;
		}
	}
}
spl_autoload_register("test_autoload");

test('autoload', '0000000217034f626a140211016106011101620602', false);

/*
 * you can add regression tests for your extension here
 *
 * the output of your test code has to be equal to the
 * text in the --EXPECT-- section below for the tests
 * to pass, differences between the output and the
 * expected text are interpreted as failure
 *
 * see TESTING.md for further information on
 * writing regression tests
 */
?>
--EXPECT--
autoload
17034f626a140211016106011101620602
OK

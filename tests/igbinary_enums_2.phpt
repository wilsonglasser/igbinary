--TEST--
Test unserializing valid and invalid enums
--SKIPIF--
<?php if (PHP_VERSION_ID < 80100) { echo "skip enums requires php 8.1"; } ?>
--FILE--
<?php

class ABCD {
}

enum Suit {
    case Hearts;
    case Diamonds;
    case Spades;
    case Clubs;
    const HEARTS = self::Hearts;
}
$arr = ['Hearts' => Suit::Hearts];
$arr[1] = &$arr['Hearts'];
$serArray = igbinary_serialize($arr);
echo urlencode($serArray), "\n";
$result = igbinary_unserialize($serArray);
var_dump($result);
$result[1] = 'new';
var_dump($result);
$serInvalid = str_replace('Hearts', 'HEARTS', $serArray);
var_dump(igbinary_unserialize($serInvalid));

$serInvalidConst = str_replace('Hearts', 'vvvvvv', $serArray);
var_dump(igbinary_unserialize($serInvalidConst));

$serMissingClass = str_replace('Suit', 'Club', $serArray);
var_dump(igbinary_unserialize($serMissingClass));

$serInvalidClass = str_replace('Suit', 'ABCD', $serArray);
var_dump(igbinary_unserialize($serInvalidClass));
?>
--EXPECTF--
%00%00%00%02%14%02%11%06Hearts%25%17%04Suit%27%0E%00%06%01%25%22%01
array(2) {
  ["Hearts"]=>
  &enum(Suit::Hearts)
  [1]=>
  &enum(Suit::Hearts)
}
array(2) {
  ["Hearts"]=>
  &string(3) "new"
  [1]=>
  &string(3) "new"
}

Warning: igbinary_unserialize_object_enum_case: Suit::HEARTS is not an enum case in /home/tyson/programming/igbinary/tests/igbinary_enums_2.php on line 22
NULL

Warning: igbinary_unserialize_object_enum_case: Undefined constant Suit::vvvvvv in /home/tyson/programming/igbinary/tests/igbinary_enums_2.php on line 25
NULL

Warning: igbinary_unserialize_object_enum_case: Class 'Club' does not exist in /home/tyson/programming/igbinary/tests/igbinary_enums_2.php on line 28
NULL

Warning: igbinary_unserialize_object_enum_case: Class 'ABCD' is not an enum in /home/tyson/programming/igbinary/tests/igbinary_enums_2.php on line 31
NULL
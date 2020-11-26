--TEST--
igbinary object with reference to ArrayObject
--FILE--
<?php

class UnSerializable implements Serializable
{
    public function serialize() {
        echo "Called serialize\n";
    }
    public function unserialize($serialized) {
        echo "Called unserialize\n";
    }
}

$unser = new UnSerializable();
$arr = [$unser];
$arr[1] = &$arr[0];
$arr[2] = 'endcap';
$arr[3] = &$arr[2];

$data = igbinary_serialize($arr);
echo urlencode($data) . PHP_EOL;
$recovered = igbinary_unserialize($data);
var_dump($recovered);
?>
--EXPECT--
Called serialize
%00%00%00%02%14%04%06%00%25%00%06%01%25%22%01%06%02%25%11%06endcap%06%03%25%01%02
array(4) {
  [0]=>
  &NULL
  [1]=>
  &NULL
  [2]=>
  &string(6) "endcap"
  [3]=>
  &string(6) "endcap"
}
<?php

use Yaf\Controller_Abstract as Controller;

class TestController extends Controller
{
    public function numhash($number)
    {
        return (((0x0000FFFF & $number) << 16) + ((0xFFFF0000 & $number) >> 16));
    }

    public function testAction()
    {
        $value = 1000;
        while ($value <= 10000) {
            $test   = $this->numhash($value - 1);
            $test_1 = $this->numhash($value);
            echo $value . '=>' . $test . '===' . ($test_1 - $test) . '<br>';
            $value++;
        }
    }

}

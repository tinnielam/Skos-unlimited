<?php
/**
 * Created by PhpStorm.
 * User: thientinlamngoc
 * Date: 27-06-16
 * Time: 23:28
 */

namespace home;


require 'vendor/autoload.php';
require_once 'functie.php';


class FunctieTest extends \PHPUnit_Framework_TestCase {


    public function testPushAndPop()
    {
        $stack = [];
        $this->assertEquals(0, count($stack));

        array_push($stack, 'foo');
        $this->assertEquals('foo', $stack[count($stack)-1]);
        $this->assertEquals(1, count($stack));

        $this->assertEquals('foo', array_pop($stack));
        $this->assertEquals(0, count($stack));
    }




}

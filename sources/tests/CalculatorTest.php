<?php
/**
 * Created by PhpStorm.
 * User: julian
 * Date: 23.03.2016
 * Time: 16:45
 */

namespace HsBremen\WebApi\Tests;
use HsBremen\WebApi\Calculator;


class CalculatorTest extends \PHPUnit_Framework_TestCase
{
   // Addition

    /**
     * @var Calculator
     */
    private $calc;
    public function setUp()
    {
        $this->calc = new Calculator();
    }

    /**
     * @test
     * @dataProvider provideDataForAdd
     */
    public function add($expected, $a, $b)
    {
        $calc = new Calculator();
        $this->assertEquals($expected,$calc->add($a,$b));
    }

    public function provideDataForAdd()
    {
        return [
            [2,1,1],
            [125,0,125],
            [125,125,0],
            [-125,0,-125],
            [-125,-125,0],
        ];
    }

    /**
     * @test
     */
    public function outOfRangeException()
    {
        $this->getExpectedException();
        $calc = new Calculator();
        $calc->add(PHP_INT_MAX,1);
    }


    // Subtraction







}











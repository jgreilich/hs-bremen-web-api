<?php

use HsBremen\WebApi\Entity\DateTimeHelper;

class DateTimeHelperTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     * @dataProvider spec_strings
     */
    public function shouldConvertForwardAndBackward($spec_string)
    {
        $conv_spec_string = DateTimeHelper::dateIntervalToString(new DateInterval($spec_string));

        $this->assertEquals($spec_string,$conv_spec_string, "\"$conv_spec_string\" is not \"$spec_string\" anymore.");
    }


    public function spec_strings()
    {
        return [
            ["P1Y2M3DT4H5M6S"],
            ["P2D"],
            ["PT2S"]
        ];
    }


    /**
     * @test
     */
    public function shouldReturnNullWhenNullGiven()
    {
        $this->isNull(DateTimeHelper::dateIntervalToString(null));
    }
    
   


    
    
}

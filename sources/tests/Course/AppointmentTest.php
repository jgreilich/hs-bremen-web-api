<?php

use HsBremen\WebApi\Entity\Appointment;
use HsBremen\WebApi\Entity\InvalidAppointmentException;

class AppointmentTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     * @dataProvider exceptionCausingValues
     */
    public function shouldThrowExceptions($description, DateTime $dtstart, DateTime $dtend = null, DateInterval $duration = null, $freq, DateTime $until = null, $count = null, $interval=null, $id=null)
    {
        $this->setExpectedException(InvalidAppointmentException::class);
        
        $appmnt = new Appointment($description,$dtstart,$dtend,$duration,$freq,$until,$count,$interval,$id);
    }

    public function exceptionCausingValues()
    {
        return [
            // desc, dtstart, dtend, dur, freq, until, count, interval, id
            'dtend or duration null' => ["", new DateTime(), new DateTime() , new DateInterval('PT2H'), null                        , null          , null  , null  , 1],
            'until or count null'    => ["", new DateTime(), null           , new DateInterval('PT2H'), Appointment::$freqencies[0] , new DateTime(), 19    , 2     , 1],
            'Invalid Frequency'      => ["", new DateTime(), null           , new DateInterval('PT2H'), "ERROR"                     , new DateTime(), null  , 2     , 1]
        ];
    }
    
}

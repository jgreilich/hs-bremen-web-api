<?php

namespace HsBremen\WebApi\Entity;


class Course implements \JsonSerializable
{
    private $id;
    private $weekday;
    private $starttime;
    private $endtime;

    public function __construct($id, $weekday = "monday", $starttime = "8:00", $endtime = "13:00")
    {
        $this->id = $id;
        $this->weekday = $weekday;
        $this->starttime = $starttime;
        $this->endtime = $endtime;
    }
    
    
    public function setWeekday($weekday)
    {
        $this->weekday = $weekday;
    }
    
    public function setStarttime($starttime)
    {
        $this->starttime = $starttime;
    }
    
    public function setEndtime($endtime)
    {
        $this->endtime = $endtime;
    }


    public function setId($id)
    {
        $this->id = $id;
    }
    
    function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'weekday' => $this->weekday,
            'starttime' => $this->starttime,
            'endtime' => $this->endtime,
        ];
    }

}
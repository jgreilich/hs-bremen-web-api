<?php
/**
 * Created by PhpStorm.
 * User: julian
 * Date: 04.05.2016
 * Time: 11:47
 */

namespace HsBremen\WebApi\Entity;


class Appointment implements \JsonSerializable
{
    public static $weekdays = array("So","Mo","Di","Mi","Do","Fr","Sa");

    // Name of the Appointment
    private $name;
    // Types: weekly, single
    private $type;
    // Starttime or Date
    private $start;
    // Endtime or Date
    private $end;


    public function __construct($name, DateTime $start, DateTime $end, $type = 'weekly')
    {
        $this->name = $name;
        $this->start = $start;
        $this->end = $end;
        $this->type = $type;
    }

    function jsonSerialize()
    {
        if($this->type == 'weekly'){
            $start_str = weekdays[$this->start->format('w')] . ", " . $this->start->format('H:i');
            $end_str = weekdays[$this->end->format('w')] . ", " . $this->end->format('H:i');
        } else {
            $start_str = $this->start->format("d.m.Y, H:i");
            $end_str = $this->end->format("d.m.Y, H:i");
        }
        return [
            'name' => $this->name,
            'type' => $this->type,
            'start' => $start_str,
            'end' => $end_str,
        ];
    }









}
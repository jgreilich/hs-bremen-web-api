<?php
/**
 * Created by PhpStorm.
 * User: julian
 * Date: 04.05.2016
 * Time: 11:47
 */

namespace HsBremen\WebApi\Entity;


use DateInterval;
use DateTime;

class Appointment implements \JsonSerializable
{
    public static $freqencies = array("SECONDLY", "MINUTELY", "HOURLY", "DAILY", "WEEKLY", "MONTHLY", "YEARLY");
    public static $weekdays = array("SU", "MO", "TU", "WE", "TH", "FR", "SA");

    /** @var int $id Appointment-ID */
    private $id;
    /** @var string $description*/
    private $description;
    /** @var DateTime $dtstart */
    private $dtstart;
    /** @var DateTime $dtend */
    private $dtend;
    /** @var DateInterval $duration  */
    private $duration;
    /** @var string $freq  */
    private $freq;
    /** @var DateTime $until */
    private $until;
    /** @var  int $count */
    private $count;
    /** @var  int $interval */
    private $interval;
    /** @var int $course_id */
    private $courseid;

    /**
     * Appointment constructor.
     * @param int $id
     * @param string $description
     * @param DateTime $dtstart
     * @param DateTime $dtend
     * @param DateInterval $duration
     * @param string $freq
     * @param DateTime $until
     * @param int $count
     * @param int $interval
     */
    public function __construct($description, DateTime $dtstart, DateTime $dtend = null, DateInterval $duration = null, $freq, DateTime $until = null, $count = null, $interval=null, $id=null)
    {
        $this->id = $id;
        $this->description = $description;
        $this->dtstart = $dtstart;
        $this->dtend = $dtend;
        $this->duration = $duration;
        $this->freq = $freq;
        $this->until = $until;
        $this->count = $count;
        $this->interval = $interval;
        
        $this->checkIntegrity();
    }

    /**
     * Check if the Appointment is valid.
     * @throws InvalidAppointmentException
     */
    public function checkIntegrity()
    {
        if (
                ($this->dtend != null && $this->duration != null) ||
                ($this->until != null && $this->count != null) ||
                ($this->freq != null && !in_array($this->freq, self::$freqencies))
            )
        {
            throw new InvalidAppointmentException();
        }
    }
    
    
    
    function icalSerialize(){
        // TODO: implement RFC5545
        return "not supported yet.";
    }
    
    
    function jsonSerialize()
    {
        if($this->type == 'weekly'){
            $start_str = Appointment::$weekdays[$this->start->format('w')] . ", " . $this->start->format('H:i');
            $end_str = Appointment::$weekdays[$this->end->format('w')] . ", " . $this->end->format('H:i');
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


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return DateTime
     */
    public function getDtstart()
    {
        return $this->dtstart;
    }

    /**
     * @param DateTime $dtstart
     */
    public function setDtstart($dtstart)
    {
        $this->dtstart = $dtstart;
    }

    /**
     * @return DateTime
     */
    public function getDtend()
    {
        return $this->dtend;
    }

    /**
     * @param DateTime $dtend
     */
    public function setDtend($dtend)
    {
        $this->dtend = $dtend;
    }

    /**
     * @return DateInterval
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param DateInterval $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * @return string
     */
    public function getFreq()
    {
        return $this->freq;
    }

    /**
     * @param string $freq
     */
    public function setFreq($freq)
    {
        $this->freq = $freq;
    }

    /**
     * @return DateTime
     */
    public function getUntil()
    {
        return $this->until;
    }

    /**
     * @param DateTime $until
     */
    public function setUntil($until)
    {
        $this->until = $until;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * @return int
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * @param int $interval
     */
    public function setInterval($interval)
    {
        $this->interval = $interval;
    }

    /**
     * @return mixed
     */
    public function getCourseid()
    {
        return $this->courseid;
    }

    /**
     * @param mixed $courseid
     */
    public function setCourseid($courseid)
    {
        $this->courseid = $courseid;
    }






}
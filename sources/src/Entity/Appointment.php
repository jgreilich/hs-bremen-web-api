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
use Swagger\Annotations as SWG;


/**
 * Class Appointment
 * @package HsBremen\WebApi\Entity
 * @SWG\Definition(
 *     definition="appointment",
 *     type="object"
 * )
 */

class Appointment implements \JsonSerializable
{
    public static $freqencies = array("SECONDLY", "MINUTELY", "HOURLY", "DAILY", "WEEKLY", "MONTHLY", "YEARLY");
    public static $weekdays = array("SU", "MO", "TU", "WE", "TH", "FR", "SA");

    /**
     * @var int $id Appointment-ID
     * @SWG\Property(type="integer", format="int32")
     */
    private $id;
    /**
     * @var string $description
     * @SWG\Property(type="string")
     */
    private $description;
    /**
     * @var DateTime $dtstart
     * @SWG\Property(type="string", format="date-time")
     */
    private $dtstart;
    /**
     * @var DateTime $dtend
     * @SWG\Property(type="string", format="date-time")
     */
    private $dtend;
    /** 
     * @var DateInterval $duration
     * @SWG\Property(type="string")
     */
    private $duration;
    /** 
     * @var string $freq 
     * @SWG\Property(type="string")
     */
    private $freq;
    /** 
     * @var DateTime $until 
     * @SWG\Property(type="string", format="date-time")
     */
    private $until;
    /** 
     * @var  int $count
     * @SWG\Property(type="integer") 
     */
    private $count;
    /**
     * @var  int $rinterval
     * @SWG\Property(type="integer")
     */
    private $rinterval;
    /**
     * @var int $course_id
     * @SWG\Property(type="integer")
     */
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
     * @param int $rinterval
     */
    public function __construct($description = null, DateTime $dtstart = null, DateTime $dtend = null, DateInterval $duration = null, $freq=null, DateTime $until = null, $count = null, $rinterval=null, $id=null)
    {
        $this->id = $id;
        $this->description = $description;
        $this->dtstart = $dtstart;
        $this->dtend = $dtend;
        $this->duration = $duration;
        $this->freq = $freq;
        $this->until = $until;
        $this->count = $count;
        $this->rinterval = $rinterval;
    }


    public static function createFromArray(array $row)
    {
        $appmnt = new self();
        if(array_key_exists('id',$row)) {
            $appmnt->setId($row['id']);
        }
        if(array_key_exists('description',$row)) {
            $appmnt->setDescription($row['description']);
        }
        if(array_key_exists('dtstart',$row)) {
            $appmnt->setDtstart(DateTimeHelper::SqlToDateTime($row['dtstart']));
        }
        if(array_key_exists('dtend',$row)) {
            $appmnt->setDtend(DateTimeHelper::SqlToDateTime($row['dtend']));
        }
        if(array_key_exists('duration',$row)) {
            $appmnt->setDuration($row['duration']);
        }
        if(array_key_exists('freq',$row)) {
            $appmnt->setFreq($row['freq']);
        }
        if(array_key_exists('until',$row)) {
            $appmnt->setUntil(DateTimeHelper::SqlToDateTime($row['until']));
        }
        if(array_key_exists('count',$row)) {
            $appmnt->setCount($row['count']);
        }
        if(array_key_exists('rinterval',$row)) {
            $appmnt->setRinterval($row['rinterval']);
        }
        if(array_key_exists('courseid',$row)) {
            $appmnt->setCourseid($row['courseid']);
        }
        return $appmnt;
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
        return [
            'id' => $this->id,
            'description' => $this->description,
            'dtstart' => DateTimeHelper::dateTimeToSqlString($this->dtstart),
            'dtend' => DateTimeHelper::dateTimeToSqlString($this->dtend),
            'duration' => $this->duration,
            'freq' => $this->freq,
            'until' => DateTimeHelper::dateTimeToSqlString($this->until),
            'count' => $this->count,
            'rinterval' => $this->rinterval,
            'courseid' => $this->courseid
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
    public function getRinterval()
    {
        return $this->rinterval;
    }

    /**
     * @param int $interval
     */
    public function setRinterval($interval)
    {
        $this->rinterval = $interval;
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
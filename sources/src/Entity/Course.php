<?php

namespace HsBremen\WebApi\Entity;


use DateTime;
use Swagger\Annotations as SWG;
use Swagger\Annotations\Swagger;

/**
 * Class Course
 * @package HsBremen\WebApi\Entity
 * @SWG\Definition(
 *     definition="course",
 *     type="object"
 * )
 */



class Course implements \JsonSerializable
{
    /**
     * @var int
     * @SWG\Property(type="integer", format="int32")
     */
    private $id;
    /**
     * @var string
     * @SWG\Property(
     *     type="string",
     *     description="Username of the Owner."
     * )
     */
    private $owner;
    /**
     * @var string
     * @SWG\Property(
     *     type="string",
     *     description="Name of the Course."
     * )
     */
    private $name;

    public function __construct($id=null, $owner=null, $name=null)
    {
        $this->id = $id;
        $this->owner = $owner;
        $this->name = $name;
    }

    public static function createFromArray(array $row)
    {
        $course = new self();
        if (array_key_exists('id', $row)) {
            $course->setId($row['id']);
        }
        if (array_key_exists('owner', $row)) {
            $course->setOwner($row['owner']);
        }
        $course->setName($row['name']);
        
        return $course;
    }

    /**
     * @param null $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param null $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * @param null $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return null
     */
    public function getOwner()
    {
        return $this->owner;
    }

    

    function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'owner' => $this->owner,
            'name' => $this->name
        ];
    }

}
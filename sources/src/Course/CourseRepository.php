<?php
/**
 * Created by PhpStorm.
 * User: julian
 * Date: 24.05.2016
 * Time: 22:50
 */

namespace HsBremen\WebApi\Course;

use Doctrine\DBAL\Connection;
use HsBremen\WebApi\Database\DatabaseException;
use HsBremen\WebApi\Entity\Course;
use HsBremen\WebApi\Entity\Appointment;

class CourseRepository
{
    /** @var  Connection */
    private $connection;


    /**
     * CourseRepository constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function CreateTables()
    {
        $sql = <<<EOS
CREATE TABLE IF NOT EXISTS `Person` (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
);
        
        

CREATE TABLE IF NOT EXISTS `Course` (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    max_nr_subscriber INT NOT NULL,
    id_teacher INT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (id_teacher) REFERENCES Teacher(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS `Appointment` (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    description VARCHAR(255) NOT NULL,
    id_course INT UNSIGNED NOT NULL,
    dstart Date NOT NULL,
    dend Date NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (id_course) REFERENCES Course(id) ON DELETE CASCADE
);
EOS;
        return $this->connection->exec($sql);
    }

    /**
     * @param string $name
     */
    public function SaveNewTeacher($name)
    {
        $this->connection->insert("Teacher",$name);
    }


    /**
     * @param string $name
     * @param int $id_teacher
     * @param int $max_nr_subscriber
     */
    public function SaveNewCourse($name, $id_teacher, $max_nr_subscriber)
    {
        $data = array(
            'name' => $name,
            'id_teacher' => $id_teacher,
            'max_nr_subscriber' => $max_nr_subscriber);
        $this->connection->insert("Course", $data);
    }




}
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

    public function createTables()
    {
        $sql = <<<EOS
CREATE TABLE IF NOT EXISTS `course` (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    max_nr_subscriber INT NOT NULL,
    id_teacher INT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (id_teacher) REFERENCES Teacher(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS `user_course` (
    userid INT(11) NOT NULL,
    courseid INT(11) NOT NULL,
    userfunction VARCHAR(15),
    PRIMARY KEY (userid, courseid),
    FOREIGN KEY (userid) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (courseid) REFERENCES course(id) ON DELETE CASCADE
);
EOS;
        return $this->connection->exec($sql);
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
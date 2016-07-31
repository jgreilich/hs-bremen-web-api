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
    owner VARCHAR(100) NOT NULL,
    name VARCHAR(255) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (owner) REFERENCES users(username) ON DELETE CASCADE
);
EOS;
        $this->connection->exec($sql);

        $sql = <<<EOS
CREATE TABLE IF NOT EXISTS `user_course` (
  user VARCHAR(100) NOT NULL,
  courseid INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY (user, courseid),
  CONSTRAINT fk_user FOREIGN KEY (user) REFERENCES users(username) ON DELETE CASCADE,
  CONSTRAINT fk_course FOREIGN KEY (courseid) REFERENCES course(id) ON DELETE CASCADE
);
EOS;
        $this->connection->exec($sql);
    }


    public function getAllCourses()
    {
        $sql = <<<EOS
SELECT id, name, owner  
FROM `course`
EOS;

        $courses = $this->connection->fetchAll($sql);

        $result = [];

        foreach ($courses as $row) {
            $result[] = Course::createFromArray($row);
        }

        return $result;
    }
    
    public function saveNewCourse(Course $course)
    {
        $data = $course->jsonSerialize();
        unset($data['id']);
        
        $this->connection->insert('course', $data);
        $course->setId($this->connection->lastInsertId());
    }

    public function saveCourse(Course $course)
    {
        $data = $course->jsonSerialize();
        $this->connection->update('course',$data,['id' => $data['id']]);
    }

    public function deleteCourse($id)
    {
        $this->connection->delete('course',['id' => $id]);
    }


    public function getCourse($id)
    {
        $sql = "SELECT *  FROM `course` WHERE id= :id";
        $course = $this->connection->fetchAll($sql,['id' => $id]);
        if(count($course) === 1) {
            return Course::createFromArray($course[0]);
        } else {
            throw new DatabaseException("No course with id \"$id\"");
        }
    }


    public function subscribeCourse($username, $courseid)
    {
        $this->connection->insert('user_course', ['user' => $username, 'courseid' => $courseid]);
    }

    public function unsubscribeCourse($username, $courseid)
    {
        $this->connection->delete('user_course', ['user' => $username, 'courseid' => $courseid]);
    }


    public function getSubscribers($courseid)
    {
        $sql = "SELECT user FROM `user_course` WHERE courseid= :id";
        $subs = $this->connection->fetchAll($sql,['id' => $courseid]);
//        $result = [];
//        foreach ($subs as $sub){
//            $result[] = $sub;
//        }
//        return $result;
        return $subs;
    }




}
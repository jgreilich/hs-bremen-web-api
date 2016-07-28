<?php


use Doctrine\DBAL\Connection;
use HsBremen\WebApi\Course\CourseRepository;

class CourseRepositoryTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * @test
     */
    public function shouldCreateTable()
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

        $conn = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $conn
            ->expects($this->once())
            ->method('exec')
            ->with($sql);

        $courseRepo = new CourseRepository($conn);

        $courseRepo->createTables();
    }

}

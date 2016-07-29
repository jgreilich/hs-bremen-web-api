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
        $sql1 = <<<EOS
CREATE TABLE IF NOT EXISTS `course` (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    owner VARCHAR(100) NOT NULL,
    name VARCHAR(255) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (owner) REFERENCES users(username) ON DELETE CASCADE
);
EOS;

        $sql2 = <<<EOS
CREATE TABLE IF NOT EXISTS `user_course` (
  user VARCHAR(100) NOT NULL,
  courseid INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY (user, courseid),
  CONSTRAINT fk_user FOREIGN KEY (user) REFERENCES users(username),
  CONSTRAINT fk_course FOREIGN KEY (courseid) REFERENCES course(id)
);
EOS;

        $conn = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $conn
            ->expects($this->exactly(2))
            ->method('exec')
            ->withConsecutive([$sql1],[$sql2]);

        $courseRepo = new CourseRepository($conn);

        $courseRepo->createTables();
    }

}

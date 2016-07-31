<?php


use Doctrine\DBAL\Connection;
use HsBremen\WebApi\Course\CourseRepository;
use HsBremen\WebApi\Database\DatabaseException;
use HsBremen\WebApi\Entity\Course;

class CourseRepositoryTest extends PHPUnit_Framework_TestCase
{
    /** @var  Connection */
    private $conn;

    /** @var  CourseRepository */
    private $courseRepo;
    
    public function setUp()
    {
        $this->conn = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->courseRepo = new CourseRepository($this->conn);
    }
    
    
    
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
  CONSTRAINT fk_user FOREIGN KEY (user) REFERENCES users(username) ON DELETE CASCADE,
  CONSTRAINT fk_course FOREIGN KEY (courseid) REFERENCES course(id) ON DELETE CASCADE
);
EOS;
        
        $this->conn
            ->expects($this->exactly(2))
            ->method('exec')
            ->withConsecutive([$sql1],[$sql2]);
        
        $this->courseRepo->createTables();
    }

    
    /**
     * @test
     */
    public function shouldReturnCourses()
    {
        $courses = [
            ['id' => 1, 'owner' => 'user1', 'name'=> 'course1'],
            ['id' => 2, 'owner' => 'user2', 'name'=> 'course2'] ,
            ['id' => 3, 'owner' => 'user3', 'name'=> 'course3']
        ];

        $this->conn
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn($courses);

        $result = $this->courseRepo->getAllCourses();
        
        foreach ($result as $crs){
            $this->assertInstanceOf(Course::class, $crs);
        }
        
    }
    
    
    /**
     * @test
     */
    public function shouldSaveNewCourse()
    {
        
        $course = new Course(1,'owner1','course1');
        
        $course_data = [
            'owner' => 'owner1',
            'name' => 'course1'
        ];
        
        $this->conn
            ->expects($this->once())
            ->method('insert')
            ->with('course',$course_data);
        
        $this->courseRepo->saveNewCourse($course);
        
    }
    
    /**
     * @test
     */
    public function shouldSaveCourse()
    {
        $course = new Course(1,'owner1','course1');

        $course_data = [
            'id' => 1,
            'owner' => 'owner1',
            'name' => 'course1'
        ];
        
        $identifier = [ 'id' => 1];

        $this->conn
            ->expects($this->once())
            ->method('update')
            ->with('course',$course_data,$identifier);

        $this->courseRepo->saveCourse($course);
    }
    
    /**
     * @test
     */
    public function shouldDeleteCourse()
    {
        $this->conn
            ->expects($this->once())
            ->method('delete')
            ->with('course',['id' => 1]);
        
        $this->courseRepo->deleteCourse(1);
    }
    
    /**
     * @test
     */
    public function shouldReturnCourse()
    {
        $course = new Course(1,'owner1','course1');

        $course_data = [
            'id' => 1,
            'owner' => 'owner1',
            'name' => 'course1'
        ];

        $identifier = [ 'id' => 1];

        $this->conn
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn([$course_data]);

        $this->assertEquals($course, $this->courseRepo->getCourse(1));
    }
    
    
    /**
     * @test
     */
    public function shouldReturnNoCourse()
    {
        $this->setExpectedException(DatabaseException::class);

        $this->conn
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn([]);
        
        $this->courseRepo->getCourse(1);

    }
    
    /**
     * @test
     */
    public function shouldSubscribeCourse()
    {
        $this->conn
            ->expects($this->once())
            ->method('insert')
            ->with('user_course', ['user' => 'user1', 'courseid' => 1]);
        
        $this->courseRepo->subscribeCourse('user1',1);
        
    }
    
    /**
     * @test
     */
    public function shouldUnsubscribeCourse()
    {
        $this->conn
            ->expects($this->once())
            ->method('delete')
            ->with('user_course', ['user' => 'user1', 'courseid' => 1]);

        $this->courseRepo->unsubscribeCourse('user1',1);
    }
    
    /**
     * @test
     */
    public function shouldReturnSubscribers()
    {
        $subs = [['user' => 'sub1'], ['user' => 'user2']];
        $this->conn
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn($subs);
        
        $subs_list = $this->courseRepo->getSubscribers(3);
        
        $this->assertEquals($subs_list,$subs);
    }
    
}

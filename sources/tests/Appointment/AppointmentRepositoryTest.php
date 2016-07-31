<?php

use Doctrine\DBAL\Connection;
use HsBremen\WebApi\Appointment\AppointmentRepository;
use HsBremen\WebApi\Database\DatabaseException;
use HsBremen\WebApi\Entity\Appointment;

class AppointmentRepositoryTest extends PHPUnit_Framework_TestCase
{

    /** @var  PHPUnit_Framework_MockObject_MockObject */
    private $conn;
    
    /** @var  AppointmentRepository */
    private $appmntRepo;
    
    public function setUp()
    {
        $this->conn = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->appmntRepo = new AppointmentRepository($this->conn);
    }
    
    
    
    /**
     * @test
     */
    public function shouldCreateTable()
    {
        $sql = <<<EOS
CREATE TABLE IF NOT EXISTS `appointment` (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    description VARCHAR(255),
    dtstart DATETIME NOT NULL,
    dtend DATETIME,
    duration VARCHAR(50),
    freq VARCHAR(15),
    until DATETIME,
    count INT(11),
    rinterval INT(11),
    courseid INT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (courseid) REFERENCES course(id) ON DELETE CASCADE
);
EOS;

        $this->conn
            ->expects($this->once())
            ->method('exec')
            ->with($sql);

        $this->appmntRepo->createTable();
    }
    
    /**
     * @test
     */
    public function shouldInsertAppointment()
    {
        $appmnt = new Appointment("Termin 1",
            new DateTime("01.01.2016 15:00"),
            new DateTime("01.01.2016 16:30"),
            null,
            Appointment::$freqencies[2],
            null,
            2,
            1);
        
        $this->conn
            ->expects($this->once())
            ->method('insert');
        
        $this->appmntRepo->saveNewAppointment( $appmnt);
    }

    /**
     * @test
     */
    public function shouldUpdateAppointment()
    {
        $appmnt = new Appointment("Termin 1",
            new DateTime("01.01.2016 15:00"),
            new DateTime("01.01.2016 16:30"),
            null,
            Appointment::$freqencies[2],
            null,
            2,
            1,
            1);



        $this->conn
            ->expects($this->once())
            ->method('update');
        

        $this->appmntRepo->saveAppointment($appmnt);
    }
    
    /**
     * @test
     */
    public function shouldReturnAppointment()
    {
        $appmnt = new Appointment("Termin 1",
            new DateTime("01.01.2016 15:00"),
            new DateTime("01.01.2016 16:30"),
            null,
            Appointment::$freqencies[2],
            null,
            2,
            1,
            1);
        
        
        $this->conn
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn([$appmnt->jsonSerialize()]);
        
        $this->assertEquals($appmnt, $this->appmntRepo->getAppointment(3));
    }
    
    /**
     * @test
     */
    public function shouldReturnNoAppointment()
    {
        $this->setExpectedException(DatabaseException::class);
        
        $this->conn
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn([]);
        
        $this->appmntRepo->getAppointment(3);
    }
    
    
    /**
     * @test
     */
    public function shouldReturnAppointsments()
    {
        $appmnt1 = new Appointment("Termin 1",
            new DateTime("01.01.2016 15:00"),
            new DateTime("01.01.2016 16:30"),
            null,
            Appointment::$freqencies[2],
            null,
            2,
            1,
            1);

        $appmnt2 = new Appointment("Termin 2",
            new DateTime("01.03.2016 15:00"),
            new DateTime("01.03.2016 16:30"),
            null,
            Appointment::$freqencies[2],
            null,
            2,
            1,
            1);

        $this->conn
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn([$appmnt1->jsonSerialize(),$appmnt2->jsonSerialize()]);

        $this->assertEquals([$appmnt1, $appmnt2], $this->appmntRepo->getAllAppointsments(3));
    }
    
    
    /**
     * @test
     */
    public function shouldDeleteAppointment()
    {
        $this->conn
            ->expects($this->once())
            ->method('delete')
            ->with('appointment',['id' => 3]);
        
        $this->appmntRepo->deleteAppointment(3);
    }

    


    
}

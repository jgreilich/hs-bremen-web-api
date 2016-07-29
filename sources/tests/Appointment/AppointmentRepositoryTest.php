<?php

use Doctrine\DBAL\Connection;
use HsBremen\WebApi\Appointment\AppointmentRepository;
use HsBremen\WebApi\Entity\Appointment;

class AppointmentRepositoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function shouldCreateTable()
    {
        $sql = <<<EOS
CREATE TABLE IF NOT EXISTS `appointment` (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    description VARCHAR(255),
    dtstart Date NOT NULL,
    dtend Date,
    duration VARCHAR(50),
    freq VARCHAR(15),
    until DATE,
    count INT(11)
    interval INT(11)
    courseid INT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
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
        
        $appmntRepo = new AppointmentRepository($conn);

        $appmntRepo->createTable();
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
        
        $conn = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $conn
            ->expects($this->once())
            ->method('insert');
        
        $apptmntRepo = new AppointmentRepository($conn);
        
        $apptmntRepo->saveNewAppointment( $appmnt);
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

        $conn = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $conn
            ->expects($this->once())
            ->method('update');

        $apptmntRepo = new AppointmentRepository($conn);

        $apptmntRepo->saveAppointment($appmnt);
    }

    
}

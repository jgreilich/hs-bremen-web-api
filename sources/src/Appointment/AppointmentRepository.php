<?php

namespace HsBremen\WebApi\Appointment;

use Doctrine\DBAL\Connection;
use HsBremen\WebApi\Entity\Appointment;
use HsBremen\WebApi\Entity\DateTimeHelper;

class AppointmentRepository
{
    /** @var Connection $connection */
    private $connection;

    /**
     * AppointmentRepository constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;    
    }
    
    
    public function createTable()
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
        return $this->connection->exec($sql);
    }


    public function saveAppointment($courseid, Appointment $appmnt)
    {
        if($appmnt->getId() == null){
            $this->connection->insert('appointment',array(
                "description" => $appmnt->getDescription(),
                "dtstart" => $appmnt->getDtstart(),
                "dtend" => $appmnt->getDtend(),
                "duration" => DateTimeHelper::dateIntervalToString($appmnt->getDuration()),
                "freq" => $appmnt->getFreq(),
                "until" => $appmnt->getUntil(),
                "count" => $appmnt->getCount(),
                "interval" => $appmnt->getInterval(),
                "courseid" => $courseid
            )); 
        } else {
            $this->connection->update('appointment',array(
                "description" => $appmnt->getDescription(),
                "dtstart" => $appmnt->getDtstart(),
                "dtend" => $appmnt->getDtend(),
                "duration" => DateTimeHelper::dateIntervalToString($appmnt->getDuration()),
                "freq" => $appmnt->getFreq(),
                "until" => $appmnt->getUntil(),
                "count" => $appmnt->getCount(),
                "interval" => $appmnt->getInterval(),
                "courseid" => $courseid
            ),["id" => $appmnt->getId()]);
        }
    }
    
    
    

}
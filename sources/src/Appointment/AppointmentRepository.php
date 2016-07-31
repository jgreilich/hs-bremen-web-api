<?php

namespace HsBremen\WebApi\Appointment;

use Doctrine\DBAL\Connection;
use HsBremen\WebApi\Database\DatabaseException;
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
        return $this->connection->exec($sql);
    }


    public function saveNewAppointment(Appointment $appmnt)
    {
        $data = $appmnt->jsonSerialize();
        unset($data['id']);
        $this->connection->insert('appointment',$data);
        $appmnt->setId($this->connection->lastInsertId());
    }

    public function saveAppointment(Appointment $appmnt)
    {
        $data = $appmnt->jsonSerialize();
        $this->connection->update('appointment',$data,["id" => $appmnt->getId()]);
    }

    public function getAppointment($id)
    {
        $sql = "SELECT *  FROM `appointment` WHERE id= :id";
        $appmnt = $this->connection->fetchAll($sql,['id' => $id]);
        if(count($appmnt) === 1) {
            return Appointment::createFromArray($appmnt[0]);
        } else {
            throw new DatabaseException("No appointment with id \"$id\"");
        }
    }

    public function getAllAppointsments($courseId)
    {
        $sql = "SELECT * FROM `appointment` WHERE courseid= :id";
        $appmnts = $this->connection->fetchAll($sql,['id' => $courseId]);

        $result = [];

        foreach ($appmnts as $row) {
            $result[] = Appointment::createFromArray($row);
        }

        return $result;
    }
    
    public function deleteAppointment($id){
        $this->connection->delete('appointment',['id' => $id]);
    }
    

}
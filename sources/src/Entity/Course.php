<?php

namespace HsBremen\WebApi\Entity;


class Course implements \JsonSerializable
{
    private $id;
    private $appointments;

    public function __construct($id)
    {
        $this->id = $id;
        if(!isset($appmts)) {
            $this->appointments = array(Appointment);
        } else {
            $t1 = new DateTime();
            $t1->setTime(8,0);
            

            $this->appointments = [
              new Appointment("Termin1")
                
                
            ];
        }
            
    }
    
    
    public function setId($id)
    {
        $this->id = $id;
    }
    
    function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'appointments' => $this->appointments,
        ];
    }

}
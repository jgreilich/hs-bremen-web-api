<?php

namespace HsBremen\WebApi\Entity;


use DateTime;

class Course implements \JsonSerializable
{
    private $id;
    private $appointments;

    public function __construct($id, $appmts=null)
    {
        $this->id = $id;
        if($appmts!=null) {
            $this->appointments = array();
        } else {
            $t1 = new DateTime();
            $t1->setTime(8,0);
            $t2 = new DateTime();
            $t2->setTime(10,0);

            $t3 = new DateTime();
            $t4 = (new DateTime())->setTime(23,59);


            $this->appointments = [
              new Appointment("Termin1",$t1,$t2),
              new Appointment("Termin2",$t3,$t4,"einmalig"),
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
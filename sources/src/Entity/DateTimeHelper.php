<?php
/**
 * Created by PhpStorm.
 * User: julian
 * Date: 28.07.2016
 * Time: 02:20
 */

namespace HsBremen\WebApi\Entity;


class DateTimeHelper
{
    public static function dateIntervalToString(\DateInterval $interval=null)
    {
        if($interval == null){
            return null;
        }

        // Reading all non-zero date parts.
        $date = array_filter(array(
            'Y' => $interval->y,
            'M' => $interval->m,
            'D' => $interval->d
        ));

        // Reading all non-zero time parts.
        $time = array_filter(array(
            'H' => $interval->h,
            'M' => $interval->i,
            'S' => $interval->s
        ));

        $specString = 'P';

        // Adding each part to the spec-string.
        foreach ($date as $key => $value) {
            $specString .= $value . $key;
        }
        if (count($time) > 0) {
            $specString .= 'T';
            foreach ($time as $key => $value) {
                $specString .= $value . $key;
            }
        }

        return $specString;
    }
}
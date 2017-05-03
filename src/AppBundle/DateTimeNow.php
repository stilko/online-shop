<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12.4.2017 г.
 * Time: 17:10 ч.
 */

namespace AppBundle;


use Doctrine\DBAL\Types\DateTimeType;

class DateTimeNow extends DateTimeType
{
    public function format()
    {
        return 'NOW()';
    }

}
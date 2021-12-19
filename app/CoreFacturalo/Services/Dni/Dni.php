<?php

namespace App\CoreFacturalo\Services\Dni;

class Dni
{
    public static function search($number)
    {
        $res = Jne::search($number);
        return $res;
    }
}

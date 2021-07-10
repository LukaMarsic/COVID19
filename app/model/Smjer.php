<?php

class Smjer
{

    public static function ucitajSve()
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            select * from smjer
        
        ');
        $izraz->execute();
        return $izraz->fetchAll();
    }

}
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
    public static function dodajNovi($smjer)
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            insert into smjer (naziv,trajanje,cijena,verificiran)
            values (:naziv,:trajanje,:cijena,:verificiran)
        
        ');
        $izraz->execute((array)$smjer);
    }

}
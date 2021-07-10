<?php

class Smjer
{

    public static function ucitaj($sifra)
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            select * from smjer where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifra]);
        return $izraz->fetch();
    }

    public static function ucitajSve()
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            
        select a.*, count(b.sifra) as ukupnogrupa 
        from smjer a 
        left join grupa b on a.sifra=b.smjer
        group by a.sifra,a.naziv,a.trajanje,
        a.cijena,a.verificiran ;
        
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

    public static function promjeniPostojeci($smjer)
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
           update smjer set 
           naziv=:naziv,trajanje=:trajanje,
           cijena=:cijena,verificiran=:verificiran 
           where sifra=:sifra
        
        ');
        $izraz->execute((array)$smjer);
    }

    public static function obrisiPostojeci($sifra)
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            delete from smjer where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifra]);
    }


}
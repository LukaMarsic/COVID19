<?php

class narudzbe
{

    public static function ucitaj($sifra)
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            select * from narudzbe where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifra]);
        return $izraz->fetch();
    }

    public static function ucitajSve()
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
        select a.*, count(b.sifra) as ukupnoordinacija 
        from narudzbe a 
        left join ordinacija b on a.sifra=b.narudzbe
        group by a.sifra,a.naziv,a.trajanje,
        a.cijena,a.placanje ;
        
        ');
        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function dodajNovi($narudzbe)
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            insert into narudzbe (naziv,trajanje,cijena,placanje)
            values (:naziv,:trajanje,:cijena,:placanje)
        
        ');
        $izraz->execute((array)$narudzbe);
    }

    public static function promjeniPostojeci($narudzbe)
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
           update narudzbe set 
           naziv=:naziv,trajanje=:trajanje,
           cijena=:cijena,placanje=:placanje
           where sifra=:sifra
        
        ');
        $izraz->execute((array)$narudzbe);
    }

    public static function obrisiPostojeci($sifra)
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            delete from narudzbe where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifra]);
    }

}
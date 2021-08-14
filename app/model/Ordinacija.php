<?php

class Ordinacija
{

    public static function brojRadnikaPoOrdinacijama()
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
        select a.naziv as name, count(b.radnik) as y
        from ordinacija a inner join osoblje b
        on a.sifra =b.ordinacija
        group by a.naziv;
        
        ');
        $izraz->execute();
        return $izraz->fetchAll();

    }

    public static function ucitaj($sifra)
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            select * from ordinacija where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifra]);
        $ordinacija= $izraz->fetch();

        $izraz=$veza->prepare('
        
        select b.sifra, c.ime, c.prezime 
        from osoblje a inner join radnik b
        on a.radnik =b.sifra 
        inner join osoba c on
        b.osoba =c.sifra 
        where a.ordinacija =:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifra]);
        $ordinacija->radnici = $izraz->fetchAll();

        return $ordinacija;
    }

    public static function ucitajSve()
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            select b.naziv as narudzbe, a.naziv,
            concat(d.ime, \' \', d.prezime) as doktor,
            a.datumpocetka, a.sifra, count(e.radnik) as radnika
            from ordinacija a inner join narudzbe b
            on a.narudzbe=b.sifra 
            left join doktor c 
            on a.doktor=c.sifra
            left join osoba d
            on c.osoba=d.sifra
            left join osoblje e
            on a.sifra=e.ordinacija
            group by b.naziv, a.naziv,
            concat(d.ime, \' \', d.prezime),
            a.datumpocetka, a.sifra 
        
        ');
        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function dodajNovi($ordinacija)
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            insert into ordinacija (naziv,narudzbe,doktor,datumpocetka)
            values (:naziv,:narudzbe,:doktor,:datumpocetka)
        
        ');
        $izraz->execute((array)$ordinacija);
        return $veza->lastInsertId();
    }

    public static function promjeniPostojeci($ordinacija)
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
           update ordinacija set 
           naziv=:naziv,narudzbe=:narudzbe,
           doktor=:doktor,datumpocetka=:datumpocetka 
           where sifra=:sifra
        
        ');
        $izraz->execute((array)$ordinacija);
    }

    public static function obrisiPostojeci($sifra)
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            delete from ordinacija where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifra]);
    }

    public static function dodajRadnika()
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            insert into osoblje (ordinacija,radnik) values 
            (:ordinacija,:radnik);
        
        ');
        $izraz->execute($_POST);
    }

    public static function obrisiRadnika()
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            delete from osoblje where ordinacija=:ordinacija and radnik=:radnik;
        
        ');
        $izraz->execute($_POST);
    }


}
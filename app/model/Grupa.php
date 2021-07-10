<?php

class Grupa
{

    public static function ucitaj($sifra)
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            select * from grupa where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifra]);
       
        $grupa= $izraz->fetch();

        $izraz=$veza->prepare('
        
        select b.sifra, c.ime, c.prezime 
        from clan a inner join polaznik b
        on a.polaznik =b.sifra 
        inner join osoba c on
        b.osoba =c.sifra 
        where a.grupa =:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifra]);
        $grupa->polaznici = $izraz->fetchAll();

        return $grupa;
    }

    public static function ucitajSve()
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            select b.naziv as smjer, a.naziv,
            concat(d.ime, \' \', d.prezime) as predavac,
            a.datumpocetka, a.sifra, count(e.polaznik) as polaznika
            from grupa a inner join smjer b
            on a.smjer=b.sifra 
            left join predavac c 
            on a.predavac=c.sifra
            left join osoba d
            on c.osoba=d.sifra
            left join clan e
            on a.sifra=e.grupa
            group by b.naziv, a.naziv,
            concat(d.ime, \' \', d.prezime),
            a.datumpocetka, a.sifra 
        
        ');
        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function dodajNovi($grupa)
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            insert into grupa (naziv,smjer,predavac,datumpocetka)
            values (:naziv,:smjer,:predavac,:datumpocetka)
        
        ');
        $izraz->execute((array)$grupa);
        return $veza->lastInsertId();
    }

    public static function promjeniPostojeci($grupa)
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
           update grupa set 
           naziv=:naziv,smjer=:smjer,
           predavac=:predavac,datumpocetka=:datumpocetka 
           where sifra=:sifra
        
        ');
        $izraz->execute((array)$grupa);
    }

    public static function obrisiPostojeci($sifra)
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            delete from grupa where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifra]);
    }

    public static function dodajPolaznika()
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            insert into clan (grupa,polaznik) values 
            (:grupa,:polaznik);
        
        ');
        $izraz->execute($_POST);
    }

    public static function obrisiPolaznika()
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            delete from clan where grupa=:grupa and polaznik=:polaznik;
        
        ');
        $izraz->execute($_POST);
    }

}
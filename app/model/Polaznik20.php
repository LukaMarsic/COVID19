<?php

class Polaznik20
{

    public static function ucitaj($sifra)
    {

        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
        select a.sifra, a.iban,b.ime,b.prezime,
        b.oib,b.email from polaznik20 a 
        inner join osoba b on a.osoba =b.sifra
        where a.sifra=:sifra;
        
        ');
        $izraz->execute(['sifra'=>$sifra]);
        return $izraz->fetch();


    }



    public static function ucitajSve()
    {

        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
        select a.sifra, a.iban,b.ime,b.prezime,
        b.oib,b.email, count(c.sifra) as ukupnogrupa from predavac a 
        inner join osoba b on a.osoba =b.sifra 
        left join grupa c on a.sifra =c.predavac 
        group by a.sifra, a.iban,b.ime,b.prezime,
        b.oib,b.email;
        
        ');
        $izraz->execute();
        return $izraz->fetchAll();


    }


    public static function dodajNovi($entitet)
    {
        $veza = DB::getInstanca();
        $veza->beginTransaction();
        $izraz=$veza->prepare('
        
            insert into osoba 
            (ime, prezime, email, oib) values
            (:ime, :prezime, :email, :oib)
            
        ');
        $izraz->execute([
            'ime'=>$entitet->ime,
            'prezime'=>$entitet->prezime,
            'email'=>$entitet->email,
            'oib'=>$entitet->oib
        ]);
        $zadnjaSifra=$veza->lastInsertId();
        $izraz=$veza->prepare('
        
            insert into polaznik20 
            (osoba, iban) values
            (:osoba, :iban)
        
        ');
        $izraz->execute([
            'osoba'=>$zadnjaSifra,
            'iban'=>$entitet->iban
        ]);

        $veza->commit();
    }


    public static function promjeniPostojeci($entitet)
    {
        $veza = DB::getInstanca();
        $veza->beginTransaction();
        $izraz=$veza->prepare('
        
          select osoba from polaznik20 where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$entitet->sifra]);
        $sifraOsoba=$izraz->fetchColumn();


        $izraz=$veza->prepare('
        
            update osoba 
            set ime=:ime, prezime=:prezime, email=:email, oib=:oib
            where sifra=:sifra
            
        ');
        $izraz->execute([
            'ime'=>$entitet->ime,
            'prezime'=>$entitet->prezime,
            'email'=>$entitet->email,
            'oib'=>$entitet->oib,
            'sifra'=>$sifraOsoba
        ]);


        $izraz=$veza->prepare('
        
            update polaznik20
            set iban=:iban
            where sifra=:sifra
    
        ');
        $izraz->execute([
            'sifra'=>$entitet->sifra,
            'iban'=>$entitet->iban
        ]);



        $veza->commit();

    }

    public static function obrisiPostojeci($sifra)
    {
        $veza = DB::getInstanca();
        $veza->beginTransaction();
        $izraz=$veza->prepare('
        
          select osoba from polaznik20 where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifra]);
        $sifraOsoba=$izraz->fetchColumn();

        $izraz=$veza->prepare('
        
            delete from polaznik20 where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifra]);


        $izraz=$veza->prepare('
        
            delete from osoba where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifraOsoba]);

        $veza->commit();
    }



}
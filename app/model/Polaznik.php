<?php

class Polaznik
{

    public static function ucitaj($sifra)
    {

        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
        select a.sifra, a.brojugovora,b.ime,b.prezime,
        b.oib,b.email from polaznik a 
        inner join osoba b on a.osoba =b.sifra
        where a.sifra=:sifra;
        
        ');
        $izraz->execute(['sifra'=>$sifra]);
        return $izraz->fetch();


    }



    public static function ucitajSve($stranica,$uvjet)
    {

        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
        select a.sifra, a.brojugovora,b.ime,b.prezime,
        b.oib,b.email, count(c.grupa) as ukupnogrupa from polaznik a 
        inner join osoba b on a.osoba =b.sifra 
        left join clan c on a.sifra =c.polaznik 
        group by a.sifra, a.brojugovora,b.ime,b.prezime,
        b.oib,b.email limit 12;
        
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
        
            insert into polaznik 
            (osoba, brojugovora) values
            (:osoba, :brojugovora)
        
        ');
        $izraz->execute([
            'osoba'=>$zadnjaSifra,
            'brojugovora'=>$entitet->brojugovora
        ]);

        $veza->commit();
    }


    public static function promjeniPostojeci($entitet)
    {
        $veza = DB::getInstanca();
        $veza->beginTransaction();
        $izraz=$veza->prepare('
        
          select osoba from polaznik where sifra=:sifra
        
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
        
            update polaznik 
            set brojugovora=:brojugovora
            where sifra=:sifra
    
        ');
        $izraz->execute([
            'sifra'=>$entitet->sifra,
            'brojugovora'=>$entitet->brojugovora
        ]);



        $veza->commit();

    }


    public static function obrisiPostojeci($sifra)
    {
        $veza = DB::getInstanca();
        $veza->beginTransaction();
        $izraz=$veza->prepare('
        
          select osoba from polaznik where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifra]);
        $sifraOsoba=$izraz->fetchColumn();

        $izraz=$veza->prepare('
        
            delete from polaznik where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifra]);


        $izraz=$veza->prepare('
        
            delete from osoba where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifraOsoba]);

        $veza->commit();
    }



}
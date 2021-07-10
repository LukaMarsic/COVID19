<?php

class Polaznik20
{

    public static function ucitajSve()
    {

        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
        select a.sifra, a.iban,b.ime,b.prezime,
        b.oib,b.email, count(c.sifra) as ukupnogrupa from polaznik20 a 
        inner join osoba b on a.osoba =b.sifra 
        left join grupa c on a.sifra =c.polaznik20
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
        
            insert into predavac 
            (osoba, iban) values
            (:osoba, :iban)
        
        ');
        $izraz->execute([
            'osoba'=>$zadnjaSifra,
            'iban'=>$entitet->iban
        ]);

        $veza->commit();
    }



}
<?php

class Radnik
{

    public static function traziradnike()
    {

        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
        select a.sifra, a.brojugovora,b.ime,b.prezime,
        b.oib,b.email from radnik a 
        inner join osoba b on a.osoba =b.sifra 
        where concat(b.ime, \' \', b.prezime, \' \',
        ifnull(b.oib,\'\')) like :uvjet and a.sifra not in
        (select radnik from osoblje where ordinacija=:ordinacija)
        limit 6
        ');
       
        $izraz->execute([
            'uvjet'=>'%' . $_GET['uvjet'] . '%',
            'ordinacija'=>$_GET['ordinacija']
        ]);
        return $izraz->fetchAll();


    }

    public static function ucitaj($sifra)
    {

        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
        select a.sifra, a.brojugovora,b.ime,b.prezime,
        b.oib,b.email from radnik a 
        inner join osoba b on a.osoba =b.sifra
        where a.sifra=:sifra;
        
        ');
        $izraz->execute(['sifra'=>$sifra]);
        return $izraz->fetch();


    }



    public static function ucitajSve($stranica,$uvjet)
    {

        $rps=App::config('rezultataPoStranici'); 
        $od = $stranica * $rps - $rps;


        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
        select a.sifra, a.brojugovora,b.ime,b.prezime,
        b.oib,b.email, count(c.ordinacija) as ukupnoordinacija from radnik a 
        inner join osoba b on a.osoba =b.sifra 
        left join osoblje c on a.sifra =c.radnik
        where concat(b.ime, \' \', b.prezime, \' \',
        ifnull(b.oib,\'\')) like :uvjet
        group by a.sifra, a.brojugovora,b.ime,b.prezime,
        b.oib,b.email limit :od,:rps;
        
        ');
       
        $izraz->bindParam('uvjet',$uvjet);
        $izraz->bindValue('od',$od, PDO::PARAM_INT);
        $izraz->bindValue('rps',$rps, PDO::PARAM_INT);
        $izraz->execute();
        return $izraz->fetchAll();


    }

    public static function ukupnoRadnika($uvjet)
    {

        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
        select count(a.sifra) from radnik a 
        inner join osoba b on a.osoba =b.sifra 
        where concat(b.ime, \' \', b.prezime, \' \',
        ifnull(b.oib,\'\')) like :uvjet
        ');
       
        $izraz->bindParam('uvjet',$uvjet);
        $izraz->execute();
        return $izraz->fetchColumn();


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
        
            insert into radnik
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
        
          select osoba from radnik where sifra=:sifra
        
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
        
          select osoba from radnik where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifra]);
        $sifraOsoba=$izraz->fetchColumn();

        $izraz=$veza->prepare('
        
            delete from radnik where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifra]);


        $izraz=$veza->prepare('
        
            delete from osoba where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifraOsoba]);

        $veza->commit();
    }



}
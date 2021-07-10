<?php

class PolaznikController extends AutorizacijaController
{
    private $viewDir = 'privatno'
                        . DIRECTORY_SEPARATOR
                        . 'polaznik'
                        . DIRECTORY_SEPARATOR;
    
    private $entitet=null;
    private $poruka='';

    public function index()
    {

       
        if(isset($_GET['uvjet'])){
            $uvjet='%' . $_GET['uvjet'] . '%';
        }else{
            $uvjet='%';
            $_GET['uvjet']='';
        }

        if(isset($_GET['stranica'])){
            $stranica = $_GET['stranica'];
            if($stranica==0){
                $stranica=1;
            }
        }else{
            $stranica=1;
        }

        $brojPolaznika=Polaznik::ukupnoPolaznika($uvjet);
        $ukupnoStranica=ceil($brojPolaznika/App::config('rezultataPoStranici'));


        if($stranica>$ukupnoStranica){
            $stranica=$ukupnoStranica;
        }

        $polaznici = Polaznik::ucitajSve($stranica,$uvjet);

        foreach($polaznici as $red){
            if(file_exists(BP . 'public' . DIRECTORY_SEPARATOR .
            'img' . DIRECTORY_SEPARATOR . 'polaznik' . 
            DIRECTORY_SEPARATOR . $red->sifra . '.png')){
                $red->slika = App::config('url') . 
                'public/img/polaznik/' . $red->sifra . '.png';
            }else{
                $red->slika = App::config('url') . 
                'public/img/polaznik/nepoznato.png';
            }
        }


        $this->view->render($this->viewDir . 'index',[
            'entiteti'=>$polaznici,
            'uvjet'=>$_GET['uvjet'],
            'stranica'=>$stranica,
            'ukupnoStranica'=>$ukupnoStranica
        ]);
    }

    public function novo()
    {
        if($_SERVER['REQUEST_METHOD']==='GET'){
            $this->noviEntitet();
            return;
        }
        $this->entitet = (object) $_POST;
        try {
            $this->kontrola();
            Polaznik::dodajNovi($this->entitet);
            $this->index();
        } catch (Exception $e) {
            $this->poruka=$e->getMessage();
            $this->novoView();
        }       
    }

    public function promjena()
    {
        if($_SERVER['REQUEST_METHOD']==='GET'){
            if(!isset($_GET['sifra'])){
               $ic = new IndexController();
               $ic->logout();
               return;
            }
            $this->entitet = Polaznik::ucitaj($_GET['sifra']);
            $this->poruka='Promjenite željene podatke';
            $this->promjenaView();
            return;
        }
        $this->entitet = (object) $_POST;
        try {
            $this->kontrolaImePrezime();
            Polaznik::promjeniPostojeci($this->entitet);
            $this->index();
        } catch (Exception $e) {
            $this->poruka=$e->getMessage();
            $this->promjenaView();
        }       
    }


    public function brisanje()
    {
        if(!isset($_GET['sifra'])){
            $ic = new IndexController();
            $ic->logout();
            return;
        }
        Polaznik::obrisiPostojeci($_GET['sifra']);
        header('location: ' . App::config('url') . 'polaznik/index');
       
    }







    

    private function noviEntitet()
    {
        $this->entitet = new stdClass();
        $this->entitet->ime='';
        $this->entitet->prezime='';
        $this->entitet->email='';
        $this->entitet->oib='';
        $this->entitet->brojugovora='';
        $this->poruka='Unesite tražene podatke';
        $this->novoView();
    }

    private function promjenaView()
    {
        $this->view->render($this->viewDir . 'promjena',[
            'entitet'=>$this->entitet,
            'poruka'=>$this->poruka
        ]);
    }


    private function novoView()
    {
        $this->view->render($this->viewDir . 'novo',[
            'entitet'=>$this->entitet,
            'poruka'=>$this->poruka
        ]);
    }

    private function kontrola()
    {
        $this->kontrolaImePrezime();
        $this->kontrolaOib();
    }

    private function kontrolaImePrezime()
    {
        $this->kontrolaIme();
        $this->kontrolaPrezime();
    }
  
    private function kontrolaIme()
    {
        if(strlen(trim($this->entitet->ime))==0){
            throw new Exception('Ime obavezno');
        }

        if(strlen(trim($this->entitet->ime))>50){
            throw new Exception('Ime predugačko');
        }
    }

    private function kontrolaPrezime()
    {
        if(strlen(trim($this->entitet->prezime))==0){
            throw new Exception('Prezime obavezno');
        }
    }

    private function kontrolaOib()
    {
        if(!Kontrola::CheckOIB($this->entitet->oib)){
            throw new Exception('OIB nije ispravan');
        }
    }

}
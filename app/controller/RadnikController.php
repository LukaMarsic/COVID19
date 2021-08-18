<?php

class RadnikController extends AutorizacijaController
{
    private $viewDir = 'privatno'
                        . DIRECTORY_SEPARATOR
                        . 'radnik'
                        . DIRECTORY_SEPARATOR;
    
    private $entitet=null;
    private $poruka='';

    public function traziradnike()
    {
        header('Content-type: application/json');
        echo json_encode(Radnik::traziradnike());
    }

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

        $brojRadnika=Radnik::ukupnoRadnika($uvjet);
        $ukupnoStranica=ceil($brojRadnika/App::config('rezultataPoStranici'));


        if($stranica>$ukupnoStranica){
            $stranica=$ukupnoStranica;
        }

        $radnici = Radnik::ucitajSve($stranica,$uvjet);
        
        foreach($radnici as $red){
            if(file_exists(BP . 'public' . DIRECTORY_SEPARATOR .
            'img' . DIRECTORY_SEPARATOR . 'radnik' . 
            DIRECTORY_SEPARATOR . $red->sifra . '.png')){
                $red->slika = App::config('url') . 
                'public/img/radnik/' . $red->sifra . '.png';
            }else{
                $red->slika = App::config('url') . 
                'public/img/radnik/nepoznat.png';
            }
        }


        $this->view->render($this->viewDir . 'index',[
            'entiteti'=>$radnici,
            
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
            Radnik::dodajNovi($this->entitet);
            $this->index();
        } catch (Exception $e) {
            $this->poruka=$e->getMessage();
            $this->novoview();
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
            $this->entitet = Radnik::ucitaj($_GET['sifra']);
            $this->poruka='Promjenite željene podatke';
            $this->promjenaview();
            return;
        }
        $this->entitet = (object) $_POST;
        try {
            $this->kontrolaImePrezime();
            Radnik::promjeniPostojeci($this->entitet);
            $this->index();
        } catch (Exception $e) {
            $this->poruka=$e->getMessage();
            $this->promjenaview();
        }       
    }


    public function brisanje()
    {
        if(!isset($_GET['sifra'])){
            $ic = new IndexController();
            $ic->logout();
            return;
        }
        Radnik::obrisiPostojeci($_GET['sifra']);
        header('location: ' . App::config('url') . 'radnik/index');
       
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
        $this->novoview();
    }

    private function promjenaview()
    {
        $this->view->render($this->viewDir . 'promjena',[
            'entitet'=>$this->entitet,
            'poruka'=>$this->poruka
        ]);
    }


    private function novoview()
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
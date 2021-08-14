<?php


class OrdinacijaController extends AutorizacijaController
{
    private $viewDir = 'privatno'
                        . DIRECTORY_SEPARATOR
                        . 'ordinacija'
                        . DIRECTORY_SEPARATOR;
    
    private $entitet=null;
    private $poruka='';
    private $Narudzbe=null;
    private $doktor=null;

    public function __construct()
    {
        parent::__construct();
        $this->Narudzbe=Narudzbe::ucitajSve();
        
        $s=new stdClass();
        $s->sifra=-1;
        $s->naziv='Odaberite Narudzbe';
        array_unshift($this->Narudzbe,$s);


        $this->Doktor=Doktor::ucitajSve();
        $s=new stdClass();
        $s->sifra=-1;
        $s->ime='Odaberite';
        $s->prezime='doktora';
        array_unshift($this->Doktor,$s);
    }

    public function index()
    {

        $ordinacija=Ordinacija::ucitajSve();

        foreach($ordinacija as $o){
            //https://www.php.net/manual/en/datetime.format.php
            $o->datumpocetka=date('d.m.Y. H:i', strtotime($o->datumpocetka));
            if($o->doktor==null){
                $o->doktor='[nije postavljeno]';
            }
        }

        $this->view->render($this->viewDir . 'index',[
            'entiteti'=>$ordinacija
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
            $zadnjaSifraordinacija=Ordinacija::dodajNovi($this->entitet);
            header('location: ' . App::config('url') . 
            'ordinacija/promjena?sifra=' . $zadnjaSifraordinacija);
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
            $this->entitet = Ordinacija::ucitaj($_GET['sifra']);
            $datum=date('Y-m-d\TH:i', strtotime($this->entitet->datumpocetka));
            $this->entitet->datumpocetka=$datum;
            $this->poruka='Promjenite željene podatke';
            $this->promjenaView();
            return;
        }
        $this->entitet = (object) $_POST;
        try {
            $this->kontrola();
            Ordinacija::promjeniPostojeci($this->entitet);
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
        Ordinacija::obrisiPostojeci($_GET['sifra']);
        header('location: ' . App::config('url') . 'Ordinacija/index');
       
    }

    public function dodajradnika()
    {
        Ordinacija::dodajRadnika();
        echo 'OK';
    }

    public function obrisiradnika()
    {
        Ordinacija::obrisiRadnika();
        echo 'OK';
    }







    

    private function noviEntitet()
    {
        $this->entitet = new stdClass();
        $this->entitet->naziv='';
        $this->entitet->Narudzbe=-1;
        $this->entitet->doktor=-1;
        $this->entitet->datumpocetka=date('Y-m-d\TH:i');
        $this->poruka='Unesite tražene podatke';
        $this->novoView();
    }

    private function promjenaView()
    {
        $this->view->render($this->viewDir . 'promjena',[
            'entitet'=>$this->entitet,
            'poruka'=>$this->poruka,
            'Narudzbe'=>$this->Narudzbe,
            'doktor'=>$this->doktor,
            'css'=>'<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">',
            'js'=>'<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
            <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
            <script src="' . App::config('url') . 'public/js/ordinacija/promjena.js"></script>'
        ]);
    }


    private function novoView()
    {
        $this->view->render($this->viewDir . 'novo',[
            'entitet'=>$this->entitet,
            'poruka'=>$this->poruka,
            'Narudzbe'=>$this->Narudzbe,
            'doktor'=>$this->Doktor
        ]);
    }

    private function kontrola()
    {
        $this->kontrolaNaziv();
        $this->kontrolaNarudzbe();
        $this->kontroladoktori();
    }

    private function kontrolaNaziv()
    {
        if(strlen(trim($this->entitet->naziv))==0){
            throw new Exception('Naziv obavezno');
        }

        if(strlen(trim($this->entitet->naziv))>20){
            throw new Exception('Naziv predugačko');
        }
    }

    private function kontrolaNarudzbe()
    {
        if($this->entitet->Narudzbe==-1){
            throw new Exception('Narudzbe obavezno');
        }
    }

    private function kontroladoktori()
    {
        if($this->entitet->doktori==-1){
            throw new Exception('Doktor obavezno');
            
        }
    }



}
<?php


class GrupaController extends AutorizacijaController
{
    private $viewDir = 'privatno'
                        . DIRECTORY_SEPARATOR
                        . 'grupa'
                        . DIRECTORY_SEPARATOR;
    
    private $entitet=null;
    private $poruka='';
    private $smjerovi=null;
    private $predavaci=null;

    public function __construct()
    {
        parent::__construct();
        $this->smjerovi=Smjer::ucitajSve();
        
        $s=new stdClass();
        $s->sifra=-1;
        $s->naziv='Odaberite smjer';
        array_unshift($this->smjerovi,$s);


        $this->predavaci=Polaznik20::ucitajSve();
        $s=new stdClass();
        $s->sifra=-1;
        $s->ime='Odabrite';
        $s->prezime='Polaznika';
        array_unshift($this->predavaci,$s);
    }

    public function index()
    {

        $grupe=Grupa::ucitajSve();

        foreach($grupe as $g){
            //https://www.php.net/manual/en/datetime.format.php
            $g->datumpocetka=date('d.m.Y. H:i', strtotime($g->datumpocetka));
            if($g->predavac==null){
                $g->predavac='[nije postavljeno]';
            }
        }

        $this->view->render($this->viewDir . 'index',[
            'entiteti'=>$grupe
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
            $zadnjaSifraGrupe=Grupa::dodajNovi($this->entitet);
            header('location: ' . App::config('url') . 
            'grupa/promjena?sifra=' . $zadnjaSifraGrupe);
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
            $this->entitet = Grupa::ucitaj($_GET['sifra']);
            $datum=date('Y-m-d\TH:i', strtotime($this->entitet->datumpocetka));
            $this->entitet->datumpocetka=$datum;
            $this->poruka='Promjenite željene podatke';
            $this->promjenaView();
            return;
        }
        $this->entitet = (object) $_POST;
        try {
            $this->kontrola();
            Grupa::promjeniPostojeci($this->entitet);
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
        Grupa::obrisiPostojeci($_GET['sifra']);
        header('location: ' . App::config('url') . 'grupa/index');
       
    }







    

    private function noviEntitet()
    {
        $this->entitet = new stdClass();
        $this->entitet->naziv='';
        $this->entitet->smjer=-1;
        $this->entitet->predavac=-1;
        $this->entitet->datumpocetka=date('Y-m-d\TH:i');
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
            'poruka'=>$this->poruka,
            'smjerovi'=>$this->smjerovi,
            'predavaci'=>$this->predavaci
        ]);
    }

    private function kontrola()
    {
        $this->kontrolaNaziv();
        $this->kontrolaSmjer();
        $this->kontrolaPredavac();
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

    private function kontrolaSmjer()
    {
        if($this->entitet->smjer==-1){
            throw new Exception('Smjer obavezno');
        }
    }

    private function kontrolaPredavac()
    {
        if($this->entitet->predavac==-1){
            throw new Exception('Predavač obavezno');
        }
    }

}
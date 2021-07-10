<?php

class SmjerController extends AutorizacijaController
{
    private $viewDir = 'privatno'
                        . DIRECTORY_SEPARATOR
                        . 'smjer'
                        . DIRECTORY_SEPARATOR;
    
    private $smjer=null;
    private $poruka='';

    public function index()
    {
        $this->view->render($this->viewDir . 'index',[
            'smjerovi'=>Smjer::ucitajSve()
        ]);
    }

    public function novo()
    {
        if($_SERVER['REQUEST_METHOD']==='GET'){
            $this->noviSmjer();
            return;
        }
        $this->smjer = (object) $_POST;
        if(!$this->kontrolaNaziv()){return;}
        if(!$this->kontrolaTrajanje()){return;}
        if(!$this->kontrolaCijena()){return;}
        Smjer::dodajNovi($this->smjer);
        $this->index();
    }

    public function promjena()
    {
        if($_SERVER['REQUEST_METHOD']==='GET'){
            if(!isset($_GET['sifra'])){
               $ic = new IndexController();
               $ic->logout();
               return;
            }
            $this->smjer = Smjer::ucitaj($_GET['sifra']);
            $this->poruka='Promjenite željene podatke';
            $this->promjenaView();
            return;
        }
        $this->smjer = (object) $_POST;
        if(!$this->kontrolaNaziv()){return;}
        if(!$this->kontrolaTrajanje()){return;}
        // neću odraditi na promjeni kontrolu cijene
        Smjer::promjeniPostojeci($this->smjer);
        $this->index();
    }

    public function brisanje()
    {
        if(!isset($_GET['sifra'])){
            $ic = new IndexController();
            $ic->logout();
            return;
        }
        Smjer::obrisiPostojeci($_GET['sifra']);
        header('location: ' . App::config('url') . 'smjer/index');
       
    }

    private function noviSmjer()
    {
        $this->smjer = new stdClass();
        $this->smjer->naziv='';
        $this->smjer->trajanje=100;
        $this->smjer->cijena=1000;
        $this->smjer->verificiran='0';
        $this->poruka='Unesite tražene podatke';
        $this->novoView();
    }

    private function novoView()
    {
        $this->view->render($this->viewDir . 'novo',[
            'smjer'=>$this->smjer,
            'poruka'=>$this->poruka
        ]);
    }

    private function promjenaView()
    {
        $this->view->render($this->viewDir . 'promjena',[
            'smjer'=>$this->smjer,
            'poruka'=>$this->poruka
        ]);
    }


    private function kontrolaNaziv()
    {
        if(strlen(trim($this->smjer->naziv))===0){
            $this->poruka='Naziv obavezno';
            $this->novoView();
            return false;
         }
 
         if(strlen(trim($this->smjer->naziv))>50){
            $this->poruka='Naziv ne može imati više od 50 znakova';
            $this->novoView();
            return false;
         }
         return true;
    }


    private function kontrolaTrajanje()
    {
        if(!is_numeric($this->smjer->trajanje)
            || ((int)$this->smjer->trajanje)<=0){
                $this->poruka='Trajanje mora biti cijeli pozitivni broj';
            $this->novoView();
            return false;
      }
         return true;
    }


    private function kontrolaCijena()
    {
        $this->smjer->cijena=str_replace(',','.',$this->smjer->cijena);
        if(!is_numeric($this->smjer->cijena)
              || ((float)$this->smjer->cijena)<=0){
                $this->poruka='Cijena mora biti pozitivni broj';
              $this->novoView();
              return false;
        }
         return true;
    }
    

}
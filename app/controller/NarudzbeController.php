<?php

class NarudzbeController extends AutorizacijaController
{
    private $viewDir = 'privatno'
                        . DIRECTORY_SEPARATOR
                        . 'Narudzbe'
                        . DIRECTORY_SEPARATOR;

    private $Narudzbe=null;
    private $poruka='';

    public function index()
    {
        $this->view->render($this->viewDir . 'index',[
            'Narudzbe'=>Narudzbe::ucitajSve()
        ]);
    }

    public function novo()
    {
        if($_SERVER['REQUEST_METHOD']==='GET'){
            $this->noviNarudzbe();
            return;
        }

        $this->Narudzbe = (object) $_POST;
        if(!$this->kontrolaNaziv()){return;}
        if(!$this->kontrolaTrajanje()){return;}
        if(!$this->kontrolaCijena()){return;}
        Narudzbe::dodajNovi($this->Narudzbe);
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
            $this->Narudzbe = Narudzbe::ucitaj($_GET['sifra']);
            $this->poruka='Promjenite željene podatke';
            $this->promjenaView();
            return;
        }
        $this->Narudzbe = (object) $_POST;
        if(!$this->kontrolaNaziv()){return;}
        if(!$this->kontrolaTrajanje()){return;}
        // neću odraditi na promjeni kontrolu cijene
        Narudzbe::promjeniPostojeci($this->Narudzbe);
        $this->index();
        }

        public function brisanje()
        {
            if(!isset($_GET['sifra'])){
                $ic = new IndexController();
                $ic->logout();
                return;
            }
            Narudzbe::obrisiPostojeci($_GET['sifra']);
            header('location: ' . App::config('url') . 'Narudzbe/index');
           
        }
      
        private function noviNarudzbe()
        {
            $this->Narudzbe = new stdClass();
            $this->Narudzbe->naziv='';
            $this->Narudzbe->trajanje=10;
            $this->Narudzbe->cijena=1000;
            $this->Narudzbe->potvrda='0';
            $this->poruka='Unesite tražene podatke';
            $this->novoView();
        }
       
        private function novoView()
        {
            $this->view->render($this->viewDir . 'novo',[
                'Narudzbe'=>$this->Narudzbe,
                'poruka'=>$this->poruka
            ]);
        }
    
        private function promjenaView()
        {
            $this->view->render($this->viewDir . 'promjena',[
                'Narudzbe'=>$this->Narudzbe,
                'poruka'=>$this->poruka
            ]);
        }
    
    
        private function kontrolaNaziv()
        {
            if(strlen(trim($this->Narudzbe->naziv))===0){
                $this->poruka='Naziv obavezno';
                $this->novoView();
                return false;
             }
     
             if(strlen(trim($this->Narudzbe->naziv))>50){
                $this->poruka='Naziv ne može imati više od 50 znakova';
                $this->novoView();
                return false;
             }
             return true;
        }

        private function kontrolaTrajanje()
        {
            if(!is_numeric($this->Narudzbe->trajanje)
                || ((int)$this->Narudzbe->trajanje)<=0){
                    $this->poruka='Trajanje mora biti cijeli pozitivni broj';
                $this->novoView();
                return false;
          }
             return true;
        }
        
        private function kontrolaCijena()
        {
            $this->Narudzbe->cijena=str_replace(',','.',$this->Narudzbe->cijena);
        if(!is_numeric($this->Narudzbe->cijena)
              || ((float)$this->Narudzbe->cijena)<=0){
                $this->poruka='Cijena mora biti pozitivni broj';
              $this->novoView();
              return false;
        }
         return true;
    }

      

   
}
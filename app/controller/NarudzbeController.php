<?php

class narudzbeController extends AutorizacijaController
{
    private $viewDir = 'privatno'
                        . DIRECTORY_SEPARATOR
                        . 'narudzbe'
                        . DIRECTORY_SEPARATOR;

    private $narudzbe=null;
    private $poruka='';

    public function index()
    {
        $this->view->render($this->viewDir . 'index',[
            'narudzbe'=>narudzbe::ucitajSve()
        ]);
    }

    public function novo()
    {
        if($_SERVER['REQUEST_METHOD']==='GET'){
            $this->novinarudzbe();
            return;
        }

        $this->narudzbe = (object) $_POST;
        if(!$this->kontrolaNaziv()){return;}
        if(!$this->kontrolaTrajanje()){return;}
        if(!$this->kontrolaPotvrde()){return;}
        narudzbe::dodajNovi($this->narudzbe);
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
            $this->narudzbe = narudzbe::ucitaj($_GET['sifra']);
            $this->poruka='Promjenite željene podatke';
            $this->promjenaview();
            return;
        }
        $this->narudzbe = (object) $_POST;
        if(!$this->kontrolaNaziv()){return;}
        if(!$this->kontrolaTrajanje()){return;}
        // neću odraditi na promjeni kontrolu cijene
        narudzbe::promjeniPostojeci($this->narudzbe);
        $this->index();
        }

        public function brisanje()
        {
            if(!isset($_GET['sifra'])){
                $ic = new IndexController();
                $ic->logout();
                return;
            }
            narudzbe::obrisiPostojeci($_GET['sifra']);
            header('location: ' . App::config('url') . 'narudzbe/index');
           
        }
      
        private function novinarudzbe()
        {
            $this->narudzbe = new stdClass();
            $this->narudzbe->naziv='';
            $this->narudzbe->trajanje=10;
            $this->narudzbe->potvrde=1000;
            $this->narudzbe->potvrda='0';
            $this->poruka='Unesite tražene podatke';
            $this->novoview();
        }
       
        private function novoview()
        {
            $this->view->render($this->viewDir . 'novo',[
                'narudzbe'=>$this->narudzbe,
                'poruka'=>$this->poruka
            ]);
        }
    
        private function promjenaview()
        {
            $this->view->render($this->viewDir . 'promjena',[
                'narudzbe'=>$this->narudzbe,
                'poruka'=>$this->poruka
            ]);
        }
    
    
        private function kontrolaNaziv()
        {
            if(strlen(trim($this->narudzbe->naziv))===0){
                $this->poruka='Naziv obavezno';
                $this->novoview();
                return false;
             }
     
             if(strlen(trim($this->narudzbe->naziv))>50){
                $this->poruka='Naziv ne može imati više od 50 znakova';
                $this->novoview();
                return false;
             }
             return true;
        }

        private function kontrolaTrajanje()
        {
            if(!is_numeric($this->narudzbe->trajanje)
                || ((int)$this->narudzbe->trajanje)<=0){
                    $this->poruka='Trajanje mora biti cijeli pozitivni broj';
                $this->novoview();
                return false;
          }
             return true;
        }
        
        private function kontrolaPotvrde()
        {
            $this->narudzbe->potvrde=str_replace(',','.',$this->narudzbe->potvrde);
        if(!is_numeric($this->narudzbe->potvrde)
              || ((float)$this->narudzbe->potvrde)<=0){
                $this->poruka='Potvrda mora biti valjana';
              $this->novoview();
              return false;
        }
         return true;
    }

      

   
}
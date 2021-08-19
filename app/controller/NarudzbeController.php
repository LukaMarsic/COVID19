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
            $this->narudzbe->doza=1;
            $this->narudzbe->placanje='0';
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
        
        private function kontroladoza()
        {
            $this->narudzbe->doza=str_replace(',','.',$this->narudzbe->doza);
        if(!is_numeric($this->narudzbe->doza)
              || ((float)$this->narudzbe->doza)<=0){
                $this->poruka='Doza mora biti valjana';
              $this->novoview();
              return false;
        }
         return true;
    }

      

   
}
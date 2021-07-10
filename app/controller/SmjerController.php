<?php

class SmjerController extends AutorizacijaController
{
    private $viewDir = 'privatno'
                        . DIRECTORY_SEPARATOR
                        . 'smjer'
                        . DIRECTORY_SEPARATOR;

    public function index()
    {
        $this->view->render($this->viewDir . 'index',[
            'smjerovi'=>Smjer::ucitajSve()
        ]);
    }
    public function novo()
    {
        if($_SERVER['REQUEST_METHOD']==='GET'){
            $smjer = new stdClass();
            $smjer->naziv='';
            $smjer->trajanje=100;
            $smjer->cijena=1000;
            $smjer->verificiran='0';

             // $this->view->render($this->viewDir . 'novo',[
           //     'smjer'=>$smjer,
           //     'poruka'=>'Popunite sve podatke'
           // ]);
           $this->novoView($smjer,'Popunite sve podatke');

            return;
        }


        $smjer = (object) $_POST;

        if(strlen(trim($smjer->naziv))===0){
            // $this->view->render($this->viewDir . 'novo',[
           //     'smjer'=>$smjer,
          //      'poruka'=>'Naziv obavezno'
           // ]);
           // linija ispod mijenja 4 linije iznad
            $this->novoView($smjer,'Naziv obavezno');
            return;
        }

        if(strlen(trim($smjer->naziv))>50){
            $this->novoView($smjer,'Naziv ne može imati više od 50 znakova');
            return;
        }
        
        if(!is_numeric($smjer->trajanje)
            || ((int)$smjer->trajanje)<=0){
            $this->novoView($smjer,'Trajanje mora biti cijeli pozitivni broj');
            return;
      }

      $smjer->cijena=str_replace(',','.',$smjer->cijena);
      if(!is_numeric($smjer->cijena)
            || ((float)$smjer->cijena)<=0){
            $this->novoView($smjer,'Cijena mora biti pozitivni broj');
            return;
      }

   // npr. svojstvu verificiran ne treba kontrola

      // ovdje sam siguran da je sve OK prije odlaska u bazu
      Smjer::dodajNovi($smjer);
      $this->index();
       
    }

    private function novoView($smjer, $poruka)
    {
        $this->view->render($this->viewDir . 'novo',[
            'smjer'=>$smjer,
            'poruka'=>$poruka
        ]);
    }

}


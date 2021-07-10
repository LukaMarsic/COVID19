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
            $this->view->render($this->viewDir . 'novo',[
                'smjer'=>$smjer,
                'poruka'=>'Popunite sve podatke'
            ]);
            return;
        }


        $smjer = (object) $_POST;

        if(strlen(trim($smjer->naziv))===0){
            $this->view->render($this->viewDir . 'novo',[
                'smjer'=>$smjer,
                'poruka'=>'Naziv obavezno'
            ]);
            return;
        }



       
    }
}


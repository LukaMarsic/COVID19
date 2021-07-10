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
        $this->view->render($this->viewDir . 'novo');
    }
}


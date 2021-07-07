<?php

class AutorizacijaController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!isset($_SESSION['autoriziran'])){
            $this->view->render('login',[
                'email'=>'',
                'poruka'=>'Prvo se logirajte'
            ]);
            exit;
        }
    }
}
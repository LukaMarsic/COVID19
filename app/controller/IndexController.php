<?php

class IndexController extends Controller
{
    public function index()
    {
        $this->view->render('index');
    }

    public function era()
    {
        $this->view->render('era');
    }

    public function kontakt()
    {
        $this->view->render('kontakt');
    }


    public function login()
    {
        $this->loginView('','');
    }

    public function autorizacija()
    {
        if(!isset($_POST['email']) || !isset($_POST['lozinka'])){
            $this->login();
            return; //short curcuiting
        }

        if(strlen(trim($_POST['email']))===0){
            $this->loginView('','Obavezno email');
            return;
        }

        if(strlen(trim($_POST['lozinka']))===0){
            $this->loginView($_POST['email'],'Obavezno lozinka');
            return;
        }

    }

    private function loginView($email,$poruka)
    {
        $this->view->render('login',[
            'email'=>$email,
            'poruka'=>$poruka
        ]);
    }




}
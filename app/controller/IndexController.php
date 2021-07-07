
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

    public function logout()
    {
        unset($_SESSION['autoriziran']);
        session_destroy();
        $this->index();
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

    
    


        if(!password_verify($_POST['lozinka'],$rezultat->lozinka)){
            $this->loginView($_POST['email'],'Kombinacija email i lozinka ne odgovaraju');
            return;
        }

        unset($rezultat->lozinka);
        $_SESSION['autoriziran']=$rezultat;
        $np = new NadzornaplocaController();
        $np->index();

    }

    private function loginView($email,$poruka)
    {
        $this->view->render('login',[
            'email'=>$email,
            'poruka'=>$poruka
        ]);
    }

    
    /*
    public function test()
    {
        
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('select * from smjer');
        $izraz->execute();
        $rezultati = $izraz->fetchAll();
        print_r($rezultati);
    }
    
    */
}
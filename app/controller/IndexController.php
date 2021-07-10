<?php


class IndexController extends Controller
{
    public function index()
    {
       
        $this->view->render('index',[

            'rezultat'=>2+2,
            'drugi'=>'ttt'
        ]);
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
            return; 
        }

        if(strlen(trim($_POST['email']))===0){
            $this->loginView('','Obavezno email');
            return;
        }

        if(strlen(trim($_POST['lozinka']))===0){
            $this->loginView($_POST['email'],'Obavezno lozinka');
            return;
        }

        {
            $this->loginView($_POST['email'],'Email ne postoji u bazi');
            return;
            
        }
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
        select * from operater where email=:email');



        $izraz->execute(['email'=>$_POST['email']]);
        $rezultat = $izraz->fetch();

        
        
        if($rezultat==null){
            $this->loginView($_POST['email'],'Email ne postoji u bazi');
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


    public function test()
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('select * from smjer');
        $izraz->execute();
        $rezultati = $izraz->fetchAll();
        print_r($rezultati);
    } 
    
     /*
    public function test()
    {
        $veza = DB::getInstanca();
@ -78,5 +91,5 @@ class IndexController extends Controller
        $rezultati = $izraz->fetchAll();
        print_r($rezultati);
    }
    
    */

    
}


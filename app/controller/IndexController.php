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
}
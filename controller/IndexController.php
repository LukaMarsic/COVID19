<?php

class IndexController extends Controller
{
    public function index()
    {
        $this->view->render('index',[
            'rezultat'=>2+2
        ]);
    }

    public function drugo()
    {
        $this->view->render('drugo');
    }
}
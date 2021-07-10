<?php

$dev=$_SERVER['REMOTE_ADDR']==='127.0.0.1' ? true : false;
if($dev){
    $baza=[
        'server'=>'localhost',
        'baza'=>'cesar_pp22',
        'korisnik'=>'cesar_edunova',
        'lozinka'=>'edunova123'
    
    ];
}else{
    $baza=[
        'server'=>'localhost',
        'baza'=>'xxxxx',
        'korisnik'=>'xxxxx',
        'lozinka'=>'xxxxx'
    ];
}
return [
    'url'=>'http://polaznik20.edunova.hr/',
    'nazivApp'=>'STANKO',
    'baza'=>$baza,
    'rezultataPoStranici'=>4

    ];
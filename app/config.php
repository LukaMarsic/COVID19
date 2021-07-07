<?php

    $dev=$_SERVER['REMOTE_ADDR']==='127.0.0.1' ? true : false;
if($dev){
    $baza=[
        'server'=>'localhost',
        'baza'=>'edunovapp22',
        'korisnik'=>'edunova',
        'lozinka'=>'edunova'
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
    'url'=>'http://predavac01.edunova.hr/',
    'nazivApp'=>'Edunova APP',
    'baza'=>$baza
];
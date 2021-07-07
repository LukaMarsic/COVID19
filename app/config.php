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
        'baza'=>'cesar_pp22',
        'korisnik'=>'cesar_edunova',
        'lozinka'=>'edunova123.'
    ];
}
return [
    'url'=>'http://predavac01.edunova.hr/',
    'nazivApp'=>'Edunova APP',
    'baza'=>$baza
];
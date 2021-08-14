<?php

$dev=$_SERVER['REMOTE_ADDR']==='127.0.0.1' ? true : false;
if($dev){
    $baza=[
        'server'=>'localhost',
        'baza'=>'COVID19',
        'korisnik'=>'edunova',
        'lozinka'=>'edunova'
    
    ];
}else{
    $baza=[
        'server'=>'localhost',
        'baza'=>'erinije_COVID19',
        'korisnik'=>'erinije_erinije',
        'lozinka'=>'edunova123.'
    ];
}
return [
    'url'=>'http://polaznik20.edunova.hr/',
    'nazivApp'=>'COVID19',
    'baza'=>$baza,
    'rezultataPoStranici'=>4
];
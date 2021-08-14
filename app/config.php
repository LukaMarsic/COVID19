<?php

+$dev=$_SERVER['REMOTE_ADDR']==='127.0.0.1' ? true : false;
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
        'baza'=>'erinije_covid19 ',
        'korisnik'=>'erinije_covid19',
        'lozinka'=>'Edunova2020'
    ];
}
return [
    'url'=>'http://polaznik20.edunova.hr/',
    'nazivApp'=>'COVID19',
    'baza'=>$baza,
    'rezultataPoStranici'=>4
];
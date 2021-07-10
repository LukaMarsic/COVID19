

console.log('Hello iz konzole');

let ime='Edunova';
console.log(ime);
console.log(typeof ime);
let broj=2;
let iznos=4.9;
console.log(typeof broj);
console.log(typeof iznos);
let n=Array();
console.log(typeof n);
let niz=[2,'Ime',true,4.3];
console.log(typeof niz);
console.log(niz);

let an=[]; // asocijativni niz - tretira kao objekt
an['k1']=2;
an['k2']='pero';
console.log(typeof an);
console.log(an.k1);

console.log(niz.length);

let objekt = {k1: 2,k2: 'Pero'}; //ekvivalent 22 - 24
console.log(objekt.k1);

let i='2';
if(i===2){
    console.log('OK');
}else{
    console.log('NIJE');
}

console.log(i===2 ? 'OK':'Nije');

for(let i=0;i<100;i++){
    console.log(i + ": " +  (i%2===0 ? 'Parni':'NEPARNI'));
}


for(const i of niz){
    console.log(i);
}


for(let i=0;i<niz.length;i++){
    console.log(niz[i]);
}

let b=10;
while(b>0){
    console.log(b--);
}


function izvedi(){
    console.log('Iz funkcije');
}

izvedi();

function zbroj(a,b){
    return a+b;
}

console.log(zbroj(2,3));


$('#akcija1').click(function(){
    console.log('Poziv iz jquery click');
});


$('#akcija2').click(function(){
    $('#naslov').css('color','green');
});


$('#akcija3').click(function(){
    $.ajax({
        url: "/index/ajax",
        cache: false,
        success: function(html){
          console.log(html);
        }
      });
});



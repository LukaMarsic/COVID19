$('#uvjet').autocomplete({
    source: function(req,res){
        $.ajax({
            url:'/polaznik/trazipolaznike',
            data:{
                uvjet: req.term,
                grupa: grupa
            },
            success: function(odgovor){
                res(odgovor);
            }
        });
    },
    minLength: 2,
    select: function(dogadaj,ui){
        spremi(grupa,ui.item);
    }
}).autocomplete('instance')._renderItem=function(ul,polaznik){
    return $('<li>').append('<div>' + polaznik.ime + ' ' + polaznik.prezime +
    '</div>').appendTo(ul);
};

function spremi(grupa,polaznik){
    //console.log('grupa:' + grupa);
    //console.log('polaznik:' + polaznik.sifra);
    $.ajax({
        type:'POST',
        url:'/grupa/dodajpolaznika',
        data:{
            polaznik: polaznik.sifra,
            grupa: grupa
        },
        success: function(odgovor){
            if(odgovor==='OK'){
                $('#polaznici').append('<tr>' +
                '<td>' + polaznik.ime + ' ' + polaznik.prezime + '</td>' +
                '<td>' +
                '<a class="brisanje" href="#" id="p_' + polaznik.sifra + '">' +
                    '<i title="brisanje" style="color: red" class="fas fa-trash-alt" aria-hidden="true"></i><span class="sr-only">brisanje</span>' +
                '</a>' +
                '</td>' +
            '</tr>');
            definirajBrisanje();
            }else{
                alert(odgovor);
            }
        }
    });
}

function definirajBrisanje(){
    $('.brisanje').click(function(){
        let element=$(this);
        let sifra = element.attr('id').split('_')[1];
        //console.log('grupa:' + grupa);
        //console.log('polaznik:' + sifra);
        $.ajax({
            type:'POST',
            url:'/grupa/obrisipolaznika',
            data:{
                polaznik: sifra,
                grupa: grupa
            },
            success: function(odgovor){
                if(odgovor==='OK'){
                    element.parent().parent().remove();
                }else{
                    alert(odgovor);
                }
               
            }
        });
        return false;
    });
}
definirajBrisanje();
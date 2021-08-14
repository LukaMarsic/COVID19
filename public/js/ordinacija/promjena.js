$('#uvjet').autocomplete({
    source: function(req,res){
        $.ajax({
            url:'/radnik/traziradnike',
            data:{
                uvjet: req.term,
                ordinacija: ordinacija
            },
            success: function(odgovor){
                res(odgovor);
            }
        });
    },
    minLength: 2,
    select: function(dogadaj,ui){
        spremi(ordinacija,ui.item);
    }
}).autocomplete('instance')._renderItem=function(ul,radnik){
    return $('<li>').append('<div>' + radnik.ime + ' ' + radnik.prezime +
    '</div>').appendTo(ul);
};

function spremi(ordinacija,radnik){
    //console.log('radnik:' + ordinacija);
    //console.log('radnik:' + radnik.sifra);
    $.ajax({
        type:'POST',
        url:'/ordinacija/dodajradnika',
        data:{
            radnik: radnik.sifra,
            ordinacija: ordinacija
        },
        success: function(odgovor){
            if(odgovor==='OK'){
                $('#radnici').append('<tr>' +
                '<td>' + radnik.ime + ' ' + radnik.prezime + '</td>' +
                '<td>' +
                '<a class="brisanje" href="#" id="p_' + radnik.sifra + '">' +
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
        //console.log('ordinacija:' + ordinacija);
        //console.log('radnik:' + sifra);
        $.ajax({
            type:'POST',
            url:'/ordinacija/obrisiradnika',
            data:{
                radnik: sifra,
                ordinacija: ordinacija
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
$(document).ready(function () {
    // aktif istemcilerin listesini alalım
    function getAktifIstemciler(){
        $.ajax({
            url         : 'index.php?component=mole_panel&command=getAktifIstemciler',
            type        : 'POST',
            dataType    : "json",
            success     : function(istemciler){
                $('#mole-left-colum').empty();
                $.each(istemciler, function( index, value ) {
                    console.log(value);
                    var dugme ='<div class="btn btn-default istemci_dugmesi" ' +
                        'mole_istemciler_id="' + value.mole_istemciler_id +
                        '">' +
                        value.istemci_adi +
                        '</div>';
                    $('#mole-left-colum').append(dugme);
                });
            },
            error: errorHandler = function() {
                alert("errorHandler()");
            }
        });
    }


    //istemci PC leri temsil eden düğmeye tıklanınca
    $('#mole-conteiner').on('click','.istemci_dugmesi',function () {
        var mole_istemciler_id = $(this).attr('mole_istemciler_id')
        alert(mole_istemciler_id)
    })




    ///////////////////////////////////////////////////BAŞLA///////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    getAktifIstemciler();
})
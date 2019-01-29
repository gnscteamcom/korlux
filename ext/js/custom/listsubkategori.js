/* 
 * 
 * Team2One
 * v. 16 June 2016
 * 
 */


$('#kategori').change(function(){
    var kategori = $(this).val();
    
    if(kategori == ""){
        $('#subkategori').empty();
        $('#subkategori').append('<option value=""> -- Silahkan pilih kategori dahulu -- </option>');
    }
    else{
        $.post(
            "/api/getsubcategory",
            {
                kategori: kategori,
                _token: token
            },
            function(data){

                $('#subkategori').empty();

                if(data == null || data == ''){
                    $('#subkategori').append('<option value=""> -- Kosong -- </option>');
                }
                else{
                    $('#subkategori').append('<option value=""> -- Silahkan Pilih -- </option>');
                    $.each(data, function(key, element){
                        $('#subkategori').append('<option value="' + element.id  + '">' + element.subcategory + '</option>');
                    });
                }

            }
        );
    }
    
});

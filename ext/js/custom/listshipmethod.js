/*
 *
 * Team2One
 * v. 3 Apr 2016
 *
 */


var cur_domain = document.domain;
cur_domain = "koreanluxury.com";

$('#kecamatan').blur(function() {
    $.post(
        "http://www.team2one.com/api/getlistshipmethod",
        {
            domain: cur_domain,
            kota: $('#kota').val(),
            kecamatan: $('#kecamatan').val(),
            _token: token
        },
        function(data){

            $('#ship_method').empty();

            data = $.parseJSON(data);

            if(data == null || data == ''){
                $('#ship_method').append('<option value=""> -- Kosong -- </option>');
            }
            else if(data.rc == 00){
                $('#ship_method').append('<option value=""> -- Silahkan Pilih -- </option>');
                $.each(data.m, function(key, element){
                    var value = element.shipmethod_name + ' - ' + element.shipmethod_type;
                    $('#ship_method').append('<option value="' + value  + '">' + value + '</option>');
                });
            }
            else{
                $('#ship_method').append('<option value=""> -- ' + data.m + ' -- </option>');
            }

        }
    );
});

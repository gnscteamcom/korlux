/* 
 * 
 * Team2One
 * v. 3 Apr 2016
 * 
 */


$('#ship_method').change(function(){

    var kec_value = $('#kecamatan').val();
    if(kec_value != null && kec_value != ""){
        $.post(
            "http://www.team2one.com/api/getongkoskirim",
            {
                kota: $('#kota').val(),
                kecamatan: kec_value,
                ship_method: $(this).val(),
                _token: token
            },
            function(data){

                data = $.parseJSON(data);

                if(data == null || data == ''){
                    $('#biaya_kirim_text').val("-");
                    $('#biaya_kirim').val("");
                }
                else if(data.rc == 00){
                    var ongkir = parseFloat(data.m.price);
                    $('#biaya_kirim_text').val("Rp. " + ongkir.toLocaleString());
                    $('#biaya_kirim').val(ongkir);
                }
                else{
                    $('#biaya_kirim_text').val("-");
                    $('#biaya_kirim').val("");
                }

            }

        );
    }

});
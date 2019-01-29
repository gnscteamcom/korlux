/* 
 * 
 * Team2One
 * v. 3 Apr 2016
 * 
 */
    
$('#kota').blur(function() {
    $.post(
        "http://www.team2one.com/api/getlistkecamatan",
        {
            kota: $(this).val(),
            _token: token
        },
        function(data){

            $('#kecamatan').empty();

            data = $.parseJSON(data);
            var availableKecamatan = [];

            if(data == null || data == ''){
                $('#kecamatan').prop('disabled', true);
                document.getElementById("combobox-kecamatan").innerHTML = " -- Kosong -- ";
            }
            else if(data.rc == 00){
                $('#kecamatan').prop('disabled', false);
                var html = "";
                $.each(data.m, function(key, element){
                    html += "<option value=" + element.kecamatan + ">" + element.kecamatan + "</option>";
                });
                $('#kecamatan').attr('placeholder', 'Masukkan Kecamatan Anda');
                document.getElementById("combobox-kecamatan").innerHTML = html;
            }
            else{
                $('#kecamatan').prop('disabled', true);
                document.getElementById("combobox-kecamatan").innerHTML = " -- " + data.m + " -- ";
            }

        }
    );

});
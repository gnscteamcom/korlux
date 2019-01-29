/* 

 * 
 * Team2One
 * v. 3 Apr 2016
 * 
 */


$.post(
    "http://www.team2one.com/api/getlistkota",
    {
        _token: token
    },
    function(data){

        $('#kota').empty();

        data = $.parseJSON(data);

        if(data == null || data == ''){
            document.getElementById("combobox-kota").innerHTML = " -- Kosong -- ";
        }
        else if(data.rc == 00){
            var html = "";
            $.each(data.m, function(key, element){
                html += "<option value=" + element.kota + ">" + element.kota + "</option>";
            });
            document.getElementById("combobox-kota").innerHTML = html;
        }
        else{
            document.getElementById("combobox-kota").innerHTML = " -- Koneksi Gagal -- ";
        }
    }
);

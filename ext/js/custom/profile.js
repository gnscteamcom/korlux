$('#type').change(function(){
    
    var type = $(this).val();
    
    switch(type){
        case "addTo":
            $("#addTo").show();
            $("#addFrom").hide();
            getAddToList();
            // enabledAddTo();
            break;
        case "addFrom":
            $("#addTo").hide();
            $("#addFrom").show();
            getAddFromList();
            // enabledAddFrom();
            break;
        default:
            $("#addTo").hide();
            $("#addFrom").hide();
            disabledAddFrom();
            disabledAddTo();
            clearAddFrom();
            clearAddTo();
            break;
    }
    
});


$('#nama_alamat').change(function(){
    
    var customeraddress_id = $(this).val();
    
    if(customeraddress_id == ''){
        disabledAddTo();
        clearAddTo();
    }
    else{
        enabledAddTo();
        $.post(
            "/api/getaddtodata",
            {
                customeraddress_id: customeraddress_id,
            },
            function(data){
                if(data == null || data == ''){
                    clearAddTo();
                }
                else{
                    $("#addTo #nama_depan").prop('value', data.first_name);
                    $("#addTo #nama_belakang").prop('value', data.last_name);
                    $("#addTo #alamat").prop('value', data.alamat);
                    $("#addTo #addto_kota_curr").prop('value', data.kota);
                    $("#addTo #addto_kecamatan_curr").prop('value', data.kecamatan);
                    $("#addTo #kodepos").prop('value', data.kodepos);
                    $("#addTo #hp").prop('value', data.hp);
                }
            }
        );
    }
    
});


$('#nama_pengiriman').change(function(){
    
    var dropship_id = $(this).val();
    
    if(dropship_id == ''){
        disabledAddFrom();
        clearAddFrom();
    }
    else{
        enabledAddFrom();
        $.post(
            "api/getaddfromdata",
            {
                dropship_id: dropship_id,
            },
            function(data){
                if(data == null || data == ''){
                    clearAddFrom();
                }
                else{
                    $("#addFrom #dikirim_oleh").prop('value', data.name);
                    $("#addFrom #hp_pengirim").prop('value', data.hp);
                }
            }
        );
    }
    
});


function getAddToList(){
    
    $.get(
        "api/getaddtolist",
        {
        },
        function(data){
            
            $('#nama_alamat').empty();

            if(data == null || data == ''){
                $('#nama_alamat').append('<option value=""> -- Kosong -- </option>');
            }
            else{
                $('#nama_alamat').append('<option value=""> -- Silahkan Pilih -- </option>');
                $.each(data, function(key, element){
                    $('#nama_alamat').append('<option value="' + element.id  + '">' + element.address_name + '</option>');
                });
            }

        }
    );
    
}

function getAddFromList(){
    
    $.get(
        "api/getaddfromlist",
        {
        },
        function(data){
            
            $('#nama_pengiriman').empty();

            if(data == null || data == ''){
                $('#nama_pengiriman').append('<option value=""> -- Kosong -- </option>');
            }
            else{
                $('#nama_pengiriman').append('<option value=""> -- Silahkan Pilih -- </option>');
                $.each(data, function(key, element){
                    $('#nama_pengiriman').append('<option value="' + element.id  + '">' + element.dropship_name + '</option>');
                });
            }

        }
    );
    
}


function disabledAddTo(){
    $("#addTo #nama_depan").prop('disabled', true);
    $("#addTo #nama_belakang").prop('disabled', true);
    $("#addTo #alamat").prop('disabled', true);
    $("#addTo #addto_kota").prop('disabled', true);
    $("#addTo #addto_kecamatan").prop('disabled', true);
    $("#addTo #kodepos").prop('disabled', true);
    $("#addTo #hp").prop('disabled', true);
    $("#addTo #addto_kota_curr").prop('disabled', true);
    $("#addTo #addto_kecamatan_curr").prop('disabled', true);
}

function enabledAddTo(){
    $("#addTo #nama_depan").prop('disabled', false);
    $("#addTo #nama_belakang").prop('disabled', false);
    $("#addTo #alamat").prop('disabled', false);
    $("#addTo #addto_kota").prop('disabled', false);
    $("#addTo #addto_kecamatan").prop('disabled', false);
    $("#addTo #kodepos").prop('disabled', false);
    $("#addTo #hp").prop('disabled', false);
    $("#addTo #addto_kota_curr").prop('disabled', true);
    $("#addTo #addto_kecamatan_curr").prop('disabled', true);

}

function clearAddTo(){
    $("#addTo #nama_depan").prop('value', '');
    $("#addTo #nama_belakang").prop('value', '');
    $("#addTo #alamat").prop('value', '');
    $("#addTo #addto_kota_curr").prop('value', '');
    $("#addTo #addto_kecamatan_curr").prop('value', '');
    $("#addTo #addto_kecamatan").prop('value', '');
    $("#addTo #addto_kota").prop('value', '');
    $("#addTo #kodepos").prop('value', '');
    $("#addTo #hp").prop('value', '');

}

function disabledAddFrom(){
    $("#addFrom #dikirim_oleh").prop('disabled', true);
    $("#addFrom #hp_pengirim").prop('disabled', true);
}

function enabledAddFrom(){
    $("#addFrom #dikirim_oleh").prop('disabled', false);
    $("#addFrom #hp_pengirim").prop('disabled', false);
}

function clearAddFrom(){
    $("#addFrom #dikirim_oleh").prop('value', '');
    $("#addFrom #hp_pengirim").prop('value', '');
}
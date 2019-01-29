var token = $('input[name=_token]').val();

$('#msg').delay(3000).fadeOut();
$('#err').delay(3000).fadeOut();

$('#kirim_dropship').click(function(){
    var is_checked = $(this).is(':checked');
    
    if(is_checked){
        $('#dropship_utama').attr('style', 'display:none');
        $('#dropship_baru').attr('style', 'display:block');
    }else{
        $('#dropship_baru').attr('style', 'display:none');
        $('#dropship_utama').attr('style', 'display:block');
    }
});

$('#kirim_alamat_saya').click(function(){
    var is_checked = $(this).is(':checked');
    
    if(is_checked){
        $('#alamat_kirim_saya').attr('style', 'display:none');
        $('#alamat_kirim_baru').attr('style', 'display:block');
        $('#kecamatan_text').val($('#kecamatan_dropdown option:selected').text());
        $('#kecamatan').val($('#kecamatan_dropdown option:selected').val()).trigger('change');
    }else{
        $('#alamat_kirim_baru').attr('style', 'display:none');
        $('#alamat_kirim_saya').attr('style', 'display:block');
        $('#kecamatan_text').val($('#kecamatan_utama_text').val());
        $('#kecamatan').val($('#kecamatan_utama_id').val()).trigger('change');
    }
});

$('.kecamatan').select2();

$('#kecamatan_dropdown').change(function(){
    $('#kecamatan_text').val($('#kecamatan_dropdown option:selected').text());
    $('#kecamatan').val($('#kecamatan_dropdown option:selected').val()).trigger('change');
});

$('#kecamatan').change(function(){
    getShipMethod();
});
$('#kecamatan').trigger('change');

function step1next() {
    document.getElementById("tab-1").className = "col-md-6 col-xs-6 text-center";
    document.getElementById("tab-2").className = "active col-md-6 col-xs-6 text-center";
}

function step2before() {
    document.getElementById("tab-1").className = "active col-md-6 col-xs-6 text-center";
    document.getElementById("tab-2").className = "col-md-6 col-xs-6 text-center";
}

function getShipMethod(){
    $('#ship_method').empty();
    $('#ship_method').append('<option value="" disabled selected> -- Loading -- </option>');
    $('#ship_method').val('');
    
    $.post(
        "/api/getshipmethod",
        {
            kecamatan_id: $('#kecamatan').val(),
            _token: token
        },
        function(data){

            $('#ship_method').empty();
            data = $.parseJSON(data);
            
            if(data.count > 0){
                $.each(data.data, function (key, element) {

                    if (element.ship_method != "SICEPAT - CARGO") {
                        //kalau bukan kargo
                        if (element.ship_method == "JNE - OKE") {
                            $('#ship_method').append('<option value="' + element.id + '">' + element.ship_method + " ( wajib asuransi )" + '</option>');
                        } else {
                            $('#ship_method').append('<option value="' + element.id + '">' + element.ship_method + '</option>');
                        }
                    } else {
                        //kalau kargo, dia minimum 5kg.
                        var total_weight_kg = $('#total_weight_kg').val();

                        if (total_weight_kg >= 5) {
                            $('#ship_method').append('<option value="' + element.id + '">' + element.ship_method + ' ( Min. 5 KG )</option>');
                        }
                    }
                });
                
                //set default ke sicepat-reg (id = 4)
                $('#ship_method').val(4).trigger('change');
            }else{
                $('#ship_method').append('<option value="" disabled selected> -- ' + data.no_method_id + ' -- </option>');
            }

        }
    );
    
}

$('#ship_method').change(function(){
    
    $("#order-btn").attr("disabled", "disabled");
    var ship_method = $(this).val();
    
    $('#ship_method_text').val($('#ship_method option:selected').text());
    $('#ship_method_text').val($('#ship_method_text').val().replace(" ( wajib asuransi )", ""));
    
    checkResiOtomatis($(this).val());
    
    $.post(
        "/api/getshipcost",
        {
            kecamatan_id: $('#kecamatan').val(),
            ship_method: ship_method,
            _token: token
        },
        function(data){

            data = $.parseJSON(data);
            var shipcost = 0;

            if(data.data > 0){
                shipcost = data.data;
                $('#ship_cost').val(shipcost);
            }

            $.post(
                "api/setShipCost",
                {
                    shipcost: shipcost,
                    ship_method: ship_method,
                    _token: token
                },
                function(data){

                    data = JSON.parse(data);

                    $('#weight').text(data.weight);

                    //update insurance cost
                    if(ship_method == 1){
                        getInsuranceFee();
                        blockInsurance();
                    }
                    else{
                        setInsuranceFee(0);
                        unblockInsurance();
                    }
                    
                    $('#packing_fee_text').text('Rp. ' + parseFloat(data.packing_fee).toLocaleString());

                }

            );

        }

    );
    
});


$('#kode_btn').click(function(){
    
    var kode = $('#kode_val').val().trim();
    
    if(kode != null && kode != ""){

        $.post(
            "api/checkKode",
            {
                kode: kode,
                _token: token
            },
            function(data){

                if(data > 0){
                    $('#kode_val').prop('readonly', true);
                    $('#kode_btn').prop('disabled', true);
                    $('#poin_val').prop('readonly', true);
                    $('#poin_btn').prop('disabled', true);
                    $('#free_sample').val(0);
                    $('#free_sample_notif').hide();
                }
                else{
                    $('#kode_val').val('');
                    $('#kode_val').prop('readonly', false);
                    $('#kode_btn').prop('disabled', false);
                    
                    alert('KODE KUPON SALAH ATAU TIDAK DAPAT DIGUNAKAN.');
                }
                
                updateSummary();

            }

        );
    }
    
});


$('#poin_btn').click(function(){
    
    var poin = $('#poin_val').val();
    var max = $('#poin_val').attr('max');
    
    if($.isNumeric(poin)){

        $.post(
            "api/checkPoin",
            {
                poin: poin,
                _token: token
            },
            function(data){

                if(data > 0){
                    $('#poin_val').prop('readonly', true);
                    $('#poin_btn').prop('disabled', true);
                    $('#kode_val').prop('readonly', true);
                    $('#kode_btn').prop('disabled', true);
                    $('#free_sample').val(0);
                    $('#free_sample_notif').hide();
                }
                else{
                    $('#poin_val').val('');
                    $('#poin_val').prop('readonly', false);
                    $('#poin_btn').prop('disabled', false);
                }
                
                updateSummary();

            }

        );
        
    }
    else{
        $('#poin_val').val('');
        $('#poin_val').prop('readonly', false);
        $('#poin_btn').prop('disabled', false);
    }
    
});



$('#insurance').change(function(){
    
    var check = this.checked;

    if(check){
        getInsuranceFee();
    }
    else{
        setInsuranceFee(0);
    }
    
});

$('#order-btn').click(function(e){  
    $("#order-btn").attr("disabled", true);
    $('#form-order').submit();
});


function getInsuranceFee(){
    $.post(
        "/api/getinsurancefee",
        {
            ship_method: $('#ship_method').val(),
            total: $('#shop_total').val(),
            _token: token
        },
        function(data){

            data = $.parseJSON(data);
            
            setInsuranceFee(data.data);

        }

    );
}

function setInsuranceFee(cost){
    $.post(
        "api/setinsurancecost",
        {
            insurancecost: cost,
            ship_method:$('#ship_method').val(),
            _token: token
        },
        function(data){
            updateSummary();
        }

    );
}

function updateSummary(){

    $.post(
        "api/getSummary",
        {
            _token: token
        },
        function(data){

            $('#shipcost').text('Rp. ' + parseFloat(data.shipcost).toLocaleString());
            $('#discountcoupon').text('Rp. ' + parseFloat(data.discountcoupon).toLocaleString());
            $('#discountpoint').text('Rp. ' + parseFloat(data.discountpoint).toLocaleString());
            $('#insurancecost').text('Rp. ' + parseFloat(data.insurancecost).toLocaleString());
            $('#grandtotal').text('Rp. ' + parseFloat(data.total).toLocaleString());

            $("#order-btn").attr("disabled", false);
        }

    );
    
}

function blockInsurance(){
    $('#insurance').prop("checked", true);
    $('#insurance').attr("disabled", true);
}

function unblockInsurance(){
    $('#insurance').prop("checked", false);
    $('#insurance').removeAttr("disabled");
}

function checkResiOtomatis(resi_type){
    /*
     * Hanya berlaku untuk reseller ke atas,
     * dan apabila memilih J&T
     */
    if ($('#userstatus').val() > 1) {
        if (resi_type == 7) {
            $('#resi_otomatis_div').removeAttr();
            $('#resi_otomatis_div').attr('style', 'display:block;');
        } else {
            $('#resi_otomatis_div').removeAttr();
            $('#resi_otomatis_div').attr('style', 'display:none;');
        }
    }
}

$('#resi_otomatis').change(function () {
    var panjang_resi = $('#resi_otomatis').val().length;
    var shipcost = $('#ship_cost').val();
    if (panjang_resi >= 12) {
        shipcost = 0;
    }

    $.post(
            "api/setShipCost",
            {
                shipcost: shipcost,
                ship_method: 7,
                _token: token
            },
            function (data) {

                updateSummary();

            }

    );
});
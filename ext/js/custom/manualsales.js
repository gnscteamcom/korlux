$('#kecamatan').change(function () {
    $.post(
            "/api/getshipmethod",
            {
                kecamatan_id: $(this).val(),
                _token: token
            },
            function (data) {

                $('#ship_method').empty();

                data = $.parseJSON(data);

                if (data.count > 0) {
                    $('#ship_method').append('<option value="" disabled selected> -- Silahkan Pilih -- </option>');
                    $.each(data.data, function (key, element) {
                        if (element.ship_method == "JNE - OKE") {
                            $('#ship_method').append('<option value="' + element.id + '">' + element.ship_method + " ( wajib asuransi )" + '</option>');
                        } else {
                            $('#ship_method').append('<option value="' + element.id + '">' + element.ship_method + '</option>');
                        }
                    });
                } else {
                    $('#ship_method').append('<option value="" disabled selected> -- ' + data.no_method_id + ' -- </option>');
                }

            }
    );
});

$('#ship_method').change(function () {
    
    $('#ship_method_text').val($('#ship_method option:selected').text());

    var kec_value = $('#kecamatan').val();
    if (kec_value != null && kec_value != "") {
        $.post(
                "/api/getshipcost",
                {
                    kecamatan_id: kec_value,
                    ship_method: $(this).val(),
                    _token: token
                },
                function (data) {

                    data = $.parseJSON(data);
                    var shipcost = 0;

                    if(data.data > 0){
                        shipcost = data.data;
                    }

                    var ongkir = parseFloat(shipcost);
                    $('#biaya_kirim_text').val("Rp. " + ongkir.toLocaleString());
                    $('#biaya_kirim').val(shipcost);

                }

        );
    }

});

function addRow() {
    var product = $('#product').val();
    var qty = $('#qty').val();

    if (product && qty) {
        if (qty != 0) {
            addProduct(product, qty);
        }
        $('#tags').val('');
        $('#qty').val(1);
    }
}

function addProduct(product, qty) {

    $.post(
        "/api/addmanualsales",
        {
            product: product,
            qty: qty,
            user_id:$('#user_id').val(),
            gunakan_stok:$('#gunakan_stok').val(),
            _token: token
        },
        function (data) {
            data = JSON.parse(data);

            $('#product_list').empty();
            var html = '';
            $.each(data.data, function(key, element){
                html = html 
                        + '<tr id=' + element.rowid + '>'
                        + '<td>'
                        + element.product_name
                        + '</td>'
                        + '<td>'
                        + element.price
                        + '</td>'
                        + '<td>'
                        + element.qty
                        + '</td>'
                        + '<td>'
                        + '<button type="button" class="deleteRow" data-row-id="' + element.rowid + '"><i class="fa fa-fw fa-close"></i></button>'
                        + '</td>'
                        + '</tr>';
            });
            $('#product_list').append(html);
            
            $('.deleteRow').click(function(){
                $.post(
                    "/api/removemanualsales",
                    {
                        rowid: $(this).attr('data-row-id'),
                        _token: token
                    },
                    function (data) {
                        $('#' + data).remove();
                });
            });
        }
    );
}


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
                    let total_weight = $('#total_weight').val()
                    let total_biaya_kirim = ongkir * parseInt(total_weight / 1000 + 1, 10)
                    $("#total_biaya_kirim").val(total_biaya_kirim)
                    $('#biaya_kirim_text').val("Rp. " + total_biaya_kirim.toLocaleString());
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
            status_id: $('#status_id').val(),
            _token: token
        },
        function (data) {
            data = JSON.parse(data);

            $('#product_list').empty();
            var html = '';
            let total_weight = 0;
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
                        + '<button type="button" class="deleteRow" data-row-id="' + element.rowid + '"><i class="fa fa-fw fa-times"></i></button>'
                        + '</td>'
                        + '</tr>';
                total_weight += parseInt(element.weight, 10) * parseInt(element.qty, 10);
            });
            $('#product_list').append(html);

            //calculate total shipment cost
            $('#total_weight').val(total_weight)
            let biaya_kirim = $('#biaya_kirim').val()
            if(biaya_kirim) {
              let total_biaya_kirim = biaya_kirim * parseInt(total_weight / 1000 + 1, 10)
              $("#total_biaya_kirim").val(total_biaya_kirim)
              $('#biaya_kirim_text').val(`Rp. ${total_biaya_kirim.toLocaleString()}`)
            }

            $('.deleteRow').click(function(){
                $.post(
                    "/api/removemanualsales",
                    {
                        rowid: $(this).attr('data-row-id'),
                        _token: token
                    },
                    function (data) {
                      data = JSON.parse(data)

                      $('#' + data.rowid).remove();

                      let biaya_kirim_rem = $('#biaya_kirim').val()
                      let total_weight_rem = data.total_weight
                      $('#total_weight').val(total_weight_rem)

                      if(biaya_kirim_rem) {
                        let total_biaya_kirim_rem = biaya_kirim_rem * parseInt(total_weight_rem / 1000 + 1, 10)
                        $("#total_biaya_kirim").val(total_biaya_kirim_rem)
                        $('#biaya_kirim_text').val(`Rp. ${total_biaya_kirim_rem.toLocaleString()}`)
                      }
                });
            });

        }
    );
}

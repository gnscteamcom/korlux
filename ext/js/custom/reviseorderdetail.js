/* 
 * 
 * Irwan
 * 21 Sep 2017
 * 
 */

function addRow() {
    var product = $('#product').val();
    console.log('here');

    if (product) {
        addProduct(product);
        $('#tags').val('');
    }
}

function removeLastRow() {

    $.post(
            "/api/removewholesale",
            {
                _token: token
            },
            function (data) {
                $('#product_list tr:last-child').remove();
            }
    );

}

function addProduct(product) {

    $.post(
            "/api/addwholesale",
            {
                product: product,
                qty: $('#qty').val(),
                price: $('#price').val(),
                _token: token
            },
            function (data) {

                if (data == null || data == '') {
                    alert('Tidak ada stok');
                } else {
                    if (data.err) {
                        alert(data.err);
                    } else {
                        $('#product_list').append('<tr>'
                                + '<td>'
                                + data.barcode
                                + '</td>'
                                + '<td>'
                                + data.product_name
                                + '</td>'
                                + '<td>'
                                + data.price
                                + '</td>'
                                + '<td>'
                                + data.qty
                                + '</td>'
                                + '<td>'
                                + data.buy_qty
                                + '</td>');
                    }
                }

            }
    );
}

function getPrice(product) {
    $.post(
            "/api/getPrice",
            {
                product_id: product,
                price_cat: 0,
                _token: token
            },
            function (data) {
                $('#price').val(data);
            }
    );
}
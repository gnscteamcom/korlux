/* 
 * 
 * Team2One
 * 21 Juni 2016
 * 
 */
var token = $('input[name="_token"]').val();

function addRow(){
    var product = $('#products').val();

    if(product){
        addProduct(product);
        $('#products').val('');
    }
}

function removeLastRow(){
    $('#product_list tr:last-child').remove();
}

function addProduct(product){

    $.post(
        "/api/addproductset",
        {
            product: product,
            _token: token
        },
        function(data){

            if(data == null || data == ''){
                alert('Tidak ada stok');
            }
            else{
                if(data.err){
                    alert(data.err);
                }
                else{
                    $('#product_list').append('<tr>'
                        + '<input type="hidden" name="product_id[]" value="' + data.product_id + '"/>'
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
                        + '</tr>');
                }
            }

        }
    );
}
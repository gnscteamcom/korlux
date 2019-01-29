$(".addToCart").submit(function(e){
    e.preventDefault();
    var value = $(this).serializeArray();
    var _token = $("input[name=_token]").val();
    var modal_id = '#' + $(this).attr('data-product-id');
    $.post(
        "/cart/addtocart",
        {
            _token: _token,
            data: value
        },
        function(data){
            if(data.result == 0){
                toastr.error(data.msg);
                if(data.link.length > 0){
                    setTimeout(function(){
                        window.location.href = data.link;
                    }, 3000);
                }
            }else{
                toastr.info(data.msg);
                $('#cart_count').text(data.totalItem + ' barang di keranjang');
                $(modal_id).modal('toggle');
                $('#continue-shop-modal').modal('toggle');
                $('#product_cart').text(data.product_cart);
            }
    });
});

$('#loadMore').click(function(){
    $.post(
        "/products/loadmore",
        {
            _token: $('input[name=_token]').val(),
            brand_id: $('#brand_id').val(),
            category_id: $('#category_id').val(),
            subcategory_id: $('#subcategory_id').val(),
            sort: $('#sort').val(),
            search: $('#search').val(),
            page_number: $('#page_number').val()
        },
        function(data){
            data = JSON.parse(data);
            
            $('.loadMore').attr('style', 'display:none');
            if(data.count > 0){
                toastr.success(data.msg);
                $('#page_number').val(data.page_number);
                showProduct(data.data);
                $('.loadMore').attr('style', 'display:block');
            }
            else{
                toastr.error(data.msg);
                $('.loadMore').attr('style', 'display:none');
            }
    });
});

function showProduct(data){
    $.each(data, function(key, element){
        var html_row = "<div class='row'>";
        $('#product_list').append(html_row);
        var i = 0;
        
        $.each(element, function(key_data, value){
            var html = "";
            if(i == 0){
                html = html + "<div class='col-lg-1 col-md-1 col-sm-1'></div>";
            }
            html = html + "<div class='col-md-2 col-sm-6 col-xs-6'>"
                        + "<div class='product'> "
                        + "<div class='image'>";
            
            if(value.qty <= 0){
                html = html + "<div class='ribbon ribbon-quick-view sale'>"
                        + "<div class='soldoutribbon'>&nbsp;&nbsp;&nbsp;Sold Out</div>"
                        + "<div class='ribbon-background'></div>"
                        + "</div>"
            }
            
            if(value.is_wholesale){
                html = html + "<div class='ribbon ribbon-quick-view sale'>"
                        + "<div class='theribbon'>&nbsp;&nbsp;&nbsp;Grosir</div>"
                        + "<div class='ribbon-background'></div>"
                        + "</div>";
            }
            
            if(value.sale_price > 0){
                html = html + "<div class='ribbon ribbon-quick-view sale margin-top-35'>"
                        + "<div class='theribbon bg-teal'>&nbsp;&nbsp;&nbsp;Sale</div>"
                        + "<div class='ribbon-background'></div>"
                        + "</div>"
            }
            
            html = html + "<a href='#' data-toggle='modal' data-target='#" + value.id + "'>";
            
            html = html + "<div class='lazyload'>"
                    + "<img src='" + value.image_path + "' alt='" + value.product_name + "' class='img-responsive lazy' width='450px' height='450px'>"
                    + "</div>";
            
            html = html + "</a>"
                    + "</div>"
                    + "<div class='text'>"
                    + "<p class='brand margin-0'>"
                    + "<a href='#' data-toggle='modal' data-target='#" + value.id + "'>" + value.brand_name + "</a>"
                    + "</p>"
                    + "<p  class='margin-0'> "
                    + "<strong>"
                    + "<a style='font-size:12px;' href='#' data-toggle='modal' data-target='#" + value.id + "' class='product-name'>" + value.product_name + "</a>"
                    + "</strong>"
                    + "</p>";

            if (value.sale_price > 0) {
                html = html + "<strike><p class='price'>" + value.regular_price_text + "</p></strike>"
                        + "<strong><p class='price fg-red'>" + value.sale_price_text + "</p></strong>";
            } else {
                html = html + "<p class='price'>" + value.sell_price_text + "</p>";
            }
            
            
            //Bagian popup
            html = html + "<div id='" + value.id + "' tabindex='-1' role='dialog' aria-hidden='false' class='modal fade'>"
                    + "<div class='modal-dialog modal-lg'>"
                    + "<div class='modal-content'>"
                    + "<div class='modal-body'>"
                    + "<div class='row quick-view product-main'>"
                    + "<div class='col-sm-6'>"
                    + "<div class='quick-view-main-image'>"
                    + "<img src='" + value.image_path + "' alt='" + value.product_name + "' class='img-responsive'>"
                    + "</div>"
                    + "<div class='row thumbs'>";
                    
            $.each(value.productimages, function(key_image, image){
                html = html + "<div class='col-xs-4'>"
                                + "<a href='" + image.image_path + "' class='thumb'>"
                                + "<div class='lazyload'>"
                                + "<img src='" + image.image_path + "' alt='" + value.product_name + "' class='img-responsive lazy' width='450px' height='450px'>"
                                + "</div>"
                                + "</a>"
                                + "</div>";
            });
        
            html = html + "</div>"
                        + "</div>"
                        + "<div class='col-sm-6'>"
                        + "<h4 class='product__heading text-center'>" + value.product_name + "</h4>";
                
            if(value.is_set){
                if(value.set_count > 0){
                    html = html + "<strong>"
                        + "<span class='text-center col-md-12'>Set Produk</span>"
                        + "</strong>"
                        + "<strong>"
                        + "<p class='text-center'>";
                
                    $.each(value.product_sets, function(set_key, set){
                        html = html + "- " + set.name + "<br>";
                    });
                    
                    html = html + "</p>"
                        + "</strong>";
                }
            }
    
            html = html + "<p class='text-muted text-small text-center'>"
                        + value.product_desc +
                        + "</p>"
                        + "<div class='box'>";
            
            if(value.sell_price > 0 && value.qty > 0){
                html = html + "<form method='post' action='#' id='addToCart" + value.id + "' data-product-id='" + value.id + "'>";
            }
            
            html = html + "<input type='hidden' value='" + value.id + "' name='product_id'/>"
                                    + "<input type='hidden' value='" + value.sell_price + "' name='price'/>";
            
            if (value.sale_price > 0) {
                html = html + "<strike><h4 class='text-center'>" + value.regular_price_text + "</h4></strike>"
                    + "<strong><p class='price text-center'>" + value.sale_price_text + "</p></strong>";
            } else {
                html = html + "<p class='price text-center'>" + value.sell_price_text + "</p>";
            }
            
            if(value.is_wholesale){
                html = html + "<div class='row'>"
                    + "<div class='col-md-7 col-md-offset-3'>"
                    + "<div class='form-group'>"
                    + "<label for='qty'>Harga Grosir</label>";
                
                $.each(value.productclasses, function(productclass_key, productclass){
                    html = html + "<label for='qty'>Beli " + productclass.min_qty + " : " + productclass.price_text + " / item</label>";
                });
                
                html = html + "</div>"
                    + "</div>"
                    + "</div>"
            }
            
            html = html + "<div class='row margin-top-20'>"
                    + "<div class='col-md-7 col-md-offset-3'>"
                    + "<div class='form-group'>"
                    + "<label for='qty'>Quantity (Stok : " + value.qty + " barang)</label>"
                    + "<input type='number' value='1' min='1' name='qty' max='" + value.qty + "' name='qty' class='form-control' required='required'>"
                    + "</div>"
                    + "</div>"
                    + "</div>"
                    + "<p class='text-center margin-bottom-80'>";
            
            if(value.sell_price > 0 && value.qty > 0){
                html = html + "<button type='submit' class='btn btn-primary margin-top-10 col-xs-12 col-md-5 pull-right'><i class='fa fa-shopping-cart'></i>&nbsp;Tambahkan</button>"
            }
            
            html = html + "<button type='button' class='btn btn-default margin-top-10 col-xs-12 col-md-5 pull-left' data-dismiss='modal'><i class='fa fa-remove'></i>&nbsp;Tutup</button>"
                                    + "</p>";
                            
            if(value.sell_price > 0 && value.qty > 0){
                html = html + "</form>";
            }
            
            html = html + "</div>"
                    + "</div>"
                    + "</div>"
                    + "</div>"
                    + "</div>"
                    + "</div>"
                    + "</div>"
                    + "</div>"
                    + "</div>"
                    + "</div>";
            i++;
            
            $('#product_list').append(html);
            
            $("#addToCart" + value.id).submit(function(e){
                e.preventDefault();
                var value = $(this).serializeArray();
                var _token = $("input[name=_token]").val();
                var modal_id = '#' + $(this).attr('data-product-id');
                $.post(
                    "/cart/addtocart",
                    {
                        _token: _token,
                        data: value
                    },
                    function(data){
                        if(data.result == 0){
                            toastr.error(data.msg);
                            if(data.link.length > 0){
                                setTimeout(function(){
                                    window.location.href = data.link;
                                }, 3000);
                            }
                        }else{
                            toastr.info(data.msg);
                            $('#cart_count').text(data.totalItem + ' barang di keranjang');
                            $(modal_id).modal('toggle');
                            $('#continue-shop-modal').modal('toggle');
                            $('#product_cart').text(data.product_cart);
                        }
                });
            });
        });
        i = 0;
        html_row = "</div>";
        $('#product_list').append(html_row);
        
        
        
    });
}
<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::group(['middleware' => ['web']], function () {

    Route::get('test', 'FrontEndController@test');

    #FB LOGIN
    Route::post('fb/login', 'Auth\AuthController@doLoginFB');

    #ADD TO CART
    Route::post('cart/addtocart', 'CartController@addToCartAutomatic');

    #PAYMENT LINK
    Route::get('paymentlink/{link}', 'FrontEndController@viewPaymentLink');

    #CONFIRM PAYMENT
    Route::post('confirmpayment', 'OrderController@confirmPayment');
    Route::post('confirmpaymentlink', 'OrderController@confirmPaymentLink');

    //Menu & Process Flow
    Route::get('/', 'FrontEndController@viewFrontEnd');
    Route::get('home', 'FrontEndController@viewFrontEnd');
    Route::get('howto', 'FrontEndController@viewHowto');
    Route::get('reseller', 'FrontEndController@viewReseller');
    Route::get('about', 'FrontEndController@viewAbout');
    Route::post('sendmessage', 'FrontEndController@sendMessage');
    Route::get('login', 'FrontEndController@viewLogin');

    #PRODUCTS
    Route::get('products', 'FrontEndController@viewProducts');
    Route::post('products/loadmore', 'ScrollController@loadMore');

    //Filter
    Route::get('show/{brand}/{category}/{subcategory}/{sort}/{search?}', 'FrontEndController@showResult');



    //Admin Login Side
    Route::get('cp/usr-lgn', 'LoginController@viewLogin');
    Route::get('logout', 'Auth\AuthController@doLogout');
    Route::post('auth/login', 'Auth\AuthController@doLogin');


    //Register
    Route::get('viewregister', 'UserController@viewRegister');
    Route::post('register', 'UserController@registerUser');


    //Forgot Password
    Route::get('resetpassword', 'FrontEndController@viewResetPassword');
    Route::post('resetpassword', 'UserController@resetPassword');
    Route::get('resetusername', 'FrontEndController@viewResetUsername');
    Route::post('resetusername', 'UserController@resetUsername');


    #EXTERNAL LINK
    Route::get('extlink/redirect/{link}', 'ExternalLinkController@redirectExternal');



    Route::group(['middleware' => 'auth'], function() {

        Route::group(['middleware' => 'role:1'], function(){

            //Home admin
            Route::get('adminhome', 'WebSettingsController@viewSettings');

            #REFUND
            Route::get('viewrefund', 'RefundController@viewRefund');
            Route::get('refund/finish/{id}', 'RefundController@finishRefund');
            Route::post('rejectrefund', 'RefundController@rejectRefund');
            Route::post('acceptrefund', 'RefundController@acceptRefund');

            //Khusus untuk paksa update dailyprice
            Route::get('updatedailyprice', 'DailyPriceController@updateDailyPrice');

            //Menu Settings
            Route::get('websettings', 'WebSettingsController@viewSettings');
            Route::get('othersettings', 'OtherSettingsController@viewSettings');
            Route::get('freesample', 'FreeSampleController@viewFreeSample');

            Route::post('updatecontact', 'WebSettingsController@updateContact');
            Route::post('updateaddress', 'WebSettingsController@updateAddress');
            Route::post('updateterms', 'WebSettingsController@updateTerm');
            Route::post('updatesocialmedia', 'OtherSettingsController@updateSocialMedia');
            Route::post('updatefreesample', 'FreeSampleController@updateFreeSample');


            #########################
            #PACKING FEE
            #########################
            Route::get('packingfee', 'PackingFeeController@viewPackingFee');
            Route::post('packingfee/update', 'PackingFeeController@updatePackingFee');
            Route::post('packingfeecargo/update', 'PackingFeeController@updatePackingFeeCargo');



            //Menu untuk Order di bagian Admin
            Route::get('vieworder', 'OrderController@viewOrder');
            Route::get('vieworderdetail/{id}', 'OrderController@viewOrderDetail');
            Route::post('filtershiporder', 'OrderController@filterShipOrder');
            Route::get('completeorder/{id}', 'OrderController@completeOrderCustomer');
            Route::post('rejectorder', 'OrderController@rejectOrderCustomer');
            Route::get('revertcancelorder/{id}', 'OrderController@revertCancelOrder');
            Route::get('isprint/{id}', 'OrderController@isPrint');


            //Menu bagian payment
            Route::get('viewpayment', 'OrderController@viewPayment');
            Route::get('acceptpayment/{id}', 'OrderController@acceptPayment');
            Route::get('rejectpayment/{id}', 'OrderController@rejectPayment');
            Route::post('bulkacceptpayment', 'OrderController@bulkAcceptPayment');

            #MULTIPLE PAYMENT
            Route::get('multiplepayment/accept/{id}', 'PaymentMultipleController@acceptPayment');
            Route::get('multiplepayment/reject/{id}', 'PaymentMultipleController@rejectPayment');

            //Bagian konfirmasi pembayaran Admin
            Route::get('paymentconfirmationadmin', 'PaymentAdminController@viewNewOrder');
            Route::post('confirmpaymentadmin', 'PaymentAdminController@confirmPayment');
            Route::get('paymentconfirmationadmin/{id}', 'PaymentAdminController@viewPaymentConfirmation');


            //Menu untuk Stock
            Route::get('stockopname', 'StockOpnameController@viewStockOpname');
            Route::get('stockcorrection', 'StockOpnameController@viewStockCorrection');
            Route::post('stockcorrection', 'StockOpnameController@updateStockCorrection');
            Route::get('downloadstockopnameformat', 'StockOpnameController@downloadStockOpnameFormat');
            Route::post('importstockopname', 'StockOpnameController@importStockOpname');
            Route::get('stocktotal', 'StockOpnameController@viewStockTotal');
            Route::get('stocktotal/download', 'StockOpnameController@downloadStockTotal');

            #STOCK TRANSFER
            Route::get('stocktransfer', 'StockTransferController@stockTransfer');
            Route::post('stocktransfer', 'StockTransferController@transfer');

            #REVISI TRANSFER
            Route::get('stockrevise', 'StockReviseController@stockRevise');
            Route::get('stockrevise/revise', 'StockReviseController@stockReviseForm');
            Route::post('stockrevise/revise', 'StockReviseController@reviseStock');
            Route::get('stockrevise/download', 'StockReviseController@downloadFormat');
            Route::post('stockrevise/import', 'StockReviseController@reviseBulk');
            Route::get('stockreviselist', 'StockReviseController@stockReviseList');

            Route::post('stockrevise/approve', 'StockReviseController@approveRevise');
            Route::post('stockrevise/reject', 'StockReviseController@rejectRevise');


            //Menu untuk Users
            Route::get('viewuser', 'UserController@viewAllUser');
            Route::post('user/changestatus', 'UserController@updateUserStatus');
            Route::get('userdetail/{user_id}', 'UserController@viewUserDetail');
            Route::post('updateuserconfig', 'UserController@updateUserConfig');

            #API untuk RULES
            Route::post('api/rules/admin', 'RuleController@setIsAdmin');
            Route::post('api/rules/owner', 'RuleController@setIsOwner');
            Route::post('api/rules/menuaccess', 'RuleController@setMenuAccess');


            //Menu untuk Chat
            Route::get('chat-admin', 'ChatController@viewChatAdmin');

            //Menu Change Password & Update Profile
            Route::get('changepassword', 'UserController@changePassword');


            //Menu Brand
            Route::get('viewbrand', 'BrandController@viewBrand');
            Route::get('addbrand', 'BrandController@viewAddBrand');
            Route::post('addbrand', 'BrandController@insertBrand');
            Route::get('editbrand/{id}', 'BrandController@editBrand');
            Route::post('updatebrand', 'BrandController@updateBrand');
            Route::get('deletebrand/{id}', 'BrandController@deleteBrand');


            //Menu Category
            Route::get('viewcategory', 'CategoryController@viewCategory');
            Route::get('addcategory', 'CategoryController@viewAddCategory');
            Route::post('addcategory', 'CategoryController@insertCategory');
            Route::get('editcategory/{id}', 'CategoryController@editCategory');
            Route::post('updatecategory', 'CategoryController@updateCategory');
            Route::get('deletecategory/{id}', 'CategoryController@deleteCategory');


            //Menu Sub Category
            Route::get('viewsubcategory', 'SubCategoryController@viewSubCategory');
            Route::get('addsubcategory', 'SubCategoryController@viewAddSubCategory');
            Route::post('addsubcategory', 'SubCategoryController@insertSubCategory');
            Route::get('editsubcategory/{id}', 'SubCategoryController@editSubCategory');
            Route::post('updatesubcategory', 'SubCategoryController@updateSubCategory');
            Route::get('deletesubcategory/{id}', 'SubCategoryController@deleteSubCategory');


            //Menu Product
            Route::get('viewproduct/{id}', 'ProductController@viewProduct');
            Route::get('addproduct', 'ProductController@viewAddProduct');
            Route::post('addproduct', 'ProductController@insertProduct');
            Route::get('deleteproduct/{id}', 'ProductController@deleteProduct');
            Route::get('editproduct/{id}', 'ProductController@editProduct');
            Route::post('updateproduct', 'ProductController@updateProduct');

            Route::get('viewimportproduct', 'ProductController@viewImportProduct');
            Route::post('importproduct', 'ProductController@importProduct');
            Route::get('downloadproductformat', 'ProductController@downloadProductFormat');

            ####################
            ##PRODUCT SET
            ####################
            Route::get('productsets', 'ProductSetController@viewProductSets');
            Route::get('addproductset', 'ProductSetController@addProductSet');
            Route::post('insertproductset', 'ProductSetController@insertProductSet');
            Route::get('deleteproductset/{id}', 'ProductSetController@deleteProductSet');
            Route::get('editproductset/{id}', 'ProductSetController@editProductSet');
            Route::post('updateproductset', 'ProductSetController@updateProductSet');


            //Menu Product Bulk
            Route::post('bulkdeleteproduct', 'ProductBulkController@bulkDeleteProduct');
            Route::post('viewbulkupdateproduct', 'ProductBulkController@viewBulkUpdateProduct');
            Route::get('bulkproductfinish', 'ProductBulkController@bulkProductFinish');
            Route::get('downloadbulkproductformat/{product_id?}', 'ProductBulkController@downloadBulkProductFormat');
            Route::post('bulkupdateproduct', 'ProductBulkController@bulkUpdateProduct');



            //Menu Product Image
            Route::get('viewproductimage', 'ProductImageController@viewProductImage');
            Route::get('addproductimage', 'ProductImageController@viewAddProductImage');
            Route::post('uploadproductimage', 'ProductImageController@uploadProductImage');
            Route::get('editproductimage/{id}', 'ProductImageController@viewEditProductImage');
            Route::post('updateproductimage', 'ProductImageController@updateProductImage');
            Route::get('deleteproductimage/{id}', 'ProductImageController@deleteProductImage');
            Route::get('deleteoneproductimage/{id}', 'ProductImageController@deleteOneProductImage');



            //Menu Banner
            Route::get('viewbanner', 'BannerController@viewBanner');
            Route::get('addbanner', 'BannerController@viewAddBanner');
            Route::get('editbanner/{id}', 'BannerController@editBanner');
            Route::post('updatebanner', 'BannerController@updateBanner');
            Route::post('uploadbanner', 'BannerController@uploadBanner');
            Route::get('deletebanner/{id}', 'BannerController@deleteBanner');


            #############################
            #SHOPEE SALES
            #############################
            Route::get('shopeesales/stuck', 'ShopeeController@stuckShopeeSales');
            Route::get('shopeesales', 'ShopeeController@shopeeSales');
            Route::get('shopeesales/download', 'ShopeeController@downloadShopeeSales');
            Route::get('shopeesales/add/{id}', 'ShopeeController@addShopeeSales');
            Route::post('shopeesales/continue', 'ShopeeController@continueShopeeSales');
            Route::post('shopeesales/save', 'ShopeeController@insertShopeeSales');
            Route::post('shopeesales/import', 'ShopeeController@importShopeeSales');
            Route::match(['get', 'post'], 'shopeesales/search', 'ShopeeController@searchShopeeSales');


            //Menu Price
            Route::get('viewprice', 'PriceController@viewImportPrice');
            Route::post('importprice', 'PriceController@importPrice');
            Route::get('downloadpriceformat', 'PriceController@downloadPriceFormat');
            Route::get('editprice/{id}', 'PriceController@editPrice');
            Route::get('deleteprice/{id}', 'PriceController@deletePrice');
            Route::post('deleteprice/bulk', 'PriceController@deletePriceBulk');
            Route::post('updateprice', 'PriceController@updatePrice');
            Route::get('addprice', 'PriceController@addPrice');
            Route::post('insertprice', 'PriceController@insertPrice');


            //Menu Wholesale
            Route::get('viewwholesaleprice', 'WholesaleController@viewWholeSalePrice');
            Route::get('addwholesaleprice', 'WholesaleController@viewAddWholeSalePrice');
            Route::post('addwholesaleprice', 'WholesaleController@insertWholeSalePrice');
            Route::get('editwholesaleprice/{id}', 'WholesaleController@viewEditWholeSalePrice');
            Route::post('updatewholesaleprice', 'WholesaleController@updateWholeSalePrice');
            Route::get('deletewholesaleprice/{id}', 'WholesaleController@deleteWholeSalePrice');
            Route::get('downloadwholesaleformat', 'WholesaleController@downloadWholesaleFormat');
            Route::post('importwholesale', 'WholesaleController@importWholesale');
            Route::post('deleteallwholesale', 'WholesaleController@deleteAllWholesale');




            //Menu Discount Coupon
            Route::get('viewdiscountcoupon', 'DiscountCouponController@viewCouponDiscount');
            Route::get('adddiscountcoupon', 'DiscountCouponController@viewAddCouponDiscount');
            Route::post('insertdiscountcoupon', 'DiscountCouponController@insertCouponDiscount');
            Route::get('editdiscountcoupon/{id}', 'DiscountCouponController@viewEditCouponDiscount');
            Route::post('updatediscountcoupon', 'DiscountCouponController@updateCouponDiscount');
            Route::get('deletediscountcoupon/{id}', 'DiscountCouponController@deleteCouponDiscount');


            //Menu Loyalty Poin
            Route::get('viewdiscountpoint', 'DiscountPointController@viewDiscountPoint');
            Route::post('discountpoint/activate', 'DiscountPointController@toggleIsActivate');
            Route::post('discountpoint/refresh', 'DiscountPointController@refreshPoint');
            Route::get('discountpoint/add', 'DiscountPointController@addPoint');
            Route::post('discountpoint/insert', 'DiscountPointController@insertPoint');
            Route::get('discountpoint/edit/{id}', 'DiscountPointController@editPoint');
            Route::post('discountpoint/update', 'DiscountPointController@updatePoint');
            Route::get('discountpoint/delete/{id}', 'DiscountPointController@deletePoint');



            //Menu Bank
            Route::get('viewbank', 'BankController@viewBank');
            Route::get('addbank', 'BankController@viewAddBank');
            Route::post('addbank', 'BankController@insertBank');
            Route::get('editbank/{id}', 'BankController@editBank');
            Route::post('updatebank', 'BankController@updateBank');
            Route::get('deletebank/{id}', 'BankController@deleteBank');


            //Menu Stockin
            Route::get('viewstockin', 'StockinController@viewStockin');
            Route::get('viewimportstockin', 'StockinController@viewImportStockin');
            Route::post('importstockin', 'StockinController@importStockin');
            Route::get('downloadstockinformat', 'StockinController@downloadStockinFormat');
            Route::get('editstockin/{id}', 'StockinController@editStockin');
            Route::post('updatestockin', 'StockinController@updateStockin');
            Route::get('stockbalance', 'StockinController@viewStockBalance');
            Route::post('stockbalance', 'StockinController@downloadStockBalance');




            //Bagian Report
            Route::post('printdo', 'ReportController@printDO');
            Route::post('printalldo', 'ReportController@printAllShippedDO');



            //Bagian Export
            Route::get('viewexportlist', 'ExportController@viewExportList');
            Route::post('exportlist', 'ExportController@exportList');



            //Bagian manual Sales
            Route::get('manualsales', 'ManualSalesController@viewManualSales');
            Route::post('manualsales', 'ManualSalesController@processManualSales');
            Route::get('manualsales2', 'ManualSalesController@viewManualSales2');
            Route::post('submitmanualsales', 'ManualSalesController@submitManualSales');

            #Chat Sales
            Route::get('chatsales', 'ChatSalesController@viewChatSales');


            //Bagian Histori Manual Sales
            Route::get('manualsaleshistory', 'ManualSalesController@viewManualSalesHistory');


            ##################
            #RESELLER SALES
            ##################
            Route::get('resellersales', 'ResellerSalesController@viewResellerSales');
            Route::post('resellersales', 'ResellerSalesController@processResellerSales');
            Route::get('resellersales2', 'ResellerSalesController@viewResellerSales2');
            Route::post('submitresellersales', 'ResellerSalesController@submitResellerSales');
            Route::get('resellersales/addreseller', 'ResellerSalesController@addReseller');
            Route::post('resellersales/addreseller', 'ResellerSalesController@insertReseller');


            #Bagian untuk proses order
            Route::get('viewprocessorder', 'OrderController@viewProcess');
            Route::post('order/process', 'OrderController@processOrder');
            Route::post('order/adminnotes', 'OrderController@adminNotes');

            //Bagian untuk input nomor resi
            Route::get('viewinputshipment', 'ShipmentInvoiceController@viewInputShipment');
            Route::post('shipment', 'ShipmentInvoiceController@shipmentInvoice');
            Route::get('viewimportshipmentinvoice', 'ShipmentInvoiceController@viewImportShipmentInvoice');
            Route::get('downloadshipmentinvoiceformat', 'ShipmentInvoiceController@downloadShipmentInvoiceFormat');
            Route::post('importshipmentinvoice', 'ShipmentInvoiceController@importShipmentInvoice');

            Route::get('shipment/todaydownload', 'ShipmentInvoiceController@downloadTodayShipment');



            ################################
            //REVISE ORDER DETAIL & STATUS
            ################################
            Route::get('viewreviseorder', 'ReviseOrderController@viewReviseOrder');
            Route::get('viewreviseorderdetail/{id}', 'ReviseOrderController@viewReviseOrderDetail');
            Route::post('reviseorderdetail', 'ReviseOrderController@reviseOrderDetail');





            ################################
            //EXTERNAL LINK
            ################################
            Route::get('extlink', 'ExternalLinkController@externallinks');
            Route::get('extlink/add', 'ExternalLinkController@addExternallinks');
            Route::post('extlink/add', 'ExternalLinkController@saveExternallinks');
            Route::get('extlink/edit/{id}', 'ExternalLinkController@editExternallinks');
            Route::post('extlink/update', 'ExternalLinkController@updateExternallinks');
            Route::get('extlink/delete/{id}', 'ExternalLinkController@deleteExternallinks');


            ################################
            #KECAMATAN
            ################################
            Route::get('kecamatans', 'ShipmentKecamatanController@kecamatans');
            Route::get('kecamatan/add', 'ShipmentKecamatanController@addKecamatans');
            Route::post('kecamatan/add', 'ShipmentKecamatanController@saveKecamatans');
            Route::get('kecamatan/edit/{id}', 'ShipmentKecamatanController@editKecamatans');
            Route::post('kecamatan/update', 'ShipmentKecamatanController@updateKecamatans');
            Route::get('kecamatan/delete/{id}', 'ShipmentKecamatanController@deleteKecamatans');
            Route::get('kecamatan/download/{range?}', 'ShipmentKecamatanController@download');
            Route::post('kecamatan/import', 'ShipmentKecamatanController@import');


            ################################
            #KOTA
            ################################
            Route::get('kotas', 'ShipmentKotaController@kotas');
            Route::get('kota/add', 'ShipmentKotaController@addKotas');
            Route::post('kota/add', 'ShipmentKotaController@saveKotas');
            Route::get('kota/edit/{id}', 'ShipmentKotaController@editKotas');
            Route::post('kota/update', 'ShipmentKotaController@updateKotas');
            Route::get('kota/delete/{id}', 'ShipmentKotaController@deleteKotas');
            Route::get('kota/download', 'ShipmentKotaController@download');
            Route::post('kota/import', 'ShipmentKotaController@import');


            ################################
            #ONGKOS KIRIM
            ################################
            Route::get('shipcosts', 'ShipmentCostController@costs');
            Route::get('shipcost/download/{id}', 'ShipmentCostController@download');
            Route::post('shipcost/import', 'ShipmentCostController@import');


            ################################
            #METODE
            ################################
            Route::get('shipmethods', 'ShipmentMethodController@shipmethods');
            Route::get('shipmethod/add', 'ShipmentMethodController@addMethods');
            Route::post('shipmethod/add', 'ShipmentMethodController@saveMethods');
            Route::get('shipmethod/edit/{id}', 'ShipmentMethodController@editMethods');
            Route::post('shipmethod/update', 'ShipmentMethodController@updateMethods');
            Route::get('shipmethod/delete/{id}', 'ShipmentMethodController@deleteMethods');
            Route::post('shipmethod/activate', 'ShipmentMethodController@activateMethod');


            ################################
            #RESELLER CONFIGS
            ################################
            Route::get('resellerconfig', 'ResellerConfigController@viewResellerConfig');
            Route::post('resellerconfig', 'ResellerConfigController@updateResellerConfig');

        });



        Route::group(['middleware' => 'role:0'], function(){

            #UNTUK DOWNLOAD PRICE LIST KE EXCEL
            Route::get('pricelist', 'PriceListController@downloadPriceList');

            #REFUND
            Route::get('requestrefund', 'RefundController@viewRequestRefund');
            Route::post('requestrefund', 'RefundController@requestRefund');


            //List Ajax
            Route::post('api/checkKode', 'CartController@checkKode');
            Route::post('api/checkPoin', 'CartController@checkPoin');
            Route::post('api/setShipCost', 'CartController@setShipCost');
            Route::post('api/setinsurancecost', 'CartController@setInsuranceCost');
            Route::post('api/getSummary', 'CartController@getSummary');

            #ORDER HISTORY
            Route::post('order/getorderlist', 'OrderController@getOrderList');


            //Order
            Route::get('cart', 'CartController@viewCart');
            Route::post('addtocart', 'CartController@addToCart');

            Route::get('removecartitem/{id}', 'CartController@deleteItem');
            Route::post('updatecart', 'CartController@updateCart');
            Route::get('checkout', 'CartController@viewCheckout');
            Route::post('checkout', 'OrderController@placeOrder');
            Route::get('refreshcart', 'CartController@refreshCart');



            //Menu untuk lihat order history
            Route::get('paymentconfirmation', 'FrontEndController@viewPaymentConfirmation');
            Route::post('api/payment/total', 'AjaxController@paymentTotal');
            Route::get('stock', 'FrontEndController@viewStock');
            Route::get('history', 'HistoryController@viewOrderHistory');
            Route::get('orderdetail/{id}', 'HistoryController@viewOrderDetail');




            //Menu untuk update profile khusus buyer
            Route::get('profile', 'FrontEndController@viewProfile');



            //Bagian tambah Dropship & Address
            Route::get('addto', 'FrontEndController@viewAddTo');
            Route::get('addfrom', 'FrontEndController@viewAddFrom');
            Route::post('addto', 'OrderController@addTo');
            Route::post('addfrom', 'OrderController@addFrom');
            Route::post('updateto', 'OrderController@updateTo');
            Route::post('updatefrom', 'OrderController@updateFrom');

        });


        //Global Route
        Route::post('changepassword', 'UserController@updatePassword');
        Route::post('updatename', 'UserController@updateName');
        Route::post('updateprofile', 'UserController@updateProfile');


    });



    #########################
    //AJAX
    #########################
    Route::post('api/getSiteAddress', 'AjaxController@getSiteAddress');
    Route::post('api/getMyAddress', 'AjaxController@getMyAddress');
    Route::post('api/getMyCustomerAddress', 'AjaxController@getMyCustomerAddress');
    Route::post('api/getMyCustomerDropship', 'AjaxController@getMyCustomerDropship');
    Route::post('api/getCustomerAddress', 'AjaxController@getCustomerAddress');
    Route::post('api/getshipmethod', 'AjaxController@getShipmentMethod');
    Route::post('api/getshipcost', 'AjaxController@getShipmentCost');
    Route::post('/api/getinsurancefee', 'AjaxController@getInsuranceFee');

    Route::post('api/getInvoiceValue', 'AjaxController@getInvoiceValue');
    Route::post('api/getProduct', 'AjaxController@getProduct');
    Route::post('api/getsubcategory', 'AjaxController@getSubcategory');
    Route::post('api/processuser', 'AjaxController@processUser');

    Route::get('api/getaddtolist', 'AjaxController@getAddToList');
    Route::get('api/getaddfromlist', 'AjaxController@getAddFromList');
    Route::post('api/getaddtodata', 'AjaxController@getAddToData');
    Route::post('api/getaddfromdata', 'AjaxController@getAddFromData');
    Route::post('api/addmanualsales', 'ManualSalesController@addManualSales');
    Route::post('api/removemanualsales', 'ManualSalesController@removeManualSales');
    Route::post('api/destroymanualsales', 'ManualSalesController@destroyManualSales');
    Route::post('api/addproductset', 'ProductSetController@addProduct');

    Route::post('api/cancelexpiredorder', 'AjaxController@cancelExpiredOrder');
    Route::post('api/countpoint', 'AjaxController@addPoint');
    Route::post('api/countexpiredpoint', 'AjaxController@countExpiredPoint');





    #########################
    //SEARCH
    #########################
    Route::match(['get', 'post'], 'search/searchmanualsales', 'ManualSalesController@searchOrder');
    Route::match(['get', 'post'], 'search/searchorder', 'OrderController@searchOrder');
    Route::match(['get', 'post'], 'search/searchshopee', 'OrderController@searchShopee');
    Route::match(['get', 'post'], 'search/searchshopee/revise', 'ReviseOrderController@searchShopee');
Route::match(['get', 'post'], 'search/searchreviseorder', 'ReviseOrderController@searchInvoiceNumberNotShip');
    Route::match(['get', 'post'], 'search/searchrefund', 'RefundController@searchRefund');
    Route::match(['get', 'post'], 'search/searchshipment', 'ShipmentInvoiceController@searchShipment');
    Route::match(['get', 'post'], 'search/searchprice', 'PriceController@searchPrice');
    Route::post('search/searchuser', 'UserController@searchUser');
    Route::post('search/searchstockin', 'StockinController@searchStockin');
    Route::match(['get', 'post'], 'search/searchproduct', 'ProductController@searchProduct');
    Route::match(['get', 'post'], 'search/searchproductset', 'ProductSetController@searchProductSet');
    Route::match(['get', 'post'], 'search/searchstockbyproductname', 'StockOpnameController@searchStockByProductName');
    Route::post('search/searchproductimage', 'ProductImageController@searchProductImage');
    Route::post('search/searchwholesale', 'WholesaleController@searchWholesale');
    Route::match(['get', 'post'], 'search/searchstockrevise', 'StockReviseController@searchStockRevise');




    #########################
    //CHAT
    #########################
    Route::post('chatapi/activeconversation', 'ApiChatController@activeConversation');
    Route::post('chatapi/initialconversation', 'ApiChatController@initialConversation');
    Route::post('chatapi/sendchat', 'ApiChatController@sendChat');
    Route::post('chatapi/refreshchat', 'ApiChatController@refreshChat');

    Route::post('chatapi/activeconversationlist', 'ApiChatController@activeConversationList');
    Route::post('chatapi/endconversation', 'ApiChatController@endConversation');
    Route::post('chatapi/retrieveemailandname', 'ApiChatController@retrieveEmailAndName');
    Route::post('chatapi/setsession', 'ApiChatController@setSession');
    Route::post('chatapi/uploadfile', 'ApiChatController@uploadFile');

    Route::post('chatapi/searchchat', 'ApiChatController@searchChat');






    #########################
    //PRIVATE API
    #########################
    Route::match(['post'], 'privapi/allproducts', 'PrivApi\ProductController@allProducts');
    Route::match(['post'], 'privapi/allproductimages', 'PrivApi\ProductImageController@productImages');
    Route::match(['get', 'post'], 'privapi/products', 'PrivApi\ProductController@products');
    Route::match(['get', 'post'], 'privapi/product', 'PrivApi\ProductController@product');
    Route::match(['get', 'post'], 'privapi/getcurrentpriceid', 'PrivApi\ProductController@getCurrentPriceId');

    Route::match(['get', 'post'], 'privapi/productimages', 'PrivApi\ProductimageController@productimages');

    Route::match(['get', 'post'], 'privapi/getbrand', 'PrivApi\BrandController@getBrand');

    Route::match(['get', 'post'], 'privapi/getcategory', 'PrivApi\CategoryController@getCategory');

    Route::match(['get', 'post'], 'privapi/getsubcategory', 'PrivApi\SubcategoryController@getSubcategory');

    Route::match(['get', 'post'], 'privapi/getprice', 'PrivApi\PriceController@getPrice');

});

Route::post('api/getaddtodata', 'AjaxController@getAddToData');
Route::post('api/getstockdesc', 'StockinController@getStockDesc');

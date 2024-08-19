<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Backend\PosController;
use App\Http\Controllers\Backend\SmsController;
use App\Http\Controllers\Backend\TagController;
use App\Http\Controllers\Backend\BankController;
use App\Http\Controllers\Backend\BlogController;
use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\StaffController;
use App\Http\Controllers\Backend\BannerController;
use App\Http\Controllers\Backend\CouponController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\SliderController;
use App\Http\Controllers\Backend\VendorController;
use App\Http\Controllers\Backend\ContactController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Backend\AccountsController;
use App\Http\Controllers\Backend\CampaingController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\ShippingController;
use App\Http\Controllers\Backend\SupplierController;
use App\Http\Controllers\Backend\AttributeController;
use App\Http\Controllers\Backend\SubscriberController;
use App\Http\Controllers\Backend\OrderRefundController;
use App\Http\Controllers\Backend\CashwithdrawController;
use App\Http\Controllers\Backend\OrderPaymentController;
use App\Http\Controllers\Backend\PaymentMethodController;
use App\Http\Controllers\Backend\DeliveryCouponController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/*========================== Start Admin Route  ==========================*/

Route::get('/admin', [AdminController::class, 'Index'])->name('login_form');
Route::post('/admin', [AdminController::class, 'Login'])->name('admin.login');

// Admin All Routes
Route::prefix('admin')->middleware('admin')->group(function () {
	Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
	Route::get('/logout', [AdminController::class, 'AminLogout'])->name('admin.logout');
	Route::get('/register', [AdminController::class, 'AdminRegister'])->name('admin.regester');
	Route::post('/register/store', [AdminController::class, 'AdminRegisterStore'])->name('admin.register.store');
	Route::get('/forgot-password', [AdminController::class, 'AdminForgotPassword'])->name('admin.password.request');
	Route::get('/profile', [AdminController::class, 'Profile'])->name('admin.profile');
	Route::get('/edit/profile', [AdminController::class, 'EditProfile'])->name('edit.profile');
	Route::post('/store/profile', [AdminController::class, 'StoreProfile'])->name('store.profile');
	Route::get('/change/password', [AdminController::class, 'ChangePassword'])->name('change.password');
	Route::post('/update/password', [AdminController::class, 'UpdatePassword'])->name('update.password');

	/* ================ Admin Cache Clear ============== */
	Route::get('/cache-cache', [AdminController::class, 'clearCache'])->name('cache.clear');

	// ==================== Admin Brand All Routes ===================//
	Route::prefix('brand')->group(function () {
		Route::get('/view', [BrandController::class, 'BrandView'])->name('brand.all');
		Route::get('/add', [BrandController::class, 'BrandAdd'])->name('brand.add');
		Route::post('/store', [BrandController::class, 'BrandStore'])->name('brand.store');
		Route::get('/edit/{id}', [BrandController::class, 'BrandEdit'])->name('brand.edit');
		Route::post('/update/{id}', [BrandController::class, 'BrandUpdate'])->name('brand.update');
		Route::get('/delete/{id}', [BrandController::class, 'BrandDelete'])->name('brand.delete');
		Route::get('/brand_active/{id}', [BrandController::class, 'active'])->name('brand.active');
		Route::get('/brand_inactive/{id}', [BrandController::class, 'inactive'])->name('brand.in_active');
	});

	// Admin Category All Routes
	Route::prefix('category')->group(function () {

		Route::get('/index', [CategoryController::class, 'index'])->name('category.index');
		Route::get('/create', [CategoryController::class, 'create'])->name('category.create');
		Route::post('/store', [CategoryController::class, 'store'])->name('categories.store');
		Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
		Route::post('/update/{id}', [CategoryController::class, 'update'])->name('category.update');
		Route::get('/delete/{id}', [CategoryController::class, 'destroy'])->name('category.delete');

		Route::get('/category_active/{id}', [CategoryController::class, 'active'])->name('category.active');
		Route::get('/category_inactive/{id}', [CategoryController::class, 'inactive'])->name('category.in_active');

		Route::get('/category_feature_status_change/{id}', [CategoryController::class, 'changeFeatureStatus'])->name('category.changeFeatureStatus');
	});

	// Admin Brand All Routes
	Route::prefix('supplier')->group(function () {
		Route::get('/view', [SupplierController::class, 'SupplierView'])->name('supplier.all');
		Route::get('/create', [SupplierController::class, 'create'])->name('supplier.create');
		Route::post('/create', [SupplierController::class, 'store'])->name('supplier.store');
		Route::get('/edit/{id}', [SupplierController::class, 'edit'])->name('supplier.edit');
		Route::post('/update/{id}', [SupplierController::class, 'update'])->name('supplier.update');
		Route::get('/delete/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');
		Route::get('/supplier_active/{id}', [SupplierController::class, 'active'])->name('supplier.active');
		Route::get('/supplier_inactive/{id}', [SupplierController::class, 'inactive'])->name('supplier.in_active');
	});

	// Admin Tags All Routes
	// Route::prefix('tag')->group(function () {
	// 	Route::get('/view', [TagController::class, 'TagView'])->name('tag.all');
	// 	Route::get('/add', [TagController::class, 'TagAdd'])->name('tag.add');
	// 	Route::post('/store', [TagController::class, 'TagStore'])->name('tag.store');
	// 	Route::get('/edit/{id}', [TagController::class, 'TagEdit'])->name('tag.edit');
	// 	Route::post('/update/{id}', [TagController::class, 'TagUpdate'])->name('tag.update');
	// 	Route::get('/delete/{id}', [TagController::class, 'TagDelete'])->name('tag.delete');
	// 	Route::get('/tag_active/{id}', [TagController::class, 'active'])->name('tag.active');
	// 	Route::get('/tag_inactive/{id}', [TagController::class, 'inactive'])->name('tag.in_active');
	// });

	// Admin Product All Routes
	Route::prefix('product')->group(function () {
		Route::get('/view', [ProductController::class, 'ProductView'])->name('product.all');
		Route::get('/add', [ProductController::class, 'ProductAdd'])->name('product.add');
		Route::post('/store', [ProductController::class, 'StoreProduct'])->name('product.store');
		Route::get('/edit/{id}', [ProductController::class, 'EditProduct'])->name('product.edit');

		Route::post('/update/{id}', [ProductController::class, 'ProductUpdate'])->name('product.update');

		Route::get('/multiimg/delete/{id}', [ProductController::class, 'MultiImageDelete'])->name('product.multiimg.delete');

		Route::get('/delete/{id}', [ProductController::class, 'ProductDelete'])->name('product.delete');

		Route::get('/product_active/{id}', [ProductController::class, 'active'])->name('product.active');
		Route::get('/product_inactive/{id}', [ProductController::class, 'inactive'])->name('product.in_active');

		Route::get('/product_featured/{id}', [ProductController::class, 'featured'])->name('product.featured');

		// Add Attribute Add
		Route::post('/add-more-choice-option', [ProductController::class, 'add_more_choice_option'])->name('products.add-more-choice-option');

		// ajax product page //
		Route::get('/category/subcategory/ajax/{category_id}', [ProductController::class, 'GetSubProductCategory']);
		Route::get('/subcategory/minicategory/ajax/{subcategory_id}', [ProductController::class, 'GetSubSubCategory']);
	});


	// Admin Slider All Routes
	Route::resource('/slider', SliderController::class);
	Route::get('/slider/delete/{id}', [SliderController::class, 'destroy'])->name('slider.destroy');
	Route::get('/slider_active/{id}', [SliderController::class, 'active'])->name('slider.active');
	Route::get('/slider_inactive/{id}', [SliderController::class, 'inactive'])->name('slider.in_active');

	// Admin Vendor All Routes
	Route::resource('/vendor', VendorController::class);
	Route::get('/vendor/delete/{id}', [VendorController::class, 'destroy'])->name('vendor.delete');
	Route::get('/vendor_active/{id}', [VendorController::class, 'active'])->name('vendor.active');
	Route::get('/vendor_inactive/{id}', [VendorController::class, 'inactive'])->name('vendor.in_active');

	// Admin Customer All Routes
	Route::resource('/customer', UserController::class);
	Route::get('/online/user/list', [UserController::class, 'online_user_list'])->name('online.user.list');
	Route::get('/online/user/all/print', [UserController::class, 'online_user_Print'])->name('online.user.print');
	Route::get('/customer-status/{id}', [UserController::class, 'status'])->name('customer.status');
	Route::post('/customer-update-pass/{id}', [UserController::class, 'update_pass'])->name('customer.update.pass');
	Route::get('/customer/delete/{id}', [UserController::class, 'destroy'])->name('customer.delete');
	Route::get('/customer/all/print', [UserController::class, 'customerPrint'])->name('all.customer.print');
	Route::get('/customer/show/{id}', [UserController::class, 'show'])->name('customer.show');
	Route::get('/onlineUser/details/{id}', [UserController::class, 'onlineUserdetails'])->name('online.User.details');
	Route::get('/customer/order/print/{id}', [UserController::class, 'customerOrderPrint'])->name('customer.order.print');
	Route::get('/customer/payment/print/{id}', [UserController::class, 'customerPaymentPrint'])->name('customer.payment.print');
	Route::post('/customer/import', [UserController::class, 'import'])->name('customer.import');
    Route::post('/admin-update-user-pass/{id}', [UserController::class, 'admin_update_user_pass'])->name('admin.update.user.pass');

    // user-coutomer datatable start
    Route::get('/online/export/user', [UserController::class, 'online_user_export'])->name('online_user.export');
    Route::get('/pos/export/user', [UserController::class, 'pos_user_export'])->name('pos_user.export');
    Route::get('user/search', [UserController::class, 'user_search'])->name('user.search');
    Route::get('user/pagination', [UserController::class, 'user_pagination'])->name('user.pagination');
    // user-coutomer datatable start

	//Admin Campaign All Route
	Route::resource('/campaing', CampaingController::class);
	Route::get('/campaing/delete/{id}', [CampaingController::class, 'destroy'])->name('campaing.delete');
	Route::get('/campaing_active/{id}', [CampaingController::class, 'active'])->name('campaing.active');
	Route::get('/campaing_inactive/{id}', [CampaingController::class, 'inactive'])->name('campaing.in_active');

	Route::post('/flash_deals/product_discount', [CampaingController::class, 'product_discount'])->name('flash_deals.product_discount');
	Route::post('/flash-deals/product-discount-edit', [CampaingController::class, 'product_discount_edit'])->name('flash_deals.product_discount_edit');


	// <--------- Banner route start ------>
	Route::resource('/banner', BannerController::class);
	Route::post('/banner/update/{id}', [BannerController::class, 'update'])->name('banner.update');
	Route::get('/banner/delete/{id}', [BannerController::class, 'destroy'])->name('banner.delete');
	Route::get('/banner_active/{id}', [BannerController::class, 'active'])->name('banner.active');
	Route::get('/banner_inactive/{id}', [BannerController::class, 'inactive'])->name('banner.in_active');

	// <--------- Blog route start ------>
	Route::resource('/blog', BlogController::class);
	Route::post('/blog/update/{id}', [BlogController::class, 'update'])->name('blog.update');
	Route::get('/blog/delete/{id}', [BlogController::class, 'destroy'])->name('blog.delete');
	Route::get('/blog_active/{id}', [BlogController::class, 'active'])->name('blog.active');
	Route::get('/blog_inactive/{id}', [BlogController::class, 'inactive'])->name('blog.in_active');

	// <--------- Page route start ------>
	Route::resource('/page', PageController::class);
	Route::get('/page/delete/{id}', [PageController::class, 'destroy'])->name('page.delete');
	Route::get('/page_active/{id}', [PageController::class, 'active'])->name('page.active');
	Route::get('/page_inactive/{id}', [PageController::class, 'inactive'])->name('page.in_active');

	// Attribute All Route
	Route::resource('/attribute', AttributeController::class);
	Route::get('/attribute/delete/{id}', [AttributeController::class, 'destroy'])->name('attribute.delete');

	// AttributeValue All Route
	Route::post('/attribute/value', [AttributeController::class, 'value_store'])->name('attribute.value_store');
	Route::get('/attribute/value/edit/{id}', [AttributeController::class, 'value_edit'])->name('attribute_value.edit');
	Route::post('/attribute/value/update/{id}', [AttributeController::class, 'value_update'])->name('attribute.val_update');
	Route::get('/attribute_value_active/{id}', [AttributeController::class, 'value_active'])->name('attribute_value.active');
	Route::get('/attribute_value_inactive/{id}', [AttributeController::class, 'value_inactive'])->name('attribute_value.in_active');
	Route::get('/attribute/value/delete/{id}', [AttributeController::class, 'value_destroy'])->name('attribute_value.delete');

	//Unit All Route
	Route::get('/unit', [AttributeController::class, 'index_unit'])->name('unit.index');
	Route::get('/unit/create', [AttributeController::class, 'create_unit'])->name('unit.create');
	Route::post('/unit/store', [AttributeController::class, 'store_unit'])->name('unit.store');
	Route::get('/unit/edit/{id}', [AttributeController::class, 'edit_unit'])->name('unit.edit');
	Route::post('/unit/update/{id}', [AttributeController::class, 'update_unit'])->name('unit.update');
	Route::get('/unit/delete/{id}', [AttributeController::class, 'destroy_unit'])->name('unit.delete');
	Route::get('/unit-status/{id}', [AttributeController::class, 'changeStatus'])->name('unit.changeStatus');


	// Setting All Route
	Route::get('/settings/index', [SettingController::class, 'index'])->name('setting.index');
	Route::post('/settings/update', [SettingController::class, 'update'])->name('update.setting');
	Route::get('/settings/activation', [SettingController::class, 'activation'])->name('setting.activation');
	Route::post('/color/change', [SettingController::class, 'UpdateColor'])->name('update.color');
	Route::get('/website/maintenance', [SettingController::class, 'maintenanceMood'])->name('maintenance');


	// Facebook plugin
	Route::get('/facebook_plugin_setting', [SettingController::class, 'facebook_plugin_setting'])->name('setting.facebook_plugin_setting');
	Route::post('/facebook_plugin_setting', [SettingController::class, 'update'])->name('setting.facebook_plugin_setting');

	// free shipping
	Route::get('free-shipping', [SettingController::class, 'freeShipping'])->name('free.shipping');

	// Shipping Methods Route
	Route::get('/shipping/index', [ShippingController::class, 'index'])->name('shipping.index');
	Route::get('/shipping/create', [ShippingController::class, 'create'])->name('shipping.create');
	Route::post('/shipping/store', [ShippingController::class, 'store'])->name('shipping.store');
	Route::get('/shipping/edit/{id}', [ShippingController::class, 'edit'])->name('shipping.edit');
	Route::post('/shipping/update/{id}', [ShippingController::class, 'update'])->name('shipping.update');
	Route::get('/shipping/delete/{id}', [ShippingController::class, 'destroy'])->name('shipping.delete');
	Route::get('/shipping_active/{id}', [ShippingController::class, 'active'])->name('shipping.active');
	Route::get('/shipping_inactive/{id}', [ShippingController::class, 'inactive'])->name('shipping.in_active');

	Route::get('/attributes/combination', [AttributeController::class, 'combination'])->name('combination.index');

	// Payment Methods Route
	Route::get('/payment-methods/configuration', [PaymentMethodController::class, 'index'])->name('paymentMethod.config');
	Route::post('/payment-methods/update', [PaymentMethodController::class, 'update'])->name('paymentMethod.update');


	Route::prefix('orders')->group(function () {
		// Orders All Route
		Route::get('/all_orders', [OrderController::class, 'index'])->name('all_orders.index');
		Route::get('/all_orders/pos', [OrderController::class, 'indexPos'])->name('all_orders.indexPos');
		Route::get('/online/export/order', [OrderController::class, 'online_order_export'])->name('online_order.export');
		Route::get('/pos/export/order', [OrderController::class, 'pos_order_export'])->name('pos_order.export');
		Route::get('/delivered/order', [OrderController::class, 'delivered_order'])->name('delivered.order');
		Route::get('/deleted/order', [OrderController::class, 'deleted_order'])->name('deleted.order');

		Route::get('/show_hide_active/{id}', [OrderController::class, 'ShowHideactive'])->name('order.show_hide_active');


		Route::get('/all_orders/vendor_sale_index', [OrderController::class, 'vendorSellView'])->name('all_orders.vendor_sale_index');
		Route::get('/all_orders/all_vendor_sale_index', [OrderController::class, 'AllvendorSellView'])->name('all_orders.all_vendor_sale_index');
		Route::get('/all_orders/{id}/show', [OrderController::class, 'show'])->name('all_orders.show');
		Route::get('/all_orders/{id}/ordercancle', [OrderController::class, 'ordercancle'])->name('order.cancle');
		Route::get('/orders_delete/{id}', [OrderController::class, 'destroy'])->name('delete.orders');
		Route::get('/orders/delete/{id}', [OrderController::class, 'order_delete_byStatus'])->name('order.delete.byStatus');
		Route::get('/orders/all/delete', [OrderController::class, 'order_all_delete'])->name('order.all.delete');
		Route::put('/orders_update/{id}', [OrderController::class, 'update'])->name('admin.orders.update');
		Route::get('/invoice/{id}', [OrderController::class, 'invoice_download'])->name('invoice.download');
		Route::get('/print/invoice/{order}', [OrderController::class, 'invoice_print_download'])->name('print.invoice.download');
		//package
		Route::get('/all_packages', [OrderController::class, 'packages_index'])->name('all_packages.index');
		Route::get('/all_packages/status/{id}', [OrderController::class, 'package_status'])->name('packages.status');
		Route::get('/order/product/package', [OrderController::class, 'order_product_packaged'])->name('order.product.packaged');
		Route::get('/order/lock/package', [OrderController::class, 'order_lock_package'])->name('order.lock.package');
		Route::get('/order/product/csv', [OrderController::class, 'order_product_csv'])->name('order.product.csv');
		Route::get('/order/product/Print', [OrderController::class, 'order_product_Print'])->name('order.product.Print');
		Route::get('/multiple/orderprint/page', [OrderController::class, 'multiple_order_print_page'])->name('multiple.orderprint.page');
		//order Change
		Route::get('/delete/orderproduct/{id}', [OrderController::class, 'delete_order_product'])->name('delete.order.product');
		Route::get('/order/quantity/update', [OrderController::class, 'order_quantity_update'])->name('order.quantity.update');
		Route::Post('/order/itemAdd', [OrderController::class, 'order_itemAdd'])->name('order.itemAdd');
		Route::get('/order/newProduct/quantity/update', [OrderController::class, 'order_newProduct_quantity_update'])->name('order.newProduct.quantity.update');
		Route::get('order/gift/status', [OrderController::class, 'order_gift_status'])->name('order.gift_status');

		//all datatable work
		Route::get('order/pro_search', [OrderController::class, 'pro_search'])->name('order.pro_search');
		Route::get('order/deliver-export', [OrderController::class, 'deliver_export'])->name('order.deliver.export');
		Route::get('order/trash-export', [OrderController::class, 'orderTrash_export'])->name('order.trash.export');
		Route::get('order/pagination', [OrderController::class, 'order_pagination'])->name('order.pagination');
		
		 //pos order dataTable
        Route::get('Posorder/pro_search', [OrderController::class, 'Posorder_pro_search'])->name('Posorder.pro_search');
        Route::get('Posorder/pagination', [OrderController::class, 'Posorder_pagination'])->name('Posorder.pagination');
        //Online order dataTable
        Route::get('Onlineorder/pro_search', [OrderController::class, 'Onlineorder_pro_search'])->name('Onlineorder.pro_search');
        Route::get('Onlineorder/pagination', [OrderController::class, 'Onlineorder_pagination'])->name('Onlineorder.pagination');
        //Package order dataTable
        Route::get('Packageorder/pro_search', [OrderController::class, 'Packageorder_pro_search'])->name('Packageorder.pro_search');
        Route::get('Packageorder/pagination', [OrderController::class, 'Packageorder_pagination'])->name('Packageorder.pagination');
        //Deliver order dataTable
        Route::get('Deliverorder/pro_search', [OrderController::class, 'Deliverorder_pro_search'])->name('Deliverorder.pro_search');
        Route::get('Deliverorder/pagination', [OrderController::class, 'Deliverorder_pagination'])->name('Deliverorder.pagination');
        //Delete order dataTable
        Route::get('Deleteorder/pro_search', [OrderController::class, 'Deleteorder_pro_search'])->name('Deleteorder.pro_search');
        Route::get('Deleteorder/pagination', [OrderController::class, 'Deleteorder_pagination'])->name('Deleteorder.pagination');
        //allVendor order dataTable
        Route::get('allVendororder/pro_search', [OrderController::class, 'allVendororder_pro_search'])->name('allVendororder.pro_search');
        Route::get('allVendororder/pagination', [OrderController::class, 'allVendororder_pagination'])->name('allVendororder.pagination');

	});

	// payment status
	Route::post('/orders/update_payment_status', [OrderController::class, 'update_payment_status'])->name('orders.update_payment_status');
	// delivery status
	Route::post('/orders/update_delivery_status', [OrderController::class, 'update_delivery_status'])->name('orders.update_delivery_status');

	// Report All Route
	Route::get('/stock_report', [ReportController::class, 'index'])->name('stock_report.index');
	Route::get('/revenue_report', [ReportController::class, 'revenueIndex'])->name('revenue_report.index');
	Route::get('/report/revenue-date-filter', [ReportController::class, 'revenueDateFilter'])->name('report.revenue.dateFilter');


	/*================  Admin Address Updated  ==================*/
	Route::post('/address/update/{id}', [OrderController::class, 'admin_address_update'])->name('admin.address.update');
	/*================  Admin User Updated  ==================*/
	Route::post('/user/update/{user_id}', [OrderController::class, 'admin_user_update'])->name('admin.user.update');
	/*================  Ajax  ==================*/
	Route::get('/division-district/ajax/{division_id}', [OrderController::class, 'getdivision'])->name('division.ajax');
	Route::get('/district-upazilla/ajax/{district_id}', [OrderController::class, 'getupazilla'])->name('upazilla.ajax');
	/*================  Ajax  ==================*/

	/*================  Ajax Category Store ==================*/
	Route::post('/category/insert', [ProductController::class, 'categoryInsert'])->name('category.ajax.store');
	/*================  Ajax Brand Store ==================*/
	Route::post('/brand/insert', [ProductController::class, 'brandInsert'])->name('brand.ajax.store');

	// <--------- Coupon route start ------>
	Route::resource('/coupons', CouponController::class);
	Route::post('/coupon/update/{id}', [CouponController::class, 'update'])->name('coupons.update');
	Route::get('/coupon/delete/{id}', [CouponController::class, 'destroy'])->name('coupon.delete');
	Route::get('/coupon_active/{id}', [CouponController::class, 'active'])->name('coupon.active');
	Route::get('/coupon_inactive/{id}', [CouponController::class, 'inactive'])->name('coupon.in_active');

	// <--------- Delivery Coupon route start ------>
    Route::get('/deliveryCoupon/index', [DeliveryCouponController::class, 'index'])->name('deliveryCoupon.index');
	Route::get('/deliveryCoupon/create', [DeliveryCouponController::class, 'create'])->name('deliveryCoupon.create');
	Route::post('/deliveryCoupon/store', [DeliveryCouponController::class, 'store'])->name('deliveryCoupon.store');
    Route::get('/deliveryCoupon/edit/{id}', [DeliveryCouponController::class, 'edit'])->name('deliveryCoupon.edit');
	Route::post('/deliveryCoupon/update/{id}', [DeliveryCouponController::class, 'update'])->name('deliveryCoupon.update');
	Route::get('/deliveryCoupon/delete/{id}', [DeliveryCouponController::class, 'destroy'])->name('deliveryCoupon.delete');
	Route::get('/deliveryCoupon_active/{id}', [DeliveryCouponController::class, 'active'])->name('deliveryCoupon.active');
	Route::get('/deliveryCoupon_inactive/{id}', [DeliveryCouponController::class, 'inactive'])->name('deliveryCoupon.in_active');



	// sms-templates //
	Route::get('/sms-templates', [SmsController::class, 'template_index'])->name('sms.templates');
	Route::post('/sms-templates/store', [SmsController::class, 'store'])->name('sms.templates.store');
	Route::post('/sms-templates/update/{template_id}', [SmsController::class, 'template_update'])->name('sms.templates.update');

	// sms-providers //
	Route::get('/sms-providers', [SmsController::class, 'provider_index'])->name('sms.providers');
	Route::post('/sms-providers/store', [SmsController::class, 'providersStore'])->name('sms.providers.store');
	Route::post('/sms-providers/update/{provider_id}', [SmsController::class, 'provider_update'])->name('sms.providers.update');

	// role premissions //
	Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
	Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
	Route::post('/roles/store', [RoleController::class, 'store'])->name('roles.store');
	Route::get('/roles/edit/{id}', [RoleController::class, 'edit'])->name('roles.edit');
	Route::post('/roles/update/{id}', [RoleController::class, 'update'])->name('roles.update');
	Route::get('/roles/delete/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');

	// role premissions staffs //
	Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
	Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
	Route::post('/staff/store', [StaffController::class, 'store'])->name('staff.store');
	Route::get('/staff/edit/{id}', [StaffController::class, 'edit'])->name('staff.edit');
	Route::post('/staff/update/{id}', [StaffController::class, 'update'])->name('staff.update');
	Route::get('/staff/delete/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');

	//Subscribers
	Route::get('/subscribers', [SubscriberController::class, 'index'])->name('subscribers.index');
	Route::get('/subscribers/destroy/{id}', [SubscriberController::class, 'destroy'])->name('subscribers.destroy');

	//Reating & Review
	Route::get('/review', [ReviewController::class, 'index'])->name('review.index');
	Route::get('/review/destroy/{id}', [ReviewController::class, 'destroy'])->name('review.destroy');
	Route::get('/review_active/{id}', [ReviewController::class, 'active'])->name('review.active');
	Route::get('/review_inactive/{id}', [ReviewController::class, 'inactive'])->name('review.in_active');

	//Contact
	Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
	Route::get('/contact/destroy/{id}', [ContactController::class, 'destroy'])->name('contact.destroy');

	// Admin Accounting All Routes
	Route::prefix('accounts')->group(function () {
		Route::get('/account-heads', [AccountsController::class, 'heads'])->name('accounts.heads');
		Route::get('/account-heads/create', [AccountsController::class, 'create_head'])->name('accounts.heads.create');
		Route::post('/account-heads/store', [AccountsController::class, 'store_head'])->name('accounts.heads.store');
		Route::get('/account-heads/change-status/{id}', [AccountsController::class, 'change_status_head'])->name('accounts.heads.change_status');
		Route::get('/account-heads/delete/{id}', [AccountsController::class, 'head_destroy'])->name('accounts.heads.delete');
		Route::get('/account-ledgers', [AccountsController::class, 'ledgers'])->name('accounts.ledgers');
		Route::get('/account-ledgers/create', [AccountsController::class, 'create_ledger'])->name('accounts.ledgers.create');
		Route::post('/account-ledgers/store', [AccountsController::class, 'store_ledger'])->name('accounts.ledgers.store');
		Route::get('/account-ledgers/delete/{id}', [AccountsController::class, 'ledger_destroy'])->name('accounts.ledgers.delete');
		Route::post('/account-ledgers/report', [AccountsController::class, 'ledger_report'])->name('accounts.leagers.report');
		Route::get('/account-ledgers/report/print', [AccountsController::class, 'ledger_report_print'])->name('accounts.ledgers.report_print');
	});

	Route::post('/pos/customer/insert', [PosController::class, 'customerInsert'])->name('customer.ajax.store.pos');
	Route::post('/pos/customer/update', [PosController::class, 'customerupdate'])->name('customer.ajax.update.pos');
	Route::get('/pos/get/customer/data', [PosController::class, 'getcustomerdata'])->name('get.customer.data.pos');
	Route::get('/pos/get/update/customer/data', [PosController::class, 'get_update_customerdata'])->name('get.update.customer.data.pos');

	//Admin POS All Routes
	Route::prefix('pos')->group(function () {
		Route::get('/', [PosController::class, 'index'])->name('pos.index');
		Route::get('add-to-cart/product', [PosController::class, 'add_pos_product'])->name('add.pos.product');
		Route::get('get-pos-cart/product', [PosController::class, 'getPosCartData'])->name('get.pos.CartData');
		Route::get('/products/add-to-cart', [PosController::class, 'filter'])->name('pos.filter');
		Route::get('/pos/delete/{id}', [PosController::class, 'posdelete'])->name('pos.delete.item');
		Route::get('/pos/cart/update', [PosController::class, 'updatePosCart'])->name('pos.cart.update');
		Route::get('/get-products', [PosController::class, 'filter'])->name('pos.filter.ajax');
		Route::get('/product-search/show', [PosController::class, 'searchProductshow'])->name('product.search.show');
		Route::POST('/store', [PosController::class, 'store'])->name('pos.order.store');
		Route::POST('/store/withPrint', [PosController::class, 'store_withPrint'])->name('pos.order.store.withPrint');
		Route::POST('/pos/order/cancle', [PosController::class, 'pos_orderCencel'])->name('pos.order.cancle');
		Route::post('get/advanced/amount', [PosController::class, 'get_advanced_amount'])->name('get.advanced.amount');
		Route::get('get/user/address/pos', [PosController::class, 'get_user_address_pos'])->name('get.user.address.pos');
		Route::get('/advancedPayment/search/transection', [PosController::class, 'advancedPayment_searchtransection'])->name('advancedPayment.searchtransection');
		Route::get('/pos/transection/amount/ajax/{transaction_no}', [PosController::class, 'get_transection_amount'])->name('transection.amount.ajax');
		Route::get('product/filter/{name}', [PosController::class, 'pos_filter'])->name('pos.filter.show');
		Route::get('gift/status', [PosController::class, 'pos_gift_status'])->name('pos.gift_status');
	});

	//POS Route
	// Route::name('pos.')->prefix('pos')->group(function(){
	//    Route::get('/','PosController@index')->name('index');
	//
	//    Route::get('cart', 'PosController@shoppingCart')->name('cart');
	//    Route::post('order/store', 'PosController@orderStore')->name('order.store');
	//    Route::post('order/cencel', 'PosController@orderCencel')->name('order.cencel');
	//    Route::get('product-search','PosController@searchProduct')->name('product.search');
	//    Route::get('get-cart-count','PosController@getCartCount')->name('get.cart.count');
	//    Route::get('get-cart-data','PosController@getCartData')->name('get.cart.data');
	//    Route::post('cart-delete/{id}','PosController@deleteProduct')->name('remove.cart.item');
	//    Route::get('update-cart','PosController@updateCart')->name('update.cart');
	//});



	//cash withdraw
	Route::post('/cash/withdraw', [CashwithdrawController::class, 'store'])->name('cash.withdraw');
	Route::get('/cash-withdraw', [CashwithdrawController::class, 'index'])->name('cash-withdraw.index');
	Route::get('/Bkash', [CashwithdrawController::class, 'Bkash'])->name('bkash');
	Route::get('/Nagad', [CashwithdrawController::class, 'Nagad'])->name('nagad');
	Route::get('/Bank', [CashwithdrawController::class, 'Bank'])->name('bank');
	Route::get('/cash', [CashwithdrawController::class, 'Cash'])->name('cash');
	Route::get('/withdraw/status/{id}', [CashwithdrawController::class, 'withdrawStatus'])->name('withdraw.status');
	Route::get('/withdraw-history', [CashwithdrawController::class, 'withdraw_history'])->name('withdraw.history');

	// Payment All Route
	Route::prefix('payment')->group(function () {
		Route::resource('/', OrderPaymentController::class);
		Route::get('/payment/delete/{id}', [OrderPaymentController::class, 'destroy'])->name('payment.delete');
		Route::get('/customer-payment/ajax/{invoice_no}', [OrderPaymentController::class, 'getCustomerPayment'])->name('customerPayment.ajax');
		Route::get('/customer-invoice/ajax/{invoice_no}', [OrderPaymentController::class, 'getCustomerInvoice'])->name('customerInvoice.ajax');
		Route::get('/customer_number/ajax/{user_id}', [OrderPaymentController::class, 'getCustomerPhone'])->name('customerPhone.ajax');
	});

	Route::get('/payment', [OrderPaymentController::class, 'index'])->name('payment.index');
	Route::get('/payment/create', [OrderPaymentController::class, 'create'])->name('payment.create');
	Route::post('/payment/store', [OrderPaymentController::class, 'store'])->name('payment.store');
	Route::get('/payment/edit/{id}', [OrderPaymentController::class, 'edit'])->name('payment.edit');
	Route::post('/payment/update/{id}', [OrderPaymentController::class, 'update'])->name('payment.update');
	Route::get('/payment/delete/{id}', [OrderPaymentController::class, 'destroy'])->name('payment.destroy');
	//Advanced Payment
	Route::get('/advanced/payment', [OrderPaymentController::class, 'advanced_create'])->name('advanced.create');
	Route::post('/advanced/store', [OrderPaymentController::class, 'advanced_store'])->name('advanced.store');
	Route::get('/advanced/edit/{id}', [OrderPaymentController::class, 'advanced_edit'])->name('advanced.edit');
	Route::post('/advanced/update/{id}', [OrderPaymentController::class, 'advanced_update'])->name('advanced.update');
	Route::get('/advanced/delete/{id}', [OrderPaymentController::class, 'advanced_destroy'])->name('advanced.destroy');
	Route::get('/advancedPayment/search/customer', [OrderPaymentController::class, 'advancedPayment_searchCustomer'])->name('advancedPayment.searchCustomer');
	//Advance payment update
	Route::get('/advanced/index', [OrderPaymentController::class, 'advanced_index'])->name('advanced.index');
	Route::get('/advanced/ledger', [OrderPaymentController::class, 'advanced_ledger'])->name('advanced.ledger');
	Route::get('/old/advance/payment/check', [OrderPaymentController::class, 'old_advance_payment_check'])->name('old.advance.payment.check');
	Route::post('/advanced/payment/store', [OrderPaymentController::class, 'advanced_payment_store'])->name('advanced.payment.store');
	Route::get('/advanced/payment/edit{id}', [OrderPaymentController::class, 'advanced_payment_edit'])->name('advanced.payment.edit');
	Route::put('/advanced/payment/update/{id}', [OrderPaymentController::class, 'advanced_payment_updated'])->name('advanced.payment.update');
	Route::get('/advanced/payment/delete/{id}', [OrderPaymentController::class, 'advanced_payment_destroy'])->name('advanced.payment.destroy');
    Route::get('/advanced/payment/refund/{id}', [OrderPaymentController::class, 'advanced_payment_refund'])->name('advanced.payment.refund');

    Route::get('advance_ledger/ledger_search', [OrderPaymentController::class, 'advance_ledger_search'])->name('advance.ledger.search');
    Route::get('advance_ledger/deliver-export', [OrderPaymentController::class,'advance_ledger_export'])->name('advance.ledger.export');
    Route::get('advance_ledger/pagination', [OrderPaymentController::class, 'advance_ledger_pagination'])->name('advance.ledger.pagination');

	//Advance Refund
	Route::get('/refund', [OrderRefundController::class, 'index'])->name('refund.index');
	Route::get('/refund/create', [OrderRefundController::class, 'create'])->name('refund.create');
	Route::post('/refund/store', [OrderRefundController::class, 'store'])->name('refund.store');
	Route::get('/refund/edit/{id}', [OrderRefundController::class, 'edit'])->name('refund.edit');
	Route::post('/refund/update/{id}', [OrderRefundController::class, 'update'])->name('refund.update');
	Route::get('/refund/delete/{id}', [OrderRefundController::class, 'destroy'])->name('refund.destroy');

	//Bank Amount List
	Route::get('/bank-ledgers-list', [BankController::class, 'index'])->name('bank.ledgers.list');
	Route::get('/bank-ledgers-create', [BankController::class, 'create'])->name('bank.ledgers.create');
	Route::post('/bank-ledgers/store', [BankController::class, 'store'])->name('bank.ledgers.store');
	Route::get('/bank-ledgers/edit/{id}', [BankController::class, 'edit'])->name('bank.ledgers.edit');
	Route::post('/bank-ledgers/update/{id}', [BankController::class, 'update'])->name('bank.ledgers.update');
	Route::get('/bank-ledgers/delete/{id}', [BankController::class, 'destroy'])->name('bank.ledgers.destroy');
});

/*========================== End Admin Route  ==========================*/

require __DIR__ . '/auth.php';
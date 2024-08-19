@php
  $prefix = Request::route()->getPrefix();
  $route = Route::current()->getName();
@endphp
<aside class="navbar-aside bg-primary" id="offcanvas_aside">
    <div class="aside-top">
        <a href="{{ route('admin.dashboard') }}" class="brand-wrap">
            @php
                $logo = get_setting('site_footer_logo');
            @endphp
            @if($logo != null)
                <img src="{{ asset(get_setting('site_footer_logo')->value ?? ' ') }}" alt="{{ env('APP_NAME') }}"
                 style="height: 30px !important; width: 100px !important; min-width: 100px !important;
                 ">
            @else
                <img src="{{ asset('upload/no_image.jpg') }}" alt="{{ env('APP_NAME') }}" style="height: 30px !important; width: 80px !important; min-width: 80px !important;">
            @endif
        </a>
        <div>
            <button class="btn btn-icon btn-aside-minimize"><i class="text-white material-icons md-menu_open"></i></button>
        </div>
    </div>
    <nav>
        <ul class="menu-aside">
            <li class="menu-item {{ ($route == 'admin.dashboard')? 'active':'' }}">
                <a class="menu-link" href="{{ route('admin.dashboard') }}">
                   <i class="fa-solid fa-house fontawesome_icon_custom"></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>

            <li class="menu-item has-submenu
                {{ ($route == 'slider.index')? 'active':'' }}
                {{ ($route == 'slider.edit')? 'active':'' }}
                {{ ($route == 'slider.create')? 'active':'' }}
            ">
                @if(Auth::guard('admin')->user()->role == '1' || in_array('37', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                    <a class="menu-link" href="#">
                        <i class="fa-solid fa-photo-film fontawesome_icon_custom"></i>
                        <span class="text">Sliders</span>
                    </a>
                @endif
                <div class="submenu">
                    @if(Auth::guard('admin')->user()->role == '1' || in_array('37', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                        <a class="{{ ($route == 'slider.index') ? 'active':'' }}" href="{{ route('slider.index') }}">Slider List</a>
                    @endif
                    @if(Auth::guard('admin')->user()->role == '1' || in_array('38', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                        <a class="{{ ($route == 'slider.create') ? 'active':'' }}" href="{{ route('slider.create') }}">Slider Add</a>
                    @endif
                </div>
            </li>
            @if(Auth::guard('admin')->user()->role == '1')
            <li class="menu-item has-submenu
                {{ ($route == 'banner.index')? 'active':'' }}
                {{ ($route == 'banner.edit')? 'active':'' }}
                {{ ($route == 'banner.create')? 'active':'' }}
            ">
                <a class="menu-link" href="#">
                    <i class="icon material-icons md-pie_chart"></i>
                    <span class="text">Banner</span>
                </a>
                <div class="submenu">
                    <a class="{{ ($route == 'banner.index') ? 'active':'' }}" href="{{ route('banner.index') }}">Banner List</a>
                    <a class="{{ ($route == 'banner.create') ? 'active':'' }}" href="{{ route('banner.create') }}">Banner Add</a>
                </div>
            </li>
            @endif

            <li class="menu-item has-submenu
                {{ ($prefix == 'admin/product') || ($prefix == 'admin/category')|| ($prefix == 'admin/unit') || ($route == 'attribute.index') ||($route == 'unit.index') || ($prefix == 'admin/brand') ? 'active' : '' }}
            ">
                @if(Auth::guard('admin')->user()->role == '1' || in_array('2', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                    <a class="menu-link" href="#">
                        <i class="fa-solid fa-bag-shopping fontawesome_icon_custom"></i>
                        <span class="text">Products</span>
                    </a>
                @endif
                <div class="submenu">
                    @if(Auth::guard('admin')->user()->role == '1' || in_array('1', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                        <a class="{{ ($route == 'product.add') ? 'active':'' }}" href="{{ route('product.add') }}">Product Add</a>
                    @endif
                    @if(Auth::guard('admin')->user()->role == '1' || in_array('2', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                        <a class="{{ ($route == 'product.all') ? 'active':'' }}" href="{{ route('product.all') }}">Products</a>
                    @endif
                    @if(Auth::guard('admin')->user()->role == '1' || in_array('5', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                        <a class="{{ ($prefix == 'admin/category') ? 'active':'' }}" href="{{ route('category.index') }}">Categories</a>
                    @endif
                    @if(Auth::guard('admin')->user()->role == '1' || in_array('13', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                        <a class="{{ ($route == 'attribute.index') ? 'active':'' }}" href="{{ route('attribute.index') }}">Attributes</a>
                    @endif
                    @if(Auth::guard('admin')->user()->role == '1' || in_array('53', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                        <a class="{{ ($route == 'unit.index') ? 'active':'' }}" href="{{ route('unit.index') }}">Unit</a>
                    @endif
                    @if(Auth::guard('admin')->user()->role == '1' || in_array('9', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                        <a class="{{ ($prefix == 'admin/brand') ? 'active':'' }}" href="{{ route('brand.all') }}">Brands</a>
                    @endif
                </div>
            </li>
            <li class="menu-item has-submenu
                {{ ($route == 'campaing.index')? 'active':'' }}
                {{ ($route == 'campaing.create')? 'active':'' }}
                {{ ($route == 'campaing.edit')? 'active':'' }}
            ">
                @if(Auth::guard('admin')->user()->role == '1' || in_array('41', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                    <a class="menu-link" href="#">
                       <i class="fa-solid fa-photo-film fontawesome_icon_custom"></i>
                        <span class="text">Campaigns</span>
                    </a>
                @endif
                <div class="submenu">
                    @if(Auth::guard('admin')->user()->role == '1' || in_array('41', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                        <a class="{{ ($route == 'campaing.index') ? 'active':'' }}" href="{{ route('campaing.index') }}">Campaign List</a>
                    @endif
                    @if(Auth::guard('admin')->user()->role == '1' || in_array('42', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                        <a class="{{ ($route == 'campaing.create') ? 'active':'' }}" href="{{ route('campaing.create') }}">Campaign Add</a>
                    @endif
                </div>
            </li>
            @if(Auth::guard('admin')->user()->role == '1')
            <li class="menu-item has-submenu  {{ ($route == 'coupons.index')? 'active':'' }} {{ ($route == 'coupons.create')? 'active':'' }}
                {{ ($route == 'coupons.edit')? 'active':'' }}  ">
                <a class="menu-link" href="#">
                    <i class="icon material-icons md-monetization_on"></i>
                    <span class="text">Coupons</span>
                </a>
                <div class="submenu">
                    <a class="{{ ($route == 'coupons.index') ? 'active':'' }}" href="{{ route('coupons.index') }}">Coupon List</a>
                    <a class="{{ ($route == 'coupons.create') ? 'active':'' }}" href="{{ route('coupons.create') }}">Coupon Add</a>
                </div>
            </li>
            <li class="menu-item has-submenu  {{ ($route == 'deliveryCoupon.index')? 'active':'' }} {{ ($route == 'deliveryCoupon.create')? 'active':'' }}
                {{ ($route == 'deliveryCoupon.edit')? 'active':'' }}  ">
                <a class="menu-link" href="#">
                    <i class="icon material-icons md-monetization_on"></i>
                    <span class="text">Delivery Coupon</span>
                </a>
                <div class="submenu">
                    <a class="{{ ($route == 'deliveryCoupon.index') ? 'active':'' }}" href="{{ route('deliveryCoupon.index') }}">Delivery Coupon List</a>
                    <a class="{{ ($route == 'deliveryCoupon.create') ? 'active':'' }}" href="{{ route('deliveryCoupon.create') }}">Delivery Coupon Add</a>
                </div>
            </li>
            @endif
            <li class="menu-item has-submenu {{ ($route == 'all_orders.index') || ($route == 'all_orders.indexPos') ||($route == 'all_orders.all_vendor_sale_index')|| ($route == 'delivered.order') || ($route == 'deleted.order') || ($route == 'all_packages.index') ?'active':'' }}">
                @if(Auth::guard('admin')->user()->role == '1' || in_array('17', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                    <a class="menu-link" href="#">
                        <i class="icon material-icons md-shopping_cart"></i>
                        <span class="text">Sales</span>
                    </a>
                @endif
                <div class="submenu">
                    @if(Auth::guard('admin')->user()->role == '1' || in_array('17', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                          <a class="{{ ($route == 'all_orders.index') ? 'active':'' }}" href="{{ route('all_orders.index') }}" >Online All Orders</a>
                          <a class="{{ ($route == 'all_orders.indexPos') ? 'active':'' }}" href="{{ route('all_orders.indexPos') }}" >Pos All Orders</a>
                          <a class="{{ ($route == 'all_orders.all_vendor_sale_index') ? 'active':'' }}" href="{{ route('all_orders.all_vendor_sale_index') }}" >All Vendor Orders</a>
                          <a class="{{ ($route == 'all_packages.index') ? 'active':'' }}" href="{{ route('all_packages.index') }}" >All Order Package</a>
                          <a class="{{ ($route == 'delivered.order') ? 'active':'' }}" href="{{ route('delivered.order') }}" >All Delivered Order</a>
                          <a class="{{ ($route == 'deleted.order') ? 'active':'' }}" href="{{ route('deleted.order') }}" >Trash Bin</a>
                    @endif
                </div>
            </li>
            @if(Auth::guard('admin')->user()->role == '1')
            <li class="menu-item has-submenu {{ ($route == 'stock_report.index') || ($route == 'revenue_report.index') ? 'active':'' }} ">
                <a class="menu-link" href="#">
                   <i class="icon material-icons md-pie_chart"></i>
                    <span class="text">Report</span>
                </a>
                <div class="submenu">
                    <a class="{{ ($route == 'stock_report.index') ? 'active':'' }}" href="{{ route('stock_report.index') }}">Product Stock</a>
                    <a class="{{ ($route == 'revenue_report.index') ? 'active':'' }}" href="{{ route('revenue_report.index') }}">Show Monthly Revenue</a>
                </div>
            </li>
            @endif

           <li class="menu-item has-submenu {{ ($route == 'payment.index') || ($route == 'payment.create') ||($route == 'advanced.index') || ($route == 'payment.edit') || ($route == 'refund.index') || ($route == 'refund.create') || ($route == 'bank.ledgers.create') || ($route == 'bank.ledgers.list') || ($route == 'advanced.ledger') ?'active':''  }}">
                @if(Auth::guard('admin')->user()->role == '1' || in_array('66', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                    <a class="menu-link" href="#">
                        <i class="icon material-icons md-stars"></i>
                        <span class="text">Payments</span>
                    </a>
                @endif
                <div class="submenu">
                        <a class="{{ ($route == 'payment.index') ? 'active':'' }}" href="{{ route('payment.index') }}">Payment List</a>
                        <a class="{{ ($route == 'payment.create') ? 'active':'' }}" href="{{ route('payment.create') }}">Payment Add</a>
                        <a class="{{ ($route == 'advanced.index') ? 'active':'' }}" href="{{ route('advanced.index') }}">Advanced Payment</a>
                        <a class="{{ ($route == 'advanced.ledger') ? 'active':'' }}" href="{{ route('advanced.ledger') }}">Advanced Payment Ledger</a>
                        <a class="{{ ($route == 'refund.index') ? 'active':'' }}" href="{{ route('refund.index') }}">Refund List</a>
                        <a class="{{ ($route == 'refund.create') ? 'active':'' }}" href="{{ route('refund.create') }}">Refund Add</a>
                        <a class="{{ ($route == 'bank.ledgers.create')? 'active':'' }}" href="{{ route('bank.ledgers.create') }}">Bank Ledger Add</a>
                        <a class="{{ ($route == 'bank.ledgers.list')? 'active':'' }}" href="{{ route('bank.ledgers.list') }}">Bank Ledger List</a>
                </div>
            </li>
            @if(Auth::guard('admin')->user()->role == '1')
            <li class="menu-item has-submenu
            {{ ($route == 'accounts.heads')? 'active':'' }}
            {{ ($route == 'accounts.ledgers')? 'active':'' }}
            {{ ($route == 'accounts.heads.create')? 'active':'' }}
            ">
                <a class="menu-link" href="#">
                <i class="fontawesome_icon_custom fa-solid fa-calculator"></i>
                    <span class="text">Accounts</span>
                </a>
                <div class="submenu">
                    <a class="{{ ($route == 'accounts.heads')? 'active':'' }} {{ ($route == 'accounts.heads.create')? 'active':'' }}" href="{{ route('accounts.heads') }}">Account Heads</a>
                    <a class="{{ ($route == 'accounts.ledgers')? 'active':'' }}" href="{{ route('accounts.ledgers') }}">Cashbook</a>
                </div>
            </li>
           @endif

           @if(Auth::guard('admin')->user()->role == '1')
           <li class="menu-item has-submenu
           {{ ($route == 'bkash')? 'active':'' }}
           {{ ($route == 'nagad')? 'active':'' }}
           {{ ($route == 'bank')? 'active':'' }}
           {{ ($route == 'cash')? 'active':'' }}
           ">
               <a class="menu-link" href="#">
                   <i class="icon material-icons md-monetization_on"></i>
                   <span class="text">Withdraw List</span>
               </a>
               <div class="submenu">
                   <a class="{{ ($route == 'bkash')? 'active':'' }}" href="{{ route('bkash') }}">BKash</a>
                   <a class="{{ ($route == 'nagad')? 'active':'' }}" href="{{ route('nagad') }}">Nagad</a>
                   <a class="{{ ($route == 'bank')? 'active':'' }}" href="{{ route('bank') }}">Bank</a>
                   <a class="{{ ($route == 'cash')? 'active':'' }}" href="{{ route('cash') }}">Cash</a>
               </div>
           </li>
           @endif
            @if(Auth::guard('admin')->user()->role == '1')
                @if(get_setting('multi_vendor')->value)
                    <li class="menu-item has-submenu
                        {{ ($route == 'vendor.index')? 'active':'' }}
                        {{ ($route == 'vendor.edit')? 'active':'' }}
                        {{ ($route == 'vendor.create')? 'active':'' }}
                    ">
                        <a class="menu-link" href="#">
                            <i class="icon material-icons md-person_add"></i>
                            <span class="text">Vendors</span>
                        </a>
                        <div class="submenu">
                            <a class="{{ ($route == 'vendor.index') ? 'active':'' }}" href="{{ route('vendor.index') }}">Vendor List</a>
                            <a class="{{ ($route == 'vendor.create') ? 'active':'' }}" href="{{ route('vendor.create') }}">Vendor Add</a>
                        </div>
                    </li>
                @endif
            @endif


            <li class="menu-item has-submenu {{ ($prefix == 'admin/supplier')?'active':'' }}">
                @if(Auth::guard('admin')->user()->role == '1' || in_array('45', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                    <a class="menu-link" href="#">
                        <i class="icon material-icons md-stars"></i>
                        <span class="text">Suppliers</span>
                    </a>
                @endif
                <div class="submenu">
                    @if(Auth::guard('admin')->user()->role == '1' || in_array('45', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                        <a class="{{ ($route == 'supplier.all') ? 'active':'' }}" href="{{ route('supplier.all') }}">Supplier List</a>
                    @endif
                    @if(Auth::guard('admin')->user()->role == '1' || in_array('46', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                        <a class="{{ ($route == 'supplier.create') ? 'active':'' }}" href="{{ route('supplier.create') }}">Supplier Add</a>
                    @endif
                </div>
            </li>

            <li class="menu-item has-submenu
                {{ ($route == 'staff.index')? 'active':'' }}
                {{ ($route == 'staff.create')? 'active':'' }}
                {{ ($route == 'staff.edit')? 'active':'' }}
                {{ ($route == 'roles.index')? 'active':'' }}
                {{ ($route == 'roles.create')? 'active':'' }}
                {{ ($route == 'roles.edit')? 'active':'' }}
            ">
                @if(Auth::guard('admin')->user()->role == '1' || in_array('25', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                    <a class="menu-link" href="#">
                       <i class="icon material-icons md-pie_chart"></i>
                        <span class="text">Staff</span>
                    </a>
                @endif
                <div class="submenu">
                    @if(Auth::guard('admin')->user()->role == '1' || in_array('25', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                        <a class="{{ ($route == 'staff.index') ? 'active':'' }}" href="{{ route('staff.index') }}">All Staff</a>
                    @endif
                    @if(Auth::guard('admin')->user()->role == '1' || in_array('29', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                        <a class="{{ ($route == 'roles.index') ? 'active':'' }}" href="{{ route('roles.index') }}">Staff Premissions</a>
                    @endif
                </div>
            </li>

            @if(Auth::guard('admin')->user()->role == '1' || in_array('65', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                <li class="menu-item has-submenu
                {{ ($route == 'customer.index')? 'active':'' }}
                {{ ($route == 'customer.create')? 'active':'' }}
                ">
                    <a class="menu-link" href="#">
                        <i class="icon material-icons md-person"></i>
                        <span class="text">Customers</span>
                    </a>
                    <div class="submenu">
                        <a class="{{ ($route == 'customer.create')? 'active':'' }}" href="{{ route('customer.create') }}">Customer Add</a>
                        <a class="{{ ($route == 'customer.index')? 'active':'' }}" href="{{ route('customer.index') }}">Customer List</a>
                    </div>
                </li>
            @endif
            @if(Auth::guard('admin')->user()->role == '1' || in_array('65', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                <li class="menu-item has-submenu
                {{ ($route == 'online.user.list')? 'active':'' }}
                ">
                    <a class="menu-link" href="#">
                        <i class="icon material-icons md-person"></i>
                        <span class="text">Users</span>
                    </a>
                    <div class="submenu">
                        <a class="{{ ($route == 'online.user.list')? 'active':'' }}" href="{{ route('online.user.list') }}">Online User List</a>
                    </div>
                </li>
            @endif
            @if(Auth::guard('admin')->user()->role == '1')
            <li class="menu-item has-submenu
                {{ ($route == 'review.index')? 'active':'' }}
            ">
                <a class="menu-link" href="#">
                    <i class="icon material-icons md-stars"></i>
                    <span class="text">Rating & Review</span>
                </a>
                <div class="submenu">
                    <a class="{{ ($route == 'review.index') ? 'active':'' }}" href="{{ route('review.index') }}">Rating & Review List</a>
                </div>
            </li>
            @endif

            <li class="menu-item has-submenu {{ ($route == 'sms.templates') || ($route == 'sms.providers')?'active':'' }}">
                @if(Auth::guard('admin')->user()->role == '1' || in_array('33', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                    <a class="menu-link" href="#">
                        <i class="fontawesome_icon_custom fa-solid fa-phone"></i>
                        <span class="text">OTP System</span>
                    </a>
                @endif
                <div class="submenu">
                    @if(Auth::guard('admin')->user()->role == '1' || in_array('34', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                        <a class="{{ ($route == 'sms.templates') ? 'active':'' }}" href="{{ route('sms.templates') }}" >SMS Teamplates</a>
                    @endif

                    @if(Auth::guard('admin')->user()->role == '1' || in_array('35', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                        <a class="{{ ($route == 'sms.providers') ? 'active':'' }}" href="{{ route('sms.providers') }}" >SMS Providers</a>
                    @endif
                </div>
            </li>
            <li class="menu-item has-submenu
                {{ ($route == 'blog.index')? 'active':'' }}
                {{ ($route == 'blog.edit')? 'active':'' }}
                {{ ($route == 'blog.create')? 'active':'' }}
            ">
                @if(Auth::guard('admin')->user()->role == '1' || in_array('21', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                    <a class="menu-link" href="#">
                        <i class="icon material-icons md-comment"></i>
                        <span class="text">Blog</span>
                    </a>
                @endif
                <div class="submenu">
                    @if(Auth::guard('admin')->user()->role == '1' || in_array('21', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                        <a class="{{ ($route == 'blog.index') ? 'active':'' }}" href="{{ route('blog.index') }}">Blog List</a>
                    @endif
                    @if(Auth::guard('admin')->user()->role == '1' || in_array('22', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                        <a class="{{ ($route == 'blog.create') ? 'active':'' }}" href="{{ route('blog.create') }}">Blog Add</a>
                    @endif
                </div>
            </li>

            <li class="menu-item has-submenu
                {{ ($route == 'page.index')? 'active':'' }}
                {{ ($route == 'page.edit')? 'active':'' }}
                {{ ($route == 'page.create')? 'active':'' }}
            ">
                @if(Auth::guard('admin')->user()->role == '1' || in_array('49', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                    <a class="menu-link" href="#">
                        <i class="icon material-icons md-pages"></i>
                        <span class="text">Pages</span>
                    </a>
                @endif
                <div class="submenu">
                    @if(Auth::guard('admin')->user()->role == '1' || in_array('49', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                        <a class="{{ ($route == 'page.index') ? 'active':'' }}" href="{{ route('page.index') }}">Page List</a>
                    @endif
                    @if(Auth::guard('admin')->user()->role == '1' || in_array('50', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                        <a class="{{ ($route == 'page.create') ? 'active':'' }}" href="{{ route('page.create') }}">Page Add</a>
                    @endif
                </div>
            </li>

            @if(Auth::guard('admin')->user()->role == '1')
            <li class="menu-item has-submenu
                {{ ($route == 'subscribers.index')? 'active':'' }}
            ">
                <a class="menu-link" href="#">
                    <i class="icon material-icons md-pie_chart"></i>
                    <span class="text">Subscribers</span>
                </a>
                <div class="submenu">
                    <a class="{{ ($route == 'subscribers.index') ? 'active':'' }}" href="{{ route('subscribers.index') }}">Subsribers List</a>
                </div>
            </li>
            @endif

            @if(Auth::guard('admin')->user()->role == '1')
                <li class="menu-item has-submenu
                {{ ($route == 'contact.index')? 'active':'' }}
                ">
                    <a class="menu-link" href="#">
                        <i class="icon material-icons md-person"></i>
                        <span class="text">Contact list</span>
                    </a>
                    <div class="submenu">
                        <a class="{{ ($route == 'contact.index')? 'active':'' }}" href="{{ route('contact.index') }}">Contact list</a>
                    </div>
                </li>
            @endif


        </ul>
        <hr />
        @if(Auth::guard('admin')->user()->role == '1')
            <ul class="menu-aside">
                <li class="menu-item has-submenu
                {{ ($route == 'setting.index')? 'active':'' }}
                {{ ($route == 'shipping.index')? 'active':'' }}
                {{ ($route == 'shipping.create')? 'active':'' }}
                {{ ($route == 'shipping.edit')? 'active':'' }}
                {{ ($route == 'setting.facebook_plugin_setting')? 'active':'' }}
                ">
                    <a class="menu-link" href="#">
                        <i class="icon material-icons md-settings"></i>
                        <span class="text">Settings</span>
                    </a>
                    <div class="submenu">
                        <a class="{{ ($route == 'setting.index') ? 'active':'' }}" href="{{ route('setting.index') }}">Home</a>
                        <a class="{{ ($route == 'setting.activation') ? 'active':'' }}" href="{{ route('setting.activation') }}">Activation</a>
                        <a class="{{ ($route == 'setting.facebook_plugin_setting') ? 'active':'' }}" href="{{ route('setting.facebook_plugin_setting') }}">Facebook Plugin</a>
                        <a class="{{ ($route == 'shipping.index')||($route == 'shipping.create')||($route == 'shipping.edit') ? 'active':'' }}" href="{{ route('shipping.index') }}">Shipping Methods</a>
                        <a class="{{ ($route == 'paymentMethod.config') ? 'active':'' }}" href="{{ route('paymentMethod.config') }}">Payment Methods</a>
                        {{--  <a class="#" href="#">Free Shipping</a>  --}}
                    </div>
                </li>
            </ul>
        @endif
        <br />
        <br />
        <div class="sidebar-widgets">
           <div class="copyright text-center m-25">
              <p>
                 <strong class="d-block">Admin Dashboard</strong> © <script>document.write(new Date().getFullYear())</script> All Rights Reserved
              </p>
           </div>
        </div>
    </nav>
</aside>

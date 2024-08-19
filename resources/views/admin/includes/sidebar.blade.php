<!--start sidebar -->
<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{asset('assets/images/logo/favicon.png')}}" class="site-logo" alt="@lang('Favicon')">
        </div>
        <div>
            <h4 class="site-name">{{ __($setting->site_name)}}</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class="las la-bars"></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="sidebar-menu" id="menu">
        <li class="sidebar-menu-item">
            <a href="{{route('admin.dashboard')}}" class="{{ menuActive('admin.dashboard')[0] }}">
                <i class="las la-home"></i>
                <span class="menu-title">@lang('Dashboard')</span>
            </a>
        </li>
        <li class="menu-label">@lang('MANAGE USERS')</li>
        <li class="has-child-menu {{ menuActive('admin.users.*')[2] }}">
            <a class="has-arrow {{ menuActive('admin.users.*')[0] }}">
                <i class="las la-user"></i>
                <span class="menu-title">@lang('Manage Users')</span>
            </a>
            <ul class="menu-li menu-li-active {{ menuActive('admin.users.*')[1] }}">
                <li class="{{ menuActive('admin.users.list')[2] }} {{ menuActive('admin.users.details')[2] }}">
                    <a href="{{ route('admin.users.list') }}" class="{{ menuActive('admin.users.list')[0] }} {{ menuActive('admin.users.details')[0] }}">
                        <i class="las la-arrow-right"></i>@lang('All Users')
                    </a>
                </li>
                <li class="{{ menuActive('admin.users.active')[2] }}">
                    <a href="{{ route('admin.users.active')}}" class="{{ menuActive('admin.users.active')[0] }}">
                        <i class="las la-arrow-right"></i>@lang('Active Users')
                    </a>
                </li>
                <li class="{{ menuActive('admin.users.banned')[2] }}">
                    <a href="{{ route('admin.users.banned')}}" class="{{ menuActive('admin.users.banned')[0] }}">
                        <i class="las la-arrow-right"></i>@lang('Banned Users')
                    </a>
                </li>
                <li class="{{ menuActive('admin.users.unverified')[2] }}">
                    <a href="{{ route('admin.users.unverified')}}" class="{{ menuActive('admin.users.unverified')[0] }}">
                        <i class="las la-arrow-right"></i>@lang('Un-verified Users')
                    </a>
                </li>
            </ul>
        </li>
        <li class="sidebar-menu-item">
            <a href="{{route('admin.supplier.index')}}" class="{{ menuActive('admin.supplier.index')[0] }}">
                <i class="las la-shipping-fast"></i>
                <span class="menu-title">@lang('Supplier')</span>
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a href="{{route('admin.category.index')}}" class="{{ menuActive('admin.category.index')[0] }}">
                <i class="las la-ellipsis-v"></i>
                <span class="menu-title">@lang('Category')</span>
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a href="{{route('admin.brand.index')}}" class="{{ menuActive('admin.brand.index')[0] }}">
                <i class="las la-copyright"></i>
                <span class="menu-title">@lang('Brand')</span>
            </a>
        </li>
        {{-- <li class="sidebar-menu-item">
            <a href="{{route('admin.size.index')}}" class="{{ menuActive('admin.size.index')[0] }}">
                <i class="las la-compress"></i>
                <span class="menu-title">@lang('Size')</span>
            </a>
        </li> --}}
        <li class="sidebar-menu-item">
            <a href="{{route('admin.color.index')}}" class="{{ menuActive('admin.color.index')[0] }}">
                <i class="las la-fill-drip"></i>
                <span class="menu-title">@lang('Color')</span>
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a href="{{route('admin.product.index')}}" class="{{ menuActive('admin.product.index')[0] }}">
                <i class="lab la-product-hunt"></i>
                <span class="menu-title">@lang('Product')</span>
            </a>
        </li>
        <li class="menu-label">@lang('Stock')</li>
        <li class="has-child-menu {{ menuActive('admin.stock.*')[2] }}">
            <a class="has-arrow {{ menuActive('admin.stock.*')[0] }}">
                <i class="las la-bug"></i>
                <span class="menu-title">@lang('Manage Stock')</span>
            </a>
            <ul class="menu-li menu-li-active {{ menuActive('admin.stock.*')[1] }}">
                <li class="{{ menuActive('admin.stock.index')[2] }}">
                    <a href="{{ route('admin.stock.index') }}" class="{{ menuActive('admin.stock.index')[0] }}">
                        <i class="las la-arrow-right"></i>@lang('All Stock')
                    </a>
                </li>
                <li class="{{ menuActive('admin.stock.full')[2] }}">
                    <a href="{{ route('admin.stock.full') }}" class="{{ menuActive('admin.stock.full')[0] }}">
                        <i class="las la-arrow-right"></i>@lang('Available Stock')
                    </a>
                </li>
                <li class="{{ menuActive('admin.stock.out')[2] }}">
                    <a href="{{ route('admin.stock.out') }}" class="{{ menuActive('admin.stock.out')[0] }}">
                        <i class="las la-arrow-right"></i>@lang('Stock Out')
                    </a>
                </li>
                <li class="{{ menuActive('admin.stock.low')[2] }}">
                    <a href="{{ route('admin.stock.low') }}" class="{{ menuActive('admin.stock.low')[0] }}">
                        <i class="las la-arrow-right"></i>@lang('Low Stock')
                    </a>
                </li>
            </ul>
        </li>
        <li class="sidebar-menu-item">
            <a href="{{route('admin.order.index')}}" class="{{ menuActive('admin.order.index')[0] }}">
                <i class="las la-parachute-box"></i>
                <span class="menu-title">@lang('Order')</span>
            </a>
        </li>

        <li class="menu-label">@lang('All Report')</li>
        <li class="has-child-menu {{ menuActive('admin.reports.*')[2] }}">
            <a class="has-arrow {{ menuActive('admin.reports.*')[0] }}">
                <i class="las la-bug"></i>
                <span class="menu-title">@lang('Sale Report')</span>
            </a>
            <ul class="menu-li menu-li-active {{ menuActive('admin.reports.*')[1] }}">
                <li class="{{ menuActive('admin.reports.order')[2] }}">
                    <a href="{{ route('admin.reports.order') }}" class="{{ menuActive('admin.reports.order')[0] }}">
                        <i class="las la-arrow-right"></i>@lang('All Sale Report')
                    </a>
                </li>
                <li class="{{ menuActive('admin.reports.ecommerce')[2] }}">
                    <a href="{{ route('admin.reports.ecommerce')}}" class="{{ menuActive('admin.reports.ecommerce')[0] }}">
                        <i class="las la-arrow-right"></i>@lang('Ecommerce')
                    </a>
                </li>
                <li class="{{ menuActive('admin.reports.store')[2] }}">
                    <a href="{{ route('admin.reports.store')}}" class="{{ menuActive('admin.reports.store')[0] }}">
                        <i class="las la-arrow-right"></i>@lang('Store')
                    </a>
                </li>
                <li class="{{ menuActive('admin.reports.facebook')[2] }}">
                    <a href="{{ route('admin.reports.facebook')}}" class="{{ menuActive('admin.reports.facebook')[0] }}">
                        <i class="las la-arrow-right"></i>@lang('Facebook')
                    </a>
                </li>
            </ul>
        </li>
        <li class="has-child-menu {{ menuActive('admin.purchase.*')[2] }}">
            <a class="has-arrow {{ menuActive('admin.purchase.*')[0] }}">
                <i class="las la-bug"></i>
                <span class="menu-title">@lang('Purchase Report')</span>
            </a>
            <ul class="menu-li menu-li-active {{ menuActive('admin.purchase.*')[1] }}">
                <li class="{{ menuActive('admin.purchase.purchase')[2] }}">
                    <a href="{{ route('admin.purchase.purchase') }}" class="{{ menuActive('admin.purchase.purchase')[0] }}">
                        <i class="las la-arrow-right"></i>@lang('All Purchase Report')
                    </a>
                </li>
                <li class="{{ menuActive('admin.purchase.today.purchase')[2] }}">
                    <a href="{{ route('admin.purchase.today.purchase')}}" class="{{ menuActive('admin.purchase.today.purchase')[0] }}">
                        <i class="las la-arrow-right"></i>@lang('Today Purchase Report')
                    </a>
                </li>
                <li class="{{ menuActive('admin.purchase.date.purchase')[2] }}">
                    <a href="{{ route('admin.purchase.date.purchase')}}" class="{{ menuActive('admin.purchase.date.purchase')[0] }}">
                        <i class="las la-arrow-right"></i>@lang('Date Wise Purchase Report')
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-label">@lang('PAYMENTS HISTORY')</li>
        <li class="sidebar-menu-item">
            <a href="{{ route('admin.payment.history')}}" class="{{ menuActive('admin.payment.history')[0] }}">
                <div class="menu-icon"><i class="las la-file-invoice"></i>
                </div>
                <div class="menu-title">@lang('Payment')</div>
            </a>
        </li>

        <li class="menu-label">@lang('PAYMENT')</li>
        <li class="has-child-menu {{ menuActive('admin.gateway.method.*')[2] }}">
            <a class="has-arrow {{ menuActive('admin.gateway.method.*')[0] }}">
                <i class="las la-credit-card"></i>
                <span class="menu-title">@lang('Payment Gateways')</span>
            </a>
            <ul class="menu-li menu-li-active {{ menuActive('admin.gateway.method.*')[1] }}">
                <li class="{{ menuActive('admin.gateway.method.index')[2] }} {{ menuActive('admin.gateway.method.edit')[2] }}">
                    <a href="{{ route('admin.gateway.method.index') }}" class="{{ menuActive('admin.gateway.method.index')[0] }} {{ menuActive('admin.gateway.method.edit')[0] }}">
                        <i class="las la-arrow-right"></i>@lang('Gateway Method')
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-label">@lang('HISTORY')</li>
        <li class="sidebar-menu-item">
            <a href="{{ route('admin.all.login.history')}}" class="{{ menuActive('admin.all.login.history')[0] }}">
                <div class="menu-icon"><i class="las la-history"></i>
                </div>
                <div class="menu-title">@lang('Login History')</div>
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a href="{{ route('admin.subscriber.index')}}" class="{{ menuActive('admin.subscriber.index')[0] }}">
                <div class="menu-icon"><i class="las la-envelope-open-text"></i>
                </div>
                <div class="menu-title">@lang('Subscribers')</div>
            </a>
        </li>
        <li class="has-child-menu {{ menuActive('admin.theme.*')[2] }}">
            <a class="has-arrow {{ menuActive('admin.theme.*')[0] }}">
                <i class="las la-puzzle-piece"></i>
                <span class="menu-title">@lang('Manage Section')</span>
            </a>
            <ul class="menu-li menu-li-active {{ menuActive('admin.theme.*')[1] }}">
                @foreach ($tempBars as $key => $bar)
                    <li class="sidebar-menu-item">
                        <a href="{{ route('admin.theme.item', $key) }}"
                            class="@if (route('admin.theme.item', $key) == url()->current()) sidebar-menu-active @endif">
                            <span class="sidebar-menu-title"><i class="fas fa-chevron-right mr-0"></i>
                                {{ __($bar['name']) }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>
        <li class="menu-label">@lang('SETTING')</li>
        <li class="sidebar-menu-item">
            <a href="{{ route('admin.setting.index')}}" class="{{ menuActive('admin.setting.index')[0] }}">
                <div class="menu-icon"><i class="las la-sliders-h"></i>
                </div>
                <div class="menu-title">@lang('Site Setting')</div>
            </a>
        </li>
        <li class="sidebar-menu-item" class="{{ menuActive('admin.setting.logfav')[0] }}">
            <a href="{{ route('admin.setting.logfav')}}">
                <div class="menu-icon"><i class="las la-images"></i>
                </div>
                <div class="menu-title">@lang('Logo & Favicon')</div>
            </a>
        </li>
        <li class="sidebar-menu-item" class="{{ menuActive('admin.setting.extensions.index')[0] }}">
            <a href="{{ route('admin.setting.extensions.index')}}">
                <div class="menu-icon"><i class="las la-cogs"></i>
                </div>
                <div class="menu-title">@lang('Extensions')</div>
            </a>
        </li>
        <li class="sidebar-menu-item" class="{{ menuActive('admin.setting.seo')[0] }}">
            <a href="{{ route('admin.setting.seo')}}">
                <div class="menu-icon"><i class="las la-chart-area"></i>
                </div>
                <div class="menu-title">@lang('SEO Manager')</div>
            </a>
        </li>
        <li class="has-child-menu {{ menuActive('admin.email.*')[2] }}">
            <a class="has-arrow {{ menuActive('admin.email.*')[0] }}">
                <i class="las la-envelope"></i>
                <span class="menu-title">@lang('Manage Email')</span>
            </a>
            <ul class="menu-li menu-li-active {{ menuActive('admin.email.*')[1] }}">
                <li class="{{ menuActive('admin.email.general')[2] }}">
                    <a href="{{ route('admin.email.general') }}" class="{{ menuActive('admin.email.general')[0] }}">
                        <i class="las la-arrow-right"></i>@lang('General Email')
                    </a>
                </li>
                <li class="{{ menuActive('admin.email.index')[2] }}">
                    <a href="{{ route('admin.email.index')}}" class="{{ menuActive('admin.email.index')[0] }}">
                        <i class="las la-arrow-right"></i>@lang('Email Template')
                    </a>
                </li>
            </ul>
        </li>
        <li class="sidebar-menu-item">
            <a href="{{ route('admin.language.index')}}" class="{{ menuActive('admin.language*')[0] }}">
                <div class="menu-icon"><i class="las la-language"></i>
                </div>
                <div class="menu-title">@lang('Language')</div>
            </a>
        </li>
        <li class="sidebar-menu-item" class="{{ menuActive('admin.cookie')[0] }}">
            <a href="{{ route('admin.cookie')}}">
                <div class="menu-icon"><i class="las la-cookie"></i>
                </div>
                <div class="menu-title">@lang('GDPR Cookie')</div>
            </a>
        </li>
        <li class="menu-label">@lang('OTHER')</li>
        <li class="sidebar-menu-item">
            <a href="{{ route('admin.system.info')}}" class="{{ menuActive('admin.system.info')[0] }}">
                <div class="menu-icon"><i class="lab la-windows"></i>
                </div>
                <div class="menu-title">@lang('System Information')</div>
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a href="{{ route('admin.optimize')}}" class="{{ menuActive('admin.optimize')[0] }}">
                <div class="menu-icon"><i class="las la-broom"></i>
                </div>
                <div class="menu-title">@lang('Clear Cacha')</div>
            </a>
        </li>
    </ul>
    <!--end navigation-->
</div>
<!--end sidebar -->

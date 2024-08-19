<header class="header-area header-style-1 header-height-2">
    <div class="header-top header-top-ptb-1 d-none d-lg-block">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xxl-4 col-xl-12 col-lg-12">
                    <div class="header-info">
                        <ul>
                            <li class="contact_header"><i class="fa fa-envelope ms-1"></i>
                                <span>Email
                                    &nbsp;:&nbsp; <strong> <a href="tel:{{ get_setting('email')->value ?? 'null' }}">
                                            {{ get_setting('email')->value ?? 'null' }}</a></strong>
                                </span>
                            </li>

                            <li class="contact_header"><i class="fa fa-phone ms-1"></i>
                                <span>Hotline <i class="fa-solid fa-angle-down"></i>
                                    {{-- &nbsp;:&nbsp; <strong> <a href="tel:{{ get_setting('phone')->value ?? 'null' }}">
                                    {{ get_setting('phone')->value ?? 'null' }}</a></strong> --}}
                                </span>

                                <div class="email__contact">
                                    <a href='tel:09678771700' title='Call +8809678771700'>+8809678771700</a>
                                    <p style="color:white;text-align:center;font-size:12px">(10am-12pm)</p>
                                 {{--   <?php
                                    $phone = get_setting('phone')->value ?? 'null';

                                    if ($phone !== 'null') {
                                        $phoneArray = explode(',', $phone);
                                        foreach ($phoneArray as $phoneNumber) {
                                            echo "<a href='tel:$phoneNumber' title='Call $phoneNumber'>$phoneNumber</a>";
                                        }
                                    } else {
                                        echo "<a href='tel:null'>null</a>";
                                    }
                                    ?>--}}

                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-xxl-4 col-xl-6 col-lg-6">
                    <div class="text-center">
                        <div id="news-flash" class="d-inline-block">
                            <ul>
                                <li>100% Secure delivery without contacting the courier</li>
                                <li>Supper Value Deals - Save more with coupons</li>
                                <li>Trendy 25silver jewelry, save up 35% off today</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-xl-6 col-lg-6">
                    <div class="header-info header-info-right">

                        <ul>
                            <li class="contact__page"><a href="{{ route('contact.page') }}">Contact Us</a></li>
                            <li>
                                <div class="mobile-social-icon justify-content-center">
                                    <a target="_blank" href="{{ get_setting('facebook_url')->value ?? 'null' }}"
                                        title="Facebook"><img
                                            src="{{ asset('frontend/assets/imgs/theme/icons/icon-facebook-white.svg') }}"
                                            alt="" /></a>
                                    <a target="_blank" href="{{ get_setting('twitter_url')->value ?? 'null' }}"
                                        title="Twitter"><img
                                            src="{{ asset('frontend/assets/imgs/theme/icons/icon-twitter-white.svg') }}"
                                            alt="" /></a>
                                    <a target="_blank" href="{{ get_setting('instagram_url')->value ?? 'null' }}"
                                        title="Instagram"><img
                                            src="{{ asset('frontend/assets/imgs/theme/icons/icon-instagram-white.svg') }}"
                                            alt="" /></a>
                                    <a target="_blank" href="{{ get_setting('pinterest_url')->value ?? 'null' }}"
                                        title="Pinterest"><img
                                            src="{{ asset('frontend/assets/imgs/theme/icons/icon-pinterest-white.svg') }}"
                                            alt="" /></a>
                                    <a target="_blank" href="{{ get_setting('youtube_url')->value ?? 'null' }}"
                                        title="Youtube"><img
                                            src="{{ asset('frontend/assets/imgs/theme/icons/icon-youtube-white.svg') }}"
                                            alt="" /></a>
                                </div>
                            </li>
                            <li><a href="{{ route('order.tracking') }}">Order Tracking</a></li>
                            <li>
                                @if (session()->get('language') == 'bangla')
                                    <a class="language-dropdown-active"
                                        href="{{ route('english.language') }}">English</a>
                                @else
                                    <a class="language-dropdown-active" href="{{ route('bangla.language') }}">বাংলা</a>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-lg-block sticky-bar">
        <div class="header-middle header-middle-ptb-1 d-none d-lg-block">
            <div class="container">
                @php
                    $maintenance = getMaintenance();
                @endphp

                @if ($maintenance == 1)
                    <div class="row mb-2">
                        <div class="col-7 offset-3 ">
                            <div class="maintain-sms">
                                <h4 style="color:white" class="text-center">This Website is now under Maintanence</h4>
                            </div>
                        </div>
                        <div class="col-2 text-end">
                        </div>
                    </div>
                @endif

                @php
                    $couponCode = getCoupon();
                @endphp
                @if ($couponCode)
                    <div class="maintain-sms">
                        <h6 style="color:white">Coupon Code: {{ $couponCode }}</h6>
                    </div>
                @endif

                <div class="header-wrap">
                    <div class="logo logo-width-1">
                        <a href="{{ route('home') }}">
                            @php
                                $logo = get_setting('site_logo');
                            @endphp
                            @if ($logo != null)
                                <img src="{{ asset(get_setting('site_logo')->value ?? 'null') }}"
                                    alt="{{ env('APP_NAME') }}">
                            @else
                                <img src="{{ asset('upload/no_image.jpg') }}" alt="{{ env('APP_NAME') }}"
                                    style="height: 60px !important; width: 80px !important; min-width: 80px !important;">
                            @endif
                        </a>
                    </div>
                    <div class="header-right">
                        <div class="search-style-2">
                            <div class="search-area">
                                <form action="{{ route('product.search') }}" method="post" class="mx-auto">
                                    @csrf
                                    <select class="select-active" name="searchCategory" id="searchCategory">
                                        <option value="0">All Categories</option>
                                        @foreach (get_all_categories() as $cat)
                                            <option value="{{ $cat->id }}">
                                                @if (session()->get('language') == 'bangla')
                                                    {{ $cat->name_bn }}
                                                @else
                                                    {{ $cat->name_en }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <input class="search-field search" onfocus="search_result_show()"
                                        onblur="search_result_hide()" name="search" placeholder="Search here..." />
                                    <div>
                                        <button type="submit" class="btn btn-primary text-white btn-sm rounded-0"><i
                                                class="fa-solid fa-magnifying-glass"></i></button>
                                    </div>
                                </form>
                                <div class="shadow-lg searchProducts"></div>
                            </div>
                            
                        </div>
                        <div class="header-action-right">
                            <div class="header-action-2">
                                <div class="header-action-2 cart_hidden_mobile me-2">
                                    <div class="header-action-icon-2">
                                        <a class="mini-cart-icon item_mini_cart" href="#">
                                            <img alt="Nest"
                                                src="{{ asset('frontend/assets/imgs/theme/icons/icon-cart.svg') }}" />
                                            <span class="pro-count blue cartQty"></span>
                                        </a>
                                        <a href="{{ route('cart.show') }}"><span class="lable">Cart</span></a>
                                        <div class="cart-dropdown-wrap cart-dropdown-hm2">
                                            <div id="miniCart">

                                            </div>
                                            <div class="shopping-cart-footer" id="miniCart_btn">
                                                <div class="shopping-cart-total">
                                                    <h4>Total <span id="cartSubTotal"></span></h4>
                                                </div>
                                                <div class="shopping-cart-button">
                                                    <a href="{{ route('cart.show') }}" class="outline">View cart</a>
                                                    <a href="{{ route('checkout') }}">Checkout</a>
                                                </div>
                                            </div>
                                            <div class="shopping-cart-footer" id="miniCart_empty_btn">

                                                <div class="shopping-cart-button d-flex flex-row-reverse">
                                                    <a href="{{ route('home') }}">Continue Shopping</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="header-action-icon-2">
                                    @auth
                                        <a href="#">
                                            <img class="svgInject" alt="Nest"
                                                src="{{ asset('frontend/assets/imgs/theme/icons/icon-user.svg') }}" />
                                        </a>
                                        <a href="{{ route('dashboard') }}"><span class="lable ml-0">Account</span></a>
                                        <div class="cart-dropdown-wrap cart-dropdown-hm2 account-dropdown">
                                            <ul>
                                                <li>
                                                    <a href="{{ route('dashboard') }}"><i
                                                            class="fi fi-rs-user mr-10"></i>My Account</a>
                                                </li>
                                                <li>
                                                    <a class=" mr-10" href="{{ route('logout') }}"
                                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                        <i class="fi-rs-sign-out mr-10"></i>
                                                        {{ __('Logout') }}
                                                    </a>
                                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                        class="d-none">
                                                        @csrf
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    @endauth
                                    @guest
                                        <a href="{{ route('login') }}"><span class="lable ml-0"><i
                                                    class="fa-solid fa-arrow-right-to-bracket mr-10"></i>Login</span></a>
                                    @endguest
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-bottom header-bottom-bg-color">
            <div class="container">
                <div class="header-wrap header-space-between position-relative">
                    <div class="logo logo-width-1 d-block d-lg-none">

                        <a href="{{ route('home') }}">
                            @php
                                $logo = get_setting('site_logo');
                            @endphp
                            @if ($logo != null)
                                <img src="{{ asset(get_setting('site_logo')->value ?? 'null') }}"
                                    alt="{{ env('APP_NAME') }}">
                            @else
                                <img src="{{ asset('upload/no_image.jpg') }}" alt="{{ env('APP_NAME') }}"
                                    style="height: 60px !important; width: 80px !important; min-width: 80px !important;">
                            @endif
                        </a>
                    </div>
                    <div class="header-nav d-none d-lg-flex w-100">
                        <div class="row  w-100 g-0">
                            <div class="col-xl-2 col-lg-2">
                                <div class="sidebar__categories">
                                    <div class="siderbar__menu__title">all categories <i class="fa-solid fa-bars"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-10 col-lg-10">
                                <div class="main__menu">
                                    <ul class="d-flex position-relative">
                                        <li>
                                            <a href="{{ route('home') }}">
                                                @if (session()->get('language') == 'bangla')
                                                    হোম
                                                @else
                                                    Home
                                                @endif
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('product.show') }}">
                                                @if (session()->get('language') == 'bangla')
                                                    দোকান
                                                @else
                                                    Shop
                                                @endif
                                            </a>
                                        </li>

                                        {{-- mega menu  --}}
                                        @foreach (get_categories()->take(5) as $category)
                                            @if ($category->has_sub_sub > 0)
                                                <li>
                                                    <a href="{{ route('product.category', $category->slug) }}">
                                                        @if (session()->get('language') == 'bangla')
                                                            {{ $category->name_bn }}
                                                        @else
                                                            {{ $category->name_en }}
                                                        @endif
                                                        @if ($category->sub_categories && count($category->sub_categories) > 0)
                                                            <i class="fa-solid fa-angle-down"></i>
                                                        @endif
                                                    </a>
                                                    <div class="mega__menu__system">
                                                        @if ($category->sub_categories && count($category->sub_categories) > 0)
                                                            @foreach ($category->sub_categories as $sub_category)
                                                                <ul class="mega__submenu__item">
                                                                    <li>
                                                                        <a class="menu-title"
                                                                            href="{{ route('product.category', $sub_category->slug) }}">
                                                                            @if (session()->get('language') == 'bangla')
                                                                                {{ $sub_category->name_bn }}
                                                                            @else
                                                                                {{ $sub_category->name_en }}
                                                                            @endif
                                                                        </a>
                                                                        @if ($sub_category->sub_sub_categories && count($sub_category->sub_sub_categories) > 0)
                                                                            <ul class="mega__child__item">
                                                                                @foreach ($sub_category->sub_sub_categories as $sub_sub_category)
                                                                                    <li>
                                                                                        <a
                                                                                            href="{{ route('product.category', $sub_sub_category->slug) }}">
                                                                                            @if (session()->get('language') == 'bangla')
                                                                                                {{ $sub_sub_category->name_bn }}
                                                                                            @else
                                                                                                {{ $sub_sub_category->name_en }}
                                                                                            @endif
                                                                                        </a>
                                                                                    </li>
                                                                                @endforeach
                                                                            </ul>
                                                                        @endif
                                                                    </li>
                                                                </ul>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </li>
                                            @else
                                                <li>
                                                    <a href="{{ route('product.category', $category->slug) }}">
                                                        @if (session()->get('language') == 'bangla')
                                                            {{ $category->name_bn }}
                                                        @else
                                                            {{ $category->name_en }}
                                                        @endif
                                                        @if ($category->sub_categories && count($category->sub_categories) > 0)
                                                            <i class="fa-solid fa-angle-down"></i>
                                                        @endif
                                                    </a>

                                                    @if ($category->sub_categories && count($category->sub_categories) > 0)
                                                        <ul class="sub__menu">
                                                            @foreach ($category->sub_categories as $sub_category)
                                                                <li><a
                                                                        href="{{ route('product.category', $sub_category->slug) }}">{{ $sub_category->name_en }}</a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endif
                                        @endforeach
                                        <li>
                                            <a href="{{ route('campaign.all') }}">
                                                @if (session()->get('language') == 'bangla')
                                                    প্রচারণ
                                                @else
                                                    Campaign
                                                @endif
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('hot_deals.all') }}">
                                                @if (session()->get('language') == 'bangla')
                                                    হট ডিল <span class="hot_deals">hot</span>
                                                @else
                                                    hot deals <span class="hot_deals">hot</span>
                                                @endif
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="header-action-icon-2 d-block d-lg-none">
                        <div class="burger-icon burger-icon-white">
                            <span class="burger-icon-top"></span>
                            <span class="burger-icon-mid"></span>
                            <span class="burger-icon-bottom"></span>
                        </div>
                    </div>
                    <div class="header-action-right d-block d-lg-none">
                        <div class="header-action-2">
                            <!--Mobile Header Search start-->
                            <a class="p-2 d-block text-reset active show">
                                <i class="fas fa-search la-flip-horizontal la-2x"></i>
                            </a>
                            <section class="advance-search" style="display: none;">
                                <div class="search-box">
                                    <form action="{{ route('product.search') }}" method="post">
                                        @csrf
                                        <div class="input-group py-2">
                                            <span class="back_left hide"><i
                                                    class="fas fa-long-arrow-left me-2"></i></span>
                                            <input class="header-search form-control search-field search"
                                                aria-label="Input group example" aria-describedby="btnGroupAddon"
                                                onfocus="search_result_show()" onblur="search_result_hide()"
                                                name="search" placeholder="Search here..." />
                                            <!--<input class="search-field search" onfocus="search_result_show()" onblur="search_result_hide()" name="search" placeholder="Search here..." />-->
                                            <button type="submit" class="input-group-text btn btn-sm"
                                                id="btnGroupAddon"><i class="fas fa-search"></i></button>
                                        </div>
                                    </form>
                                    <div class="shadow-lg searchProducts"></div>
                                </div>
                               
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-bottom-1 header-bottom-bg-color sticky-bar d-lg-none">
        <div class="container">
            <ul class="mobile-hor-swipe header-wrap header-space-between position-relative">
                <li class="mb-10">
                    <a class="p-10" href="{{ route('home') }}">
                        @if (session()->get('language') == 'bangla')
                            হোম
                        @else
                            Home
                        @endif
                    </a>
                </li>
                <li class="mb-10">
                    <a class="p-10" href="{{ route('product.show') }}">
                        @if (session()->get('language') == 'bangla')
                            দোকান
                        @else
                            Shop
                        @endif
                    </a>
                </li>

                @foreach (get_categories()->take(5) as $category)
                    <li class="mb-10">
                        <a class="p-10" href="{{ route('product.category', $category->slug) }}">
                            @if (session()->get('language') == 'bangla')
                                {{ $category->name_bn }}
                            @else
                                {{ $category->name_en }}
                            @endif
                        </a>
                    </li>
                @endforeach
                <li class="mb-10">
                    <a href="{{ route('campaign.all') }}">
                        @if (session()->get('language') == 'bangla')
                            প্রচারণ
                        @else
                            Campaign
                        @endif
                    </a>
                </li>
                <li class="mb-10">
                    <a href="{{ route('hot_deals.all') }}">
                        @if (session()->get('language') == 'bangla')
                            হট ডিল <span class="hot_deals">hot</span>
                        @else
                            hot deals <span class="hot_deals">hot</span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>
<!-- Mobile Side menu Start -->
<div class="mobile-header-active mobile-header-wrapper-style">
    <div class="mobile-header-wrapper-inner">
        <div class="mobile-header-top">
            <div class="mobile-header-logo">
                <a href="{{ route('home') }}">
                    @php
                        $logo = get_setting('site_logo');
                    @endphp
                    @if ($logo != null)
                        <img src="{{ asset(get_setting('site_logo')->value ?? 'null') }}"
                            alt="{{ env('APP_NAME') }}">
                    @else
                        <img src="{{ asset('upload/no_image.jpg') }}" alt="{{ env('APP_NAME') }}"
                            style="height: 60px !important; width: 80px !important; min-width: 80px !important;">
                    @endif
                </a>
            </div>
            <div class="mobile-menu-close close-style-wrap close-style-position-inherit">
                <button class="close-style search-close">
                    <i class="icon-top"></i>
                    <i class="icon-bottom"></i>
                </button>
            </div>
        </div>
        <div class="mobile-header-content-area advance-search">
            <div class="mobile-search search-style-3 mobile-header-border">
                <form action="{{ route('product.search') }}" method="post">
                    @csrf
                    <input class="search-field search" onfocus="search_result_show()" onblur="search_result_hide()"
                        name="search" placeholder="Search for items…" />
                    <button type="submit"><i class="fi-rs-search"></i></button>
                </form>
            </div>
            <div class="shadow-lg searchProducts"></div>
            <div class="mobile-menu-wrap mobile-header-border">
                <!-- mobile menu start -->
                <nav>
                    <ul class="mobile-menu font-heading">
                        <li class="menu-item-has-children">
                            <a href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="menu-item-has-children">
                            <a href="{{ route('product.show') }}">
                                @if (session()->get('language') == 'bangla')
                                    দোকান
                                @else
                                    Shop
                                @endif
                            </a>
                        </li>
                        @foreach (get_categories() as $category)
                            <li class="menu-item-has-children">
                                <a href="{{ route('product.category', $category->slug) }}">
                                    @if (session()->get('language') == 'bangla')
                                        {{ $category->name_bn }}
                                    @else
                                        {{ $category->name_en }}
                                    @endif
                                </a>
                                @if ($category->sub_categories && count($category->sub_categories) > 0)
                                    <ul class="dropdown">
                                        @foreach ($category->sub_categories as $subcategory)
                                            <li class="menu-item-has-children">
                                                <a href="{{ route('product.category', $subcategory->slug) }}">
                                                    @if (session()->get('language') == 'bangla')
                                                        {{ $subcategory->name_bn }}
                                                    @else
                                                        {{ $subcategory->name_en }}
                                                    @endif
                                                </a>
                                                @if ($subcategory->sub_sub_categories && count($subcategory->sub_sub_categories) > 0)
                                                    <ul class="dropdown">
                                                        @foreach ($subcategory->sub_sub_categories as $subsubcategory)
                                                            <li>
                                                                <a
                                                                    href="{{ route('product.category', $subsubcategory->slug) }}">
                                                                    @if (session()->get('language') == 'bangla')
                                                                        {{ $subsubcategory->name_bn }}
                                                                    @else
                                                                        {{ $subsubcategory->name_en }}
                                                                    @endif
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach

                        <li class="menu-item-has-children">
                            <a href="#">Pages</a>
                            <ul class="dropdown">
                                @foreach (get_pages_both_footer()->take(4) as $page)
                                    <li>
                                        <a href="{{ route('page.about', $page->slug) }}">{{ $page->title }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                        <li class="menu-item-has-children">
                            <a href="#">Language</a>
                            <ul class="dropdown">
                                @if (session()->get('language') == 'bangla')
                                    <li>
                                        <a href="{{ route('english.language') }}">English</a>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ route('bangla.language') }}">বাংলা</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    </ul>
                </nav>
                <!-- mobile menu end -->
            </div>
            <div class="mobile-header-info-wrap">
                <!-- <div class="single-mobile-header-info">
                    <a href="#"><i class="fi-rs-marker"></i> Our location </a>
                </div> -->
                <div class="single-mobile-header-info">
                    <a href="{{ route('login') }}"><i class="fi-rs-user"></i>Log In </a>
                </div>
                <div class="single-mobile-header-info">
                    <a href="{{ route('register') }}"><i class="fi-rs-user"></i>Sign Up </a>
                </div>
                <div class="single-mobile-header-info">
                    <div class="contact_header">
                        <span>Hotline <i class="fa-solid fa-angle-down"></i>
                        </span>

                        <div class="email__contact mt-0">
                            <a href='tel:09678771700' title='Call +8809678771700'>+8809678771700</a>
                            <p style="color:white;text-align:center;font-size:12px">(10am-12pm)</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mobile-social-icon mb-50">
                <h6 class="mb-15">Follow Us</h6>
                <a href="{{ get_setting('facebook_url')->value ?? 'null' }}"><img
                        src="{{ asset('frontend/assets/imgs/theme/icons/icon-facebook-white.svg') }}"
                        alt="" /></a>
                <a href="{{ get_setting('youtube_url')->value ?? 'null' }}"><img
                        src="{{ asset('frontend/assets/imgs/theme/icons/icon-twitter-white.svg') }}"
                        alt="" /></a>
                <a href="{{ get_setting('twitter_url')->value ?? 'null' }}"><img
                        src="{{ asset('frontend/assets/imgs/theme/icons/icon-instagram-white.svg') }}"
                        alt="" /></a>
                <a href="{{ get_setting('instagram_url')->value ?? 'null' }}"><img
                        src="{{ asset('frontend/assets/imgs/theme/icons/icon-pinterest-white.svg') }}"
                        alt="" /></a>
                <a href="{{ get_setting('pinterest_url')->value ?? 'null' }}"><img
                        src="{{ asset('frontend/assets/imgs/theme/icons/icon-youtube-white.svg') }}"
                        alt="" /></a>
            </div>
            <div class="site-copyright">
                Developed by:
                <a target="_blank"
                    href="{{ get_setting('developer_link')->value ?? 'null' }}">{{ get_setting('developed_by')->value ?? 'null' }}</a>
            </div>
        </div>
    </div>
</div>
<!-- Mobile Side menu End -->
<!--End header-->

{{-- @push('footer-script')
    <script type="text/javascript">
        /* ================ Advance Product Search ============ */
        $(document).on("input change", ".search", function() {
            let text = $(this).val();
            let category_id = $("#searchCategory").val();
            if (text.length > 0) {
                $.ajax({
                    data: {
                        search: text,
                        category: category_id
                    },
                    url: "/search-product",
                    method: 'post',
                    beforeSend: function(request) {
                        request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                    },
                    success: function(result) {
                        $(".searchProducts").html(result);
                    }
                }); // end ajax
            } else {
                $(".searchProducts").html("");
            }
        });


        // end function

        /* ================ Advance Product slideUp/slideDown ============ */


        function search_result_hide() {
            $(".searchProducts").slideUp();
        }

        function search_result_show() {
            $(".searchProducts").slideDown();
        }
    </script>

    <script>
        $(document).ready(function() {
            $(".show").click(function() {
                $(".advance-search").show();
            });
            $(".hide").click(function() {
                $(".advance-search").hide();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Initially, hide the email__contact div
            $(".email__contact").hide();

            // When clicking on the span, toggle the visibility of email__contact
            $(".contact_header span").click(function(e) {
                e.stopPropagation(); // Prevent the click event from propagating to the body
                $(".email__contact").toggle();

                // Toggle the angle icon
                $(this).find("i.fa-angle-down").toggleClass("fa-angle-up");
            });

            // When clicking on the body, hide the email__contact
            $("body").click(function() {
                $(".email__contact").hide();

                // Reset the angle icon to down
                $(".contact_header span i.fa-angle-up").removeClass("fa-angle-up").addClass(
                    "fa-angle-down");
            });
        });
    </script>
@endpush --}}
<footer class="main footer-dark">
    <section class="newsletter mb-15 wow animate__animated animate__fadeIn pt-4 pb-4" style="background: #2AACC2">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-6">
                    <div class="newsletter-title-footer"> <img src="{{ asset('upload/envelop.png') }}" alt=""> SIGN
                        UP FOR NEWSLETTER FOR OFFER AND UPDATES</div>
                </div>
                <div class="col-md-12 col-lg-6">
                    <div class="subscribe__form">
                        <form class="form-subcriber d-flex" method="POST" action="{{ route('subscribers.store') }}">
                            @csrf
                            <input type="email" placeholder="Your emaill address" required="" name="email" />
                            <button class="btn" type="submit">Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-padding footer-mid main-footer-custom">
        <div class="container pt-15 pb-20">
            <div class="row">
                <div class="col-lg-6 col-sm-12">
                    <div class="footer-link-widget wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                        <h4 class="widget-title">CORPORATE HEADQUARTER</h4>
                        <ul class="contact-infor">
                            <li><i class="fa-solid fa-location-dot"></i>Address:
                                <span>{{ get_setting('business_address')->value ?? 'null' }}</span>
                            </li>
                            <li><i class="fa fa-phone"></i>Call Us:<a
                                    href="tel:{{ get_setting('phone')->value ?? 'null' }}">{{ get_setting('phone')->value ?? 'null' }}</a>
                            </li>
                            <li><i class="fa-regular fa-envelope"></i>Email: <a
                                    href="mailto:{{ get_setting('email')->value ?? 'null' }}">{{ get_setting('email')->value ?? 'null' }}</a>
                            </li>
                            <li><i class="fa fa-clock"></i>Hours:
                                <span>{{ get_setting('business_hours')->value ?? 'null' }}</span></li>
                            <li><i class="fa fa-diamond"></i>TIN:
                                <span>691890290184</span></li>
                        </ul>

                        <div class="footer__social">
                            <a href="{{ get_setting('facebook_url')->value ?? 'null' }}" target="_blank"
                                title="facebook" class="facebook"><i class="fa-brands fa-facebook-f"></i></a>
                            <a href="{{ get_setting('youtube_url')->value ?? 'null' }}" target="_blank" title="youtube"
                                class="youtube"><i class="fa-brands fa-youtube"></i></a>
                            <a href="{{ get_setting('instagram_url')->value ?? 'null' }}" target="_blank"
                                title="twitter" class="twitter"><i class="fa-brands fa-twitter"></i></a>
                            <a href="{{ get_setting('instagram_url')->value ?? 'null' }}" target="_blank"
                                title="instagram" class="instagram"><i class="fa-brands fa-instagram"></i></a>
                            <a href="#" target="_blank" class="linkedin" title="linkedin"><i
                                    class="fa-brands fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-4">
                    <div class="footer-link-widget wow animate__animated animate__fadeInUp" data-wow-delay=".2s">
                        <h4 class="widget-title">ACCOUNTS</h4>
                        <ul class="footer-list mb-sm-5 mb-md-0">
                            <li><a href="{{ route('login') }}">Sign In</a></li>
                            <li><a href="{{ route('cart.show') }}">View Cart</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-4">
                    <div class="footer-link-widget wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                        <h4 class="widget-title">CUSTOMER SERVICE</h4>
                        <ul class="footer-list mb-sm-5 mb-md-0">
                            <li><a href="{{ route('order.tracking') }}">Track Your Order</a></li>
                            @foreach (get_pages_both_footer() as $page)
                                <li>
                                    <a href="{{ route('page.about', $page->slug) }}">
                                        {{ $page->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-4">
                    <div class="footer-link-widget wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                        <h4 class="widget-title">TERMS & POLICY 2024</h4>
                        <ul class="footer-list mb-sm-5 mb-md-0">
                            <li><a href="{{ route('terms.condition') }}">Terms & Condition</a></li>
                            <li><a href="{{ route('privacy.policy') }}">Privacy Policy</a></li>
                            <li><a href="{{ route('return&refund.policy') }}">Return & Refund Policy</a></li>
                            <li><a href="{{ route('cancellation.policy') }}">Cancellation Policy</a></li>
                            <li><a href="{{ route('terms.service') }}">Terms of Service</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="pb-30 wow animate__animated animate__fadeInUp" data-wow-delay="0">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-4 col-lg-4 col-md-7 col-12">
                    <div class="copyright__left text-md-center">
                        <ul class="contact-infor">
                            <li class="d-flex align-items-center">
                                <h5 class="widget-title">CHECK OUT OUR APP! </h5>
                                <div class="download-app">
                                    <a href="#" class="hover-up"><img
                                            src="{{ asset('frontend/assets/imgs/theme/google-play.jpg') }}"
                                            alt="" /></a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-5 col-12">
                    <p class="font-sm text-center classicit">
                        Developed by:
                        <a target="_blank" href="https://classicit.com.bd/">Classic IT</a>
                    </p>
                </div>
                {{-- <div class="col-xl-4 col-lg-4 col-md-6 text-end d-none d-md-block"> --}}
                <div class="col-xl-5 col-lg-4 col-md-12 col-12 text-center text-lg-end classicit_year">
                    <a href="#" class="footer__payment__info">
                        <img class="" src="{{ asset('frontend/assets/imgs/theme/payment-method.png') }}"
                            alt="">
                    </a>
                    <p class="font-sm mb-0">&copy; {{ get_setting('copy_right')->value ?? 'null' }} All rights
                        reserved</p>
                </div>
            </div>
        </div>
    </div>
</footer>

@php
    $prefix = Request::route()->getPrefix();
    $route = Route::current()->getName();
@endphp
<div class="nest-mobile-bottom-nav d-xl-none mobile_fixed_bottom bg-white shadow-lg border-top rounded-top"
    style="box-shadow: 0px -1px 10px rgb(0 0 0 / 15%)!important; ">
    <div class="row align-items-center gutters-5">
        <div class="col mobile_bottom_nav_col">
            <a href="{{ route('home') }}" class="text-reset d-block text-center pb-2 pt-3">
                <i class="fas fa-home fs-20 opacity-60 opacity-100 {{ $route == 'home' ? 'text-brand' : '' }}"></i>
                <span class="d-block fs-10 fw-600">Home</span>
            </a>
        </div>
        <div class="col mobile_bottom_nav_col">
            <a href="{{ route('product.show') }}" class="text-reset d-block text-center pb-2 pt-3">
                <span class="d-inline-block position-relative px-2">
                    <i
                        class="fa-sharp fa-solid fa-bag-shopping {{ $route == 'product.show' ? 'text-brand' : '' }}"></i>
                </span>
                <span class="d-block fs-10 fw-600">Shop</span>
            </a>
        </div>
        <div class="col-auto">
            <a href="{{ route('cart.show') }}" class="text-reset d-block text-center pb-2 pt-3">
                <span class="mobile-card-nav align-items-center d-flex justify-content-center position-relative"
                    style="margin-top: -33px;box-shadow: 0px -5px 10px rgb(0 0 0 / 15%);border-color: #fff !important;">
                    <i class="fa-solid fa-cart-shopping la-2x text-white"></i>
                </span>
                <span class="d-block mt-1 fs-10 fw-600">
                    Cart
                    (<span class="cart-count cartQty"></span>)
                </span>
            </a>
        </div>
        <div class="col mobile_bottom_nav_col mobile__category__show">
            {{-- <a href="{{ route('category_list.index') }}" class="text-reset d-block text-center pb-2 pt-3">
                <i class="fas fa-list-ul fs-20 opacity-60 {{ ($route == 'category_list.index')? 'text-brand':'' }}"></i>
                <span class="d-block fs-10 fw-600">Categories</span>
            </a> --}}
            <a href="javascript:void(0)" class="text-reset d-block text-center pb-2 pt-3">
                <i
                    class="fas fa-list-ul fs-20 opacity-60 {{ $route == 'category_list.index' ? 'text-brand' : '' }}"></i>
                <span class="d-block fs-10 fw-600">Categories</span>
            </a>
            @php
                $category = App\Models\Category::all();
            @endphp
            <ul class="bottom__fixed__category">
                @foreach (get_categories() as $category)
                    <li>
                        <span>
                            <a href="{{ route('product.category', $category->slug) }}" class="category-link">
                                @if (session()->get('language') == 'bangla')
                                    {{ $category->name_bn }}
                                @else
                                    {{ $category->name_en }}
                                @endif
                            </a>
                            @if ($category->sub_categories && count($category->sub_categories) > 0)
                                <i class="fa fa-angle-down category-toggle"></i>
                            @endif
                        </span>
                        @if ($category->sub_categories && count($category->sub_categories) > 0)
                            <ul class="subcategory">
                                @foreach ($category->sub_categories as $sub_category)
                                    <li><a
                                            href="{{ route('product.category', $sub_category->slug) }}">{{ $sub_category->name_en }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="col mobile_bottom_nav_col">
            @if (Auth()->check())
                <a href="{{ route('dashboard') }}" class="text-reset d-block text-center pb-2 pt-3">
                    <span class="d-block mx-auto">
                        @if(Auth::user()->profile_image)
                            <img src="{{ asset(Auth::user()->profile_image) }}" class="rounded-circle mobile_bottom_nav_account">
                        @else
                            <img src="{{ asset('frontend/assets/imgs/user_photo.png') }}"
                            class="rounded-circle mobile_bottom_nav_account">
                        @endif
                    </span>
                    <span class="d-block fs-10 fw-600">Account</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="text-reset d-block text-center pb-2 pt-3">
                    <span class="d-block mx-auto">
                        {{--  <i class="fa fa-user"></i>  --}}
                        <img src="{{ asset('frontend/assets/imgs/user_photo.png') }}"
                            style="width: 30px;height:30px;position: relative;
                        left: 50%;
                        transform: translateX(-50%);"
                            alt="">
                    </span>
                    <span class="d-block fs-10 fw-600">Account</span>
                </a>
            @endif
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $(".mobile__category__show a").click(function(event) {
            // Prevent the click event from propagating further
            event.stopPropagation();
            $(".bottom__fixed__category").toggle();
        });

        // Listen for clicks on the body
        {{--  $("body").click(function() {
            $(".bottom__fixed__category").hide();
        });  --}}
    });
</script>
<script>
    $(document).ready(function() {
        $('.category-toggle').click(function() {
            var subcategory = $(this).closest('li').find('.subcategory');
            $('.subcategory').not(subcategory).slideUp();
            subcategory.slideToggle();
        });
    });
</script>
@extends('layouts.frontend')
@section('content-frontend')
    @include('frontend.common.maintenance')
    @push('css')
        <style>
            .gbtn,
            .button {
                display: inline-block;
                font-size: 14px;
                font-weight: 700;
                padding: 12px 30px;
                border-radius: 4px;
                color: #fff;
                border: 1px solid transparent;
                background-color: #FF384B;
                cursor: pointer;
                transition: all 300ms linear 0s;
                letter-spacing: 0.5px;
            }

            .fbtn,
            .button {
                display: inline-block;
                font-size: 14px;
                font-weight: 700;
                padding: 12px 30px;
                border-radius: 4px;
                color: #fff;
                border: 1px solid transparent;
                background-color: #316FF6;
                cursor: pointer;
                transition: all 300ms linear 0s;
                letter-spacing: 0.5px;
            }

            .social__auth button {
                border-radius: 5px;
                border: none;
                transition: all ease-in-out;
            }

            .social__auth {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .social__auth button:first-child {
                background: #bd081c;
            }

            .social__auth button:last-child {
                background: #1877f2;
            }

           .social__auth button a {
                font-size: 16px;
                transition: all .5s ease-in-out;
                font-weight: 700;
                color: #fff;
                width: 100%;
                padding: 15px 0;
                display: block;
            }
            
            .social__auth button:hover>a {
                color: #fff;
            }

            .form-group.row.najmul:hover>a {
                color: #fff !important;
            }

            .najmul:hover .social__auth button a {
                color: #fff;
            }
            .social__auth button {
                padding: 0;
                line-height: 1;
            }
        </style>
    @endpush
    @php
        $maintenance = getMaintenance();
    @endphp
    <main class="main pages">
        <div class="page-header breadcrumb-wrap">
            <div class="container">
                <div class="breadcrumb">
                    <a href="{{ route('home') }}" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
                    <span></span></span> Login
                </div>
            </div>
        </div>
        <div class="page-content section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8 col-lg-10 col-md-12 m-auto">
                        <div class="row">
                            {{-- <div class="col-lg-6 pr-30 d-none d-lg-block">
                            <img class="border-radius-15" src="{{asset('frontend/assets/imgs/page/login-1.png')}}" alt="" />
                    </div> --}}
                            <div class="col-lg-6 col-md-8 offset-lg-3 offset-md-4">
                                <div class="login_wrap widget-taber-content background-white najmul">
                                    <div class="padding_eight_all bg-white">
                                        <div class="heading_s1">
                                            <h1 class="mb-5">Login</h1>
                                            <p class="mb-30">Don't have an account? <a
                                                    href="{{ route('register') }}">Create here</a></p>
                                        </div>
                                        <form method="POST" action="{{ route('login') }}" class="row g-3 needs-validation najmul"
                                            novalidate>
                                            @csrf
                                            <div class="form-group">
                                                <input type="text" name="phone" placeholder="Phone *"  oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                    value="{{ old('phone') }}" autofocus />
                                                @error('email')
                                                    <div class="text-danger" style="font-weight: bold;">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <input type="password" name="password" placeholder="Your password *"
                                                    autocomplete="current-password" value="{{ old('password') }}" />
                                                @error('password')
                                                    <div class="text-danger" style="font-weight: bold;">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="login_footer form-group">
                                                <div class="chek-form">
                                                    <div class="custome-checkbox">
                                                        <input class="form-check-input" type="checkbox" name="checkbox"
                                                            id="exampleCheckbox1" value="" />
                                                        <label class="form-check-label"
                                                            for="exampleCheckbox1"><span>{{ __('Remember me') }}</span></label>
                                                    </div>
                                                </div>
                                                @if ($maintenance == 1)
                                                    <a class="text-muted" data-bs-toggle="modal"
                                                        data-bs-target="#maintenance">Forgot password?</a>
                                                @else
                                                    <a class="text-muted" href="{{ route('password.request') }}">Forgot
                                                        password?</a>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                @if ($maintenance == 1)
                                                    <button type="button" class="btn btn-heading btn-block hover-up"
                                                        data-bs-toggle="modal" data-bs-target="#maintenance"><i
                                                            class="fa-solid fa-arrow-right-to-bracket"></i>{{ __('Log in') }}</button>
                                                @else
                                                    <button type="submit" class="btn btn-heading btn-block hover-up"
                                                        name="login"><i class="fa-solid fa-arrow-right-to-bracket"></i>
                                                        {{ __('Log in') }}</button>
                                                @endif
                                            </div>

                                            <!--<p style="text-align: center;">OR</p>-->

                                            <!--<div class="form-group row ">-->
                                            <!--    <div class="col-md-6 offset-md-3">-->
                                            <!--        <div class="social__auth">-->
                                            <!--            <button>-->
                                            <!--                <a href="{{ url('login/google') }}">Login-->
                                            <!--                    with Google</a>-->
                                            <!--            </button>-->
                                            <!--            <button> <a href="{{ url('login/facebook') }}">Login-->
                                            <!--                    with Facebook</a></button>-->
                                            <!--        </div>-->
                                            <!--    </div>-->
                                            <!--</div>-->
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
@endsection
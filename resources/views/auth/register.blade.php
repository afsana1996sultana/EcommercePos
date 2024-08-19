@extends('layouts.frontend')
@section('content-frontend')
@include('frontend.common.maintenance')
@php
   $maintenance = getMaintenance();
@endphp
<main class="main pages">
    <div class="page-header breadcrumb-wrap">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{route('home')}}" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
                <span></span> My Account
            </div>
        </div>
    </div>
    <div class="page-content section-padding">
        <div class="container">
            <div class="row">
                        <div class="col-lg-6 col-md-8 offset-lg-3 mx-auto col-12">
                            <div class="login_wrap widget-taber-content background-white card p-3">
                                <div class="padding_eight_all bg-white">
                                    <div class="heading_s1 text-center">
                                        <h1 class="mb-5 fs-1">Create an Account</h1>
                                        <p class="mb-30">Already have an account? <a href="{{route('login')}}">Login</a></p>
                                    </div>
                                    <form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
                                        @csrf
                                        <div class="form-group">
                                            <label for="name" class="fw-900">Name : <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="only_en" placeholder="Name" id="name" value="{{ old('name') }}" />
                                            @error('name')
                                            <div class="text-danger" style="font-weight: bold;">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="username" class="fw-900">User Name : <span class="text-danger">*</span></label>
                                            <input type="text" name="username"  class="only_en" placeholder="Username" id="username" value="{{ old('username') }}" />
                                            @error('username')
                                            <div class="text-danger" style="font-weight: bold;">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="phone" class="fw-900">Phone Number : <span class="text-danger">*</span></label>
                                            <input type="number" name="phone" id="phone" placeholder="Phone Number" value="{{ old('phone') }}" />
                                            @error('phone')
                                            <div class="text-danger" style="font-weight: bold;">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="email" class="fw-900">Email : </label>
                                            <input type="email" name="email" id="email" placeholder="Email" value="{{ old('email') }}" />
                                            @error('email')
                                            <div class="text-danger" style="font-weight: bold;">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="password" class="fw-900">Password : <span class="text-danger">*</span></label>
                                            <input type="password" name="password" placeholder="Password" id="password" autocomplete="new-password" />
                                            <span>password must be at least 8 characters</span>
                                            @error('password')
                                            <div class="text-danger" style="font-weight: bold;">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="fw-900">Confirm password : <span class="text-danger">*</span></label>
                                            <input type="password" placeholder="Confirm password" name="password_confirmation" />
                                            @error('password')
                                            <div class="text-danger" style="font-weight: bold;">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="login_footer form-group">
                                            <div class="chek-form">
                                                <div class="custome-checkbox">
                                                    <input class="form-check-input" type="checkbox" name="checkbox" id="exampleCheckbox12" value="" />
                                                    <label class="form-check-label" for="exampleCheckbox12"><span>I agree to terms &amp; Policy.</span></label>
                                                </div>
                                            </div>
                                            {{-- <a href="#"><i class="fi-rs-book-alt mr-5 text-muted"></i>Lean more</a>  --}}
                                        </div>
                                        <div class="form-group mb-30">
                                            @if($maintenance==1)
                                                <button type="button" class="btn btn-fill-out btn-block hover-up font-weight-bold" data-bs-toggle="modal" data-bs-target="#maintenance" >Submit &amp; Register</button>
                                            @else
                                               <button type="submit" class="btn btn-fill-out btn-block hover-up font-weight-bold" name="login">Submit &amp; Register</button>
                                            @endif
                                        </div>
                                        <p class="font-xs text-muted"><strong>Note:</strong>Your personal data will be used to support your experience throughout this website, to manage access to your account, and for other purposes described in our privacy policy</p>
                                    </form>
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
<script>
     $(document).on('keyup', '.only_en', function() {
        var addressInput =$(this).val();
    if (!/^[a-zA-Z0-9.,\s]*$/.test(addressInput)) {
        alert("Please enter only English characters, numbers in the address field.");
        event.target.value = addressInput.replace(/[^\sa-zA-Z0-9.,]/g, ''); // Remove any non-English characters
    }
    });
</script>
@endsection
@extends('layouts.frontend1')
@section('title')
Contact Page
@endsection

<style>
    .contact__form input,
    .contact__form textarea {
        height: auto !important;
        border-radius: 0;
    }

</style>

@section('content-frontend')
<main class="main">
    <div class="container section-padding">
        <div class="map border p-4" style="border-radius: 5px">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3648.339906899896!2d90.39583417594537!3d23.87756268388145!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c573f4be7859%3A0xfe148d927ed0353a!2sClassic%20IT%20%26%20Sky%20Mart%20Ltd.!5e0!3m2!1sen!2sbd!4v1692877430279!5m2!1sen!2sbd" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>

    <div class="contact__form section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h4 class="contact__header border-bottom pb-2 mb-3">Write to US</h4>
                    <form action="{{ route('contact.store') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name <span style="color: #F05454">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Enter Name">
                            @error('name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email<span style="color: #F05454">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="Enter Email">
                            @error('email')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="phone">Mobile<span style="color: #F05454">*</span></label>
                            <input type="text" name="phone" class="form-control" placeholder="Enter Phone">
                        </div>
                        <div class="form-group">
                            <label for="message">Whats on your mind<span style="color: #F05454">*</span></label>
                            <textarea name="message" class="form-control" cols="30" rows="5" placeholder="feel free to ask any questions"></textarea>
                            @error('message')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success">Send Message</button>
                    </form>
                </div>
                <div class="col-lg-4">
                    <div class="contact__address">
                        <h4 class="contact__header border-bottom pb-2 mb-3">Contact US</h4>

                        <ul class="contact-infor">
                            <li><i class="fa-solid fa-location-dot"></i>Address: <span>{{ get_setting('business_address')->value ?? 'null' }}</span></li>
                            <li><i class="fa fa-phone"></i>Call Us:<a href="tel:{{ get_setting('phone')->value ?? 'null' }}">{{ get_setting('phone')->value ?? 'null' }}</a></li>
                            <li><i class="fa-regular fa-envelope"></i>Email: <a href="mailto:{{ get_setting('email')->value ?? 'null' }}">{{ get_setting('email')->value ?? 'null' }}</a></li>
                            <li><i class="fa fa-clock"></i>Hours:<span> {{ get_setting('business_hours')->value ?? 'null' }}</span></li>
                        </ul>

                        <div class="mobile-social-icon justify-content-center" style="margin-top: 20px">
                            <a target="_blank" href="{{ get_setting('facebook_url')->value ?? 'null' }}" title="Facebook"><img src="{{asset('frontend/assets/imgs/theme/icons/icon-facebook-white.svg')}}" alt="" /></a>
                            <a target="_blank" href="{{ get_setting('twitter_url')->value ?? 'null' }}" title="Twitter"><img src="{{asset('frontend/assets/imgs/theme/icons/icon-twitter-white.svg')}}" alt="" /></a>
                            <a target="_blank" href="{{ get_setting('instagram_url')->value ?? 'null' }}" title="Instagram"><img src="{{asset('frontend/assets/imgs/theme/icons/icon-instagram-white.svg')}}" alt="" /></a>
                            <a target="_blank" href="{{ get_setting('pinterest_url')->value ?? 'null' }}" title="Pinterest"><img src="{{asset('frontend/assets/imgs/theme/icons/icon-pinterest-white.svg')}}" alt="" /></a>
                            <a target="_blank" href="{{ get_setting('youtube_url')->value ?? 'null' }}" title="Youtube"><img src="{{asset('frontend/assets/imgs/theme/icons/icon-youtube-white.svg')}}" alt="" /></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

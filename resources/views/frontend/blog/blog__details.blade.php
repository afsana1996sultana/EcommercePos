@extends('layouts.frontend1')
@section('content-frontend')
<div class="page-header breadcrumb-wrap">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
            <span> {{ $blog__details->title_en }} </span>
        </div>
    </div>
</div>
<div class="container mb-30 mt-30">
    <div class="row">
        <div class="col-12 col-md-8">
            <div class="single-blog-post border p-3">
                <div class="blog-post-img">
                    <img src="{{ asset($blog__details->blog_img) }}" alt="">
                </div>
                <div class="blog-post-content">
                    <div class="blog-post-meta">
                        <ul>
                            <li><i class="fi-rs-calendar mr-5"></i>{{ $blog__details->created_at->format('M d, Y') }}
                            </li>
                        </ul>
                    </div>
                    <h4>{{ $blog__details->title_en }}</h4>
                    <p>{!! $blog__details->description !!}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <ul class="blog__sidebar">
                @foreach ($blogs as $blog)
                <li>
                    <a href="{{ route('blog.details',$blog->slug) }}">
                        <img src="{{ asset($blog->blog_img) }}" alt="">
                        <h6>{{ ucfirst($blog->title_en) }}</h6>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection

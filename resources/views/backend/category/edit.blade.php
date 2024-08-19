@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<section class="content-main">
    <div class="content-header">
        <h2 class="content-title">Edit Category</h2>
        <div class="">
            <a href="{{ route('category.index') }}" class="btn btn-primary"><i class="material-icons md-plus"></i>Back
                To List</a>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-sm-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form method="post" action="{{ route('category.update', $category->id) }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group row mb-4">
                                    <label class="col-md-3 col-form-label" for="name_en">Name English</label>
                                    <div class="col-md-9">
                                        <input type="text" placeholder="Name English" id="name_en" name="name_en"
                                            value="{{$category->name_en}}" class="form-control">
                                        @error('name_en')
                                        <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label class="col-md-3 col-form-label" for="name_bn">Name Bangla</label>
                                    <div class="col-md-9">
                                        <input type="text" placeholder="Name Bangla" id="name_bn" name="name_bn"
                                            value="{{$category->name_bn}}" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label class="col-md-3 col-form-label">Parent Category</label>
                                    <div class="col-md-9">
                                        <div class="custom_select">
                                            <select class="form-control select-active w-100 form-select select-nice"
                                                name="parent_id" id="parent_id"
                                                data-selected="{{ $category->parent_id }}">
                                                <option value="0">No Parent</option>
                                                @foreach ($categories as $acategory)
                                                <option value="{{ $acategory->id }}" @if($acategory->
                                                    id==$category->parent_id) selected @endif>{{ $acategory->name_en }}
                                                </option>
                                                @foreach ($acategory->childrenCategories as $childCategory)
                                                @include('backend.include.child_category', ['child_category' =>
                                                $childCategory])
                                                @endforeach
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="form-group row mb-4">
                                    <label class="col-md-3 col-form-label" for="type">
                                       Type
                                    </label>
                                    <div class="col-md-9">
                                        <input type="number" name="type" class="form-control" id="type" placeholder="Type Level" value="{{$category->type}}">
                                        <small>Higher type has high priority</small>
                                    </div>
                                </div> -->
                                <div class="form-group row mb-4">
                                    <label class="col-md-3 col-form-label" for="description_en">Description
                                        English</label>
                                    <div class="col-md-9">
                                        <textarea name="description_en" rows="5"
                                            class="form-control">{{$category->description_en}}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label class="col-md-3 col-form-label" for="description_bn">Description
                                        Bangla</label>
                                    <div class="col-md-9">
                                        <textarea name="description_bn" rows="5"
                                            class="form-control">{{$category->description_bn}}</textarea>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="image" class="col-form-label" style="font-weight: bold;">Cover
                                        Photo:</label>
                                    <input name="image" class="form-control" type="file" id="image">
                                </div>
                                <div class="mb-4">
                                    <img id="showImage" class="rounded avatar-lg" src="{{ asset($category->image) }}"
                                        alt="Card image cap" width="100px" height="80px;">
                                </div>

                                <div class="product__type d-flex flex-wrap">
                                    <div class="mb-4">
                                        <div class="demo-checkbox" style="margin-right:10px;">
                                            <input type="checkbox" id="md_checkbox_29" class="form-check-input cursor"
                                                name="is_featured" {{ $category->is_featured == 1 ? 'checked': '' }}
                                            value="1">
                                            <label for="md_checkbox_29" class="form-check-label cursor"
                                                style="font-weight: bold; padding-left: 8px;"> Is Featured</label>
                                        </div>
                                    </div>
                                    <div class="trending__system">
                                        <div class="demo-checkbox" style="margin-right:10px">
                                            <input type="checkbox" id="trending" class="form-check-input cursor"
                                                name="trending" {{ $category->trending == 1 ? 'checked': '' }}
                                            value="1">
                                            <label for="trending" class="form-check-label cursor"
                                                style="font-weight: bold; padding-left: 8px;">Is Trending</label>
                                        </div>
                                    
                                        <div class="trending__image"
                                            style="display: {{ $category->trending == 1 ? 'block' : 'none' }}">
                                            <h5>Banner</h5>
                                            <input type="file" id="image-upload" name="banner_image"
                                                class="form-control" style="display: none;">
                                    
                                            <img src="{{ $category->banner_image ? asset($category->banner_image) : asset('upload/no_image.jpg') }}"
                                                data-image-url="{{ $category->banner_image ? asset($category->banner_image) : asset('upload/no_image.jpg') }}"
                                                style="width: 100px" id="image-preview" alt="Banner Image">
                                        </div>
                                    </div>
                                                                        

                                    <div class="mb-4" style="margin-right:10px;">
                                        <div class="demo-checkbox">
                                            <input type="checkbox" id="md_checkbox_31" class="form-check-input cursor"
                                                name="special" {{ $category->special == 1 ? 'checked': '' }} value="1">
                                            <label for="md_checkbox_31" class="form-check-label cursor"
                                                style="font-weight: bold; padding-left: 8px;"> Is Special</label>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="form-check-input me-2 cursor" name="status"
                                                id="status" {{ $category->status == 1 ? 'checked': '' }} value="1">
                                            <label class="form-check-label cursor" for="status">Status</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4 justify-content-sm-end">
                                    <div class="col-lg-3 col-md-4 col-sm-5 col-6">
                                        <input type="submit" class="btn btn-primary" value="Update">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- .row // -->
                </div>
                <!-- card body .// -->
            </div>
            <!-- card .// -->
        </div>
    </div>
</section>
<script>
    $(document).ready(function() {
        $('#trending').on('click', function() {
            if ($(this).is(':checked')) {
                $('.trending__image').show();
            } else {
                $('.trending__image').hide();
            }
        });
    });
</script>
<script>
    // Get references to the checkbox and image elements
    const trendingCheckbox = document.getElementById('trending');
    const trendingImage = document.querySelector('.trending__image');
    const imagePreview = document.getElementById('image-preview');
    const imageUpload = document.getElementById('image-upload');

    // Add an event listener to the checkbox to toggle the visibility of the image div
    trendingCheckbox.addEventListener('change', function () {
        trendingImage.style.display = this.checked ? 'block' : 'none';

        // Update the image source based on the data attribute
        if (this.checked) {
            imagePreview.src = imagePreview.getAttribute('data-image-url');
        } else {
            imagePreview.src = "{{ asset('upload/no_image.jpg') }}";
        }
    });

    // Add an event listener to the image to allow changing the banner image
    imagePreview.addEventListener('click', function () {
        imageUpload.click();
    });

    // Add an event listener to the file input to handle image selection
    imageUpload.addEventListener('change', function () {
        const selectedFile = this.files[0];

        if (selectedFile) {
            // Assuming you want to preview the selected image
            const objectURL = URL.createObjectURL(selectedFile);
            imagePreview.src = objectURL;
        } else {
            // No file selected, you can handle this case as needed
        }
    });
</script>
@endsection
@extends('admin.admin_master')
@section('admin')
<section class="content-main">
    <div class="content-header">
        <h3 class="content-title">Blog list <span class="badge rounded-pill alert-success"> {{ count($blogs) }} </span></h3>
        <div>
            @if(Auth::guard('admin')->user()->role == '1' || in_array('22', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
              <a href="{{ route('blog.create') }}" class="btn btn-primary"><i class="material-icons md-plus"></i>Blog Create</a>
            @endif
        </div>
    </div>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <div class="table-responsive-sm">
                <table id="example" class="table table-bordered table-striped" width="100%">
                    <thead>
                        <tr>
                            <th scope="col">Sl</th>
                            <th scope="col">blog Photo</th>
                            <th scope="col">Title (English)</th>
                            <th scope="col">Title (Bangla)</th>
                            <th scope="col">Description</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($blogs as $key => $blog)
                        <tr>
                            <td> {{ $key+1}} </td>
                            <td width="15%">
                                <a href="#" class="itemside">
                                    <div class="left">
                                        <img src="{{ asset($blog->blog_img) }}" class="img-sm img-avatar" alt="Userpic" />
                                    </div>
                                </a>
                            </td>
                            <td> {{ $blog->title_en ?? 'NULL' }} </td>
                            <td> {{ $blog->title_bn ?? 'NULL' }} </td>
                            <td>{!! Str::words(strip_tags($blog->description), 50) !!}</td>
                            <td>
                                @if($blog->status == 1)
                                <a href="{{ route('blog.in_active',['id'=>$blog->id]) }}">
                                    <span class="badge rounded-pill alert-success">Active</span>
                                </a>
                                @else
                                <a href="{{ route('blog.active',['id'=>$blog->id]) }}"> <span class="badge rounded-pill alert-danger">Disable</span></a>
                                @endif
                            </td>
                            <td class="text-end">
                                {{-- <a href="#" class="btn btn-md rounded font-sm">Detail</a> --}}
                                <div class="dropdown">
                                    <a href="#" data-bs-toggle="dropdown" class="btn btn-light rounded btn-sm font-sm"> <i class="material-icons md-more_horiz"></i> </a>
                                    <div class="dropdown-menu">
                                        @if(Auth::guard('admin')->user()->role == '1' || in_array('23', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                                        <a class="dropdown-item" href="{{ route('blog.edit',$blog->id) }}">Edit info</a>
                                        @endif
                                        @if(Auth::guard('admin')->user()->role == '1' || in_array('24', json_decode(Auth::guard('admin')->user()->staff->role->permissions)))
                                        <a class="dropdown-item text-danger" href="{{ route('blog.delete',$blog->id) }}" id="delete">Delete</a>
                                        @endif
                                    </div>
                                </div>
                                <!-- dropdown //end -->
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- table-responsive //end -->
        </div>
        <!-- card-body end// -->
    </div>
</section>
@endsection

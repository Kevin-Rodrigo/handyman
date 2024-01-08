@extends('adminmodule::layouts.master')

@section('title',translate('Blogs'))

@push('css_or_js')

@endpush

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <div class="page-title-wrap d-flex justify-content-between flex-wrap align-items-center gap-3 mb-3">
            <div>
                <a href="{{route('admin.blog.create')}}" class="btn btn--primary">
                    <span class="material-icons">add</span>
                    Add blog
                </a>
            </div>
        </div>
        <div class="container-fluid">
            <div class="page-title-wrap mb-30">
                <h2 class="page-title">{{translate('Blog_List')}}</h2>
            </div>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-tab-pane">
                    <div class="card">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table id="example" class="table align-middle">
                                    <thead class="align-middle">
                                    <tr>
                                        <th>{{translate('ID')}}</th>
                                        <th>{{translate('Title')}}</th>
                                        <th>{{translate('Image')}}</th>
                                        <th>{{translate('Created Date')}}</th>
                                        <th>{{translate('Action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($blogs as $blog)
                                    <tr>
                                        <td>{{$blog->id}}</td>
                                        <td>
                                            <p>{{$blog->title}}</p>
                                        </td>
                                        <td>
                                            <div class="media align-items-center gap-3">
                                                <div class="avatar avatar-lg">
                                                    <img class="avatar-img radius-5"
                                                        src="{{asset('storage/app/public/provider/logo')}}/{{$blog->image}}"
                                                        onerror="this.src='{{asset('public/assets/admin-module')}}/img/placeholder.png'"
                                                        alt="">
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td>{{$blog->created_at->format('d,M,Y')}}</td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="{{route('admin.blog.delete', $blog->id)}}"
                                                        class="table-actions_delete bg-transparent border-0 p-0">
                                                    <span class="material-icons">delete</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Main Content -->
@endsection

@push('script')


@endpush

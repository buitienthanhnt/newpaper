@extends('adminhtml.layouts.body_main')

@section('body_top_tab')
    @include('adminhtml.layouts.body_top_tab')
@endsection

@section('body_overview')
    @include('adminhtml.layouts.body_overview')
@endsection

@section('admin_title')
    paper list
@endsection

@section('after_css')
    <style>
        .table th,
        .table td {
            line-height: inherit
        }
    </style>
@endsection

@section('head_js_after')
@endsection

@section('body_main_conten')
    <div class="row">
        <div class="col-md-12 flex-row">
            <span class="btn btn-primary">setting paper info</span>
            <div class="dropdown col-md-2 d-inline">
                <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    new paper
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{ route('admin_paper_create') }}">default conten</a>
                    <a class="dropdown-item" href="{{ route('admin_paper_create', ['type' => 'product']) }}">product page</a>
                    <a class="dropdown-item" href="{{ route('admin_paper_create', ['type' => 'carousel']) }}">carousel image</a>
                    <a class="dropdown-item" href="{{ route('admin_new_by_url') }}">new by url</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <td>title</td>
                            <td>show</td>
                            <td>image</td>
                            <td>writer</td>
                            <td>category</td>
                            <td>action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($page_lists)
                            @foreach ($page_lists as $page)
                                <tr>
                                    <td>{{ $page->title }}</td>
                                    <td>{{ $page->active && $page->show ? 'yes' : 'no' }}</td>
                                    <td>
                                        <img src="{{ $page->getImagePath() }}" alt="" style="width: 100px; height: 100px;">
                                    </td>
                                    <td>
                                        @if ($page->writer)
                                            {{ $page->to_writer()->getResults()->name }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($page->categories)
                                            @foreach ($page->categories as $category)
                                                <span style="margin-bottom: 10px !important">{{ $category->name }}</span><br>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-info" href="{{ route('admin_paper_edit', ['paper_id' => $page->id]) }}">
                                            edit
                                        </a>
                                        <a href="#" class="btn btn-danger btn-flat show_confirm" data-id="{{ $page->id }}">
                                            delete
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endisset
                    </tbody>
                </table>
            </div>

            <div class="row" style="float: left">
                <div class="col-md-12 mt-20 d-flex flex-row-reverse">
                    {{ $page_lists->links() }}
                </div>

            </div>

        </div>
    </div>
@endsection

@section('before_bottom_js')
@endsection

@section('after_js')
    <script type="text/javascript">
        var token = "{{ csrf_token() }}";
        $('.show_confirm').click(function(event) {
            var id = $(this).attr("data-id");
            var url = "{{ route('admin_paper_delete') }}";

            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    Swal.fire({
                            title: 'Please Wait !',
                            html: 'data uploading', // add html attribute if you want or remove
                            allowOutsideClick: false,
                            onBeforeOpen: () => {
                                Swal.showLoading()
                            },
                        });
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        contentType: 'application/json',
                        data: JSON.stringify({
                            _token: token,
                            paper_id: id
                        }),
                        success: function(result) {
                            if (result) {
                                var data = JSON.parse(result);
                                if (data.code == 200) {
                                    Swal.fire({
                                        position: 'center',
                                        type: 'success',
                                        title: data.value,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    $(this).parent().parent().parent().remove();
                                } else {
                                    Swal.fire({
                                        position: 'center',
                                        type: 'warning',
                                        title: data.value,
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                }
                            }
                        }.bind(this),
                        error: function(e) {
                            Swal.fire({
                                position: 'center',
                                type: 'warning',
                                title: "can not delete, please try again.",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    })
                }
            });
            return;
        });
    </script>
@endsection

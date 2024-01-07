@extends('adminhtml.layouts.body_main')

@section('body_top_tab')
    @include('adminhtml.layouts.body_top_tab')
@endsection

@section('body_overview')
    @include('adminhtml.layouts.body_overview')
@endsection

@section('admin_title')
    firebase dashboard
@endsection

@section('after_css')
@endsection

@section('head_js_after')
@endsection

@section('body_main_conten')
    <span>day la noi dung cua firebase dahboard</span>
    <table class="table table-striped table-inverse table-responsive">
        <thead class="thead-inverse">
            <tr>
                <th>id</th>
                <th>title</th>
                <th>image</th>
                <th>action</th>
            </tr>
        </thead>
        <tbody>
            @isset($listPaper)
                @foreach ($listPaper as $key => $item)
                    <tr>
                        <td scope="row">{{ $key }}</td>
                        <td>{{ $item['title'] }}</td>
                        <td>
                            <img src="{{ $item['image_path'] }}"
                                class="img-fluid ${3|rounded-top,rounded-right,rounded-bottom,rounded-left,rounded-circle}"
                                alt="">
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger fa-pull-right delete_firebase"
                                data-id="{{ $key }}">delete</button>

                            <a type="button" href="{{ route('admin_paper_edit', ['paper_id' => $item['id']]) }}"
                                class="btn btn-warning fa-pull-right">view</a>
                        </td>
                    </tr>
                @endforeach

                @foreach ($papers as $paper)
                    <tr>
                        <td scope="row">{{ $paper->id }}</td>
                        <td>{{ $paper->title }}</td>
                        <td>
                            <img src="{{ $paper->image_path }}"
                                class="img-fluid ${3|rounded-top,rounded-right,rounded-bottom,rounded-left,rounded-circle}"
                                alt="">
                        </td>
                        <td>
                            <button type="button" class="btn btn-info up_firebase" data-id="{{ $paper->id }}">up
                                firebase</button>

                            <a type="button" href="{{ route('admin_paper_edit', ['paper_id' => $paper->id]) }}"
                                class="btn btn-warning fa-pull-right">view</a>
                        </td>
                    </tr>
                @endforeach
            @endisset


        </tbody>
    </table>
    <div class="row" style="float: left">
        <div class="col-md-12 mt-20 d-flex flex-row-reverse">
            {{ $papers->links() }}
        </div>

    </div>
@endsection

@section('before_bottom_js')
@endsection

@section('after_js')
    <script type="text/javascript">
        $(document).ready(function() {
            let _token = "{{ csrf_token() }}";
            let addUrl = "{{ route('admin_firebase_addPaper') }}";
            $('.up_firebase').click(function() {

                let paperId = $(this).attr('data-id');
                if (paperId) {
                    Swal.fire({
                        title: 'Please Wait !',
                        html: 'data uploading', // add html attribute if you want or remove
                        allowOutsideClick: false,
                        onBeforeOpen: () => {
                            Swal.showLoading()
                        },
                    });
                    // swal.close();

                    $.ajax({
                        url: addUrl,
                        type: "POST",
                        contentType: 'application/json',
                        data: JSON.stringify({
                            _token: _token,
                            paper_id: paperId
                        }),
                        success: function(result) {
                            var data = JSON.parse(result);
                            if (data.code == 200) {
                                console.log(data);
                                Swal.fire({
                                    position: 'center',
                                    type: 'success',
                                    title: 'added the paper to firebase',
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                                $(this).addClass('btn-danger').removeClass('up_firebase').addClass('delete_firebase').html('delete').attr('data-id', data.data);
                            }

                            if (data.code == 400) {
                                Swal.fire({
                                    position: 'center',
                                    type: 'error',
                                    title: 'can`t added the paper to firebase',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }

                    }.bind(this),
                    error: function(error) {
                        Swal.fire({
                            position: 'center',
                            type: 'error',
                            title: 'can`t added the paper to firebase',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }
                    })
                }
            })

            $('.delete_firebase').click(function() {
                let sourceId = $(this).attr('data-id');
                let deleteUrl = "{{ route('admin_firebase_deletePaper') }}";
                if (sourceId) {
                    Swal.fire({
                        title: 'Please Wait !',
                        html: 'remove running !!!', // add html attribute if you want or remove
                        allowOutsideClick: false,
                        onBeforeOpen: () => {
                            Swal.showLoading()
                        },
                    });
                    $.ajax({
                        url: deleteUrl,
                        type: "DELETE",
                        contentType: 'application/json',
                        data: JSON.stringify({
                            _token: _token,
                            paper_id: sourceId
                        }),
                        success: function(result) {
                            var data = JSON.parse(result);
                            if (data.code == 200) {
                                Swal.fire({
                                    position: 'center',
                                    type: 'success',
                                    title: 'remove the paper in firebase',
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                                $(this).parent().parent().remove();
                            }

                            if (data.code == 400) {
                                Swal.fire({
                                    position: 'center',
                                    type: 'error',
                                    title: 'can`t remove the paper in firebase',
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }
                        }.bind(this),
                        error: function(error) {

                        }
                    })
                }
            })
        })
    </script>
@endsection

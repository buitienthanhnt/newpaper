@extends('adminhtml.layouts.body_main')

@section('body_top_tab')
    @include('adminhtml.layouts.body_top_tab')
@endsection

@section('body_overview')
    @include('adminhtml.layouts.body_overview')
@endsection

@section('admin_title')
    writer list
@endsection


@section('body_main_conten')
    @if ($message = session('success'))
        <?php alert()->success('server message', $message); ?>
    @endif
    <div class="container">
        <div class="row">
            <a href="{{ route('admin_writer_create') }}">
                <button class="btn btn-info">create writer</button>
            </a>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <td>name</td>
                            <td>email</td>
                            <td>alias</td>
                            <td>image</td>
                            <td>active</td>
                            <td>action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($all_writer)
                            @foreach ($all_writer as $writer)
                                <tr>
                                    <td>{{ $writer->name }}</td>
                                    <td>{{ $writer->email }}</td>
                                    <td>{{ $writer->alias }}</td>
                                    <td>
                                        <img src="{{ $writer->image_path }}" style="width: 100px; height: 100px;"
                                            alt="">
                                    </td>
                                    <td>{{ $writer->active ? 'active' : 'inactive' }}</td>
                                    <td>
                                        <a href="">
                                            <button class="btn btn-info">edit</button>
                                        </a>
                                        <a href="{{ route('admin_writer_delete') }}" id="{{ $writer->id }}">
                                            <button class="btn btn-danger btn-flat show_confirm">delete</button>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-md-12 mt-20 d-flex flex-row-reverse">
                    {{ $all_writer->links() }}
                </div>

            </div>
        </div>
    </div>

    <script type="text/javascript">
        $('.show_confirm').click(function(event) {
            var form = $(this).closest("form");
            var name = $(this).data("name");
            var url = "{{ route('admin_writer_delete') }}";
            event.preventDefault();
            swal({
                    title: `Are you sure you want to delete this record?`,
                    text: "If you delete this, it will be gone forever.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    window.location.href = url;
                    // $("#2").trigger("click");
                    // console.log(willDelete);
                    // var xhr = new XMLHttpRequest();
                    // xhr.open("POST", url, true);
                    // xhr.setRequestHeader('Content-Type', 'application/json');
                    // xhr.send();

                    // fetch(url, {
                    //     method: 'POST',
                    //     headers: {
                    //         'Accept': 'application/json',
                    //         'Content-Type': 'application/json'
                    //     },
                    //     body: JSON.stringify({
                    //         "id": 78912
                    //     })
                    // })
                });
        });
    </script>
@endsection

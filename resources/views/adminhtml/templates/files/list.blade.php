@extends('adminhtml.layouts.body_main')

@section('body_top_tab')
    @include('adminhtml.layouts.body_top_tab')
@endsection

@section('body_overview')
    @include('adminhtml.layouts.body_overview')
@endsection

@section('admin_title')
    file add
@endsection

@section('body_main_conten')
    @if ($message = session('success'))
        <?php alert()->success('server message', $message); ?>
    @endif

    <div class="container">
        <div class="row">
            <a href="{{ route('admin_file_add') }}">
                <button class="btn btn-info">add new file</button>
            </a>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <td>file name</td>
                            <td>file path</td>
                            <td>resize path</td>
                            <td>action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($list_file)
                            @foreach ($list_file as $file)
                                <tr>
                                    <td>{{ $file->file_name }}</td>
                                    <td>
                                        <img src="{{ $file->file_url }}" style="width: 320px; height: 240px; border-radius: 0px" alt="">
                                    </td>
                                    <td>
                                        <img src="{{ $file->file_url }}" style="width: 100px; height: 100px; border-radius: 0px" alt="">
                                    </td>
                                    <td>
                                        <a >
                                            <button class="btn btn-info">edit</button>
                                        </a>
                                        <a href="">
                                            <button class="btn btn-danger btn-flat show_confirm"
                                                data-id="">delete</button>
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
                    {{ $list_file->links() }}
                </div>

            </div>
        </div>
    </div>
@endsection

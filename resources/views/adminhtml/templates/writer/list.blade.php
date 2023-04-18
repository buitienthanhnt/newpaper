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
    <div class="container">
        <div class="row">
            <a href="{{ route('admin_writer_create') }}">
                <button class="btn btn-info">create writer</button>
            </a>
            
            <div class="col-md-12">
                <p>writer list admin</p>
            </div>
        </div>
    </div>

    <script>
        
    </script>
@endsection

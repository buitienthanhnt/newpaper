@extends('adminhtml.layouts.body_main')

@section('body_top_tab')
    @include('adminhtml.layouts.body_top_tab')
@endsection

@section('body_overview')
    @include('adminhtml.layouts.body_overview')
@endsection

@section('admin_title')
    setup config
@endsection

@section('after_css')
    <style type="text/css">
        .select2-container--default .select2-selection--multiple .select2-selection__choice__display {
            font-size: medium
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            font-size: medium !important
        }
    </style>
@endsection

@section('body_main_conten')
    <div class="container">
        <div class="col-md-10">
            <form action="{{ route('admin_config_insert') }}" method="post">
                @csrf
                @if ($allOfCoreConfig)
                    @foreach ($allOfCoreConfig as $item)
                        <div class="form-group">
                          <label>{{ $item->name }}</label>
                          <input type="text" class="form-control" name="" aria-describedby="helpId" value="{{ $item->value }}" disabled>
                          <small id="helpId" class="form-text text-muted">{{ $item->description }}</small>
                        </div>
                    @endforeach
                @endif
            </form>
        </div>
    </div>
@endsection

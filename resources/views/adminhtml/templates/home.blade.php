@extends('adminhtml.layouts.body_main')

@section('body_top_tab')
    @include('adminhtml.layouts.body_top_tab')
@endsection

@section('body_overview')
    @include('adminhtml.layouts.body_overview')
@endsection

@section('body_main_conten')
    @if (session("not_page"))
        <?php Alert::error("not page", 'Message')->autoClose(2000) ?>
    @endif
    <span>noi dung trong body main conten</span>
@endsection

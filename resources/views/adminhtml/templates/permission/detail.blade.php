@extends('adminhtml.layouts.body_main')

@section('body_top_tab')
    @include('adminhtml.layouts.body_top_tab')
@endsection

@section('body_overview')
    @include('adminhtml.layouts.body_overview')
@endsection

@section('admin_title')
    permission detail
@endsection

@section('head_js_after')
    <script src={{ asset('assets/adminhtml/json-hierarchical-tree-picker/jquery.simple-tree-picker.js') }}></script>
@endsection
    <link rel="stylesheet" href={{asset('assets/adminhtml/json-hierarchical-tree-picker/jquery.simple-tree-picker.css')}}>
@section('after_css')

@endsection

@section('body_main_conten')
	<p>detail of permission</p>
@endsection

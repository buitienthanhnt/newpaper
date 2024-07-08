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
        <div class="col-md-12">
            <span>noi dung cua order detail</span>
            <div class="card">
                <div class="card-body">
                  <h5 class="card-title">nguoi mua: {{ $order->name }}</h5>
                  <h6 class="card-subtitle mb-2 text-muted">email: {{ $order->email }}</h6>
                  <h6 class="card-subtitle mb-2 text-muted">sdt: {{ $order->phone }}</h6>
                  <h6 class="card-subtitle mb-2 text-muted">ngay tao: {{ $order->created_at }}</h6>
                  <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>

                  <ul class="list-group">
                    @foreach ($order->orderItems() as $item)
                    <li class="list-group-item">
                        <div>
                            <p>paper name: {{$item->title}}</p>
                            <p>gia: {{$item->price}}</p>
                            <p>so luong: {{$item->qty}}</p>
                        </div>
                    </li>
                    @endforeach
                  </ul>

                  <a href="#" class="text-success mr-5">{{ $order->status }}</a>
                  <a href="#" class="card-link">Another link</a>
                </div>
              </div>
        </div>
    </div>
@endsection

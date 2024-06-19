@extends('frontend.layouts.pagestruct')

@section('page_top_head')
    @include('frontend.templates.page_top_head')
    {{-- <script src="{{ asset('assets/frontend/js/commentReply.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/commentHistory.js') }}"></script> --}}
@endsection

@section('page_header')
    <!-- Preloader Start -->
    @include('frontend.templates.page_header')
@endsection

@section('page_footer')
    @include('frontend.templates.page_footer')
@endsection

@section('title')
    cart detail
@endsection

@section('main_conten')
    <div class="container">
        <div class="row py-2">
            <div class="col-md-12">
                <table class="table table-light">
                    <tbody>
                        <tr>
                            <td>tên sp</td>
                            <td>giá tiền</td>
                            <td>số lượng</td>
                            <td>xóa</td>
                        </tr>
                        @foreach ($cart as $k => $item)
                            <tr>
                                <td>{{ $item['title'] }}</td>
                                <td>{{ number_format($item['price']) }} vnđ</td>
                                <td>{{ $item['qty'] }}</td>
                                <td><a href="{{ route('paper_xoaItem', ['id' => $k]) }}" class="text-primary">xóa</a></td>
                            </tr>
                        @endforeach
                        @if ($cart)
                            <tr>
                                <td>tổng hợp</td>
                                <td>{{ number_format(array_sum(array_map(fn($i) => $i['price'] * $i['qty'], $cart))) }}
                                    vnđ</td>
                                <td>{{ array_sum(array_column($cart, 'qty')) }}</td>
                                <td></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="col-md-12">
                <a class="btn float-right" href="{{ route('paper_clearCart') }}">clear cart</a>
            </div>
        </div>
    </div>
@endsection

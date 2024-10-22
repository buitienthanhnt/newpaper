@extends('frontend.layouts.pagestruct')

@section('page_top_head')
    @include('frontend.templates.page_top_head')
@endsection

@section('page_header')
    <!-- Preloader Start -->
    @render(\App\ViewBlock\TopBar::class)
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
            @if (!empty($cart) && count($cart->getItems()))
                <div class="col-md-12">
                    <a class="text-primary"href="{{ route('front_paper_by_type', ['type' => "price"]) }}">Danh sách sản phẩm</a>
                    <table class="table table-light">
                        <tbody>
                            <tr>
                                <td>tên sp</td>
                                <td>giá tiền</td>
                                <td>số lượng</td>
                                <td>xóa</td>
                            </tr>
                            @foreach ($cart->getItems() as $item)
                                <tr>
                                    <td>{{ $item->getValueTitle(0) }}</td>
                                    <td>{{ $item->getValuePriceFormat() }} vnđ</td>
                                    <td>{{ $item->getQty() }}</td>
                                    <td><a href="{{ route('front_delete_cart_item', ['item_id' => $item->getValueId()]) }}" class="text-primary">xóa</a></td>
                                </tr>
                            @endforeach
                            @if ($cart)
                                <tr>
                                    <td>tổng hợp</td>
                                    <td>
                                        {{ number_format($cart->getTotals()) }}
                                        vnđ
                                    </td>
                                    <td>{{ $cart->getCount() }}</td>
                                    <td></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 d-flex justify-content-between">
                    <a class="btn float-right" href="{{ route('front_clear_cart') }}">clear cart</a>
                    <a class="btn float-right" href="{{ route('front_checkout') }}">checkout</a>
                </div>
            @else
                <p class="">Bạn chưa có sản phẩm nào trong giỏ hàng!!
                    <a class="text-primary"href="{{ route('front_paper_by_type', ['type' => "price"]) }}">click here to shopping</a>
                </p>
            @endif
        </div>
    </div>
@endsection

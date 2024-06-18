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
        <div class="row">
            <div class="col-md-12">
                <table class="table table-light">
                    <tbody>
                        <tr>
                            <td>tên sp</td>
                            <td>giá tiền</td>
                            <td>số lượng</td>
                        </tr>
                        @foreach ($cart as $item)
                            <tr>
                                <td>{{ $item['title'] }}</td>
                                <td>{{ $item['price'] }} vnd</td>
                                <td>{{ $item['qty'] }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td>tổng tiền</td>
                            <td>{{ array_sum(array_column($cart, 'price')) }} vnđ</td>
                            <td></td>
                        </tr>

                    </tbody>
                </table>
            </div>
            <div class="col-md-2">
                <a class="btn" href="{{ route('paper_clearCart') }}">clear cart</a>
            </div>
        </div>
    </div>
@endsection

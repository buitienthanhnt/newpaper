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
            @if (count($cart))
                <div class="col-md-6">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('paper_checkoutPro') }}">
                        @csrf
                        <div class="form-group">
                            <label for="exampleFormControlInput2">Người mua:</label>
                            <input type="text" class="form-control" required name="name" id="exampleFormControlInput2"
                                placeholder="lee thanh tu">
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Email:</label>
                            <input type="email" class="form-control" required name="email" id="exampleFormControlInput1"
                                placeholder="name@example.com">
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput3">sđt liên hệ:</label>
                            <input type="tel" class="form-control" required name="phone" id="exampleFormControlInput3"
                                placeholder="0702032201">
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlInput4">Lựa chọn giao hàng:</label>
                            <div class="form-check">
                                <input class="form-check-input addressCheck" type="checkbox" name="addressHc"
                                    id="defaultCheck1">
                                <label class="form-check-label" for="defaultCheck1">
                                    Giao giờ hành chính:
                                </label>
                                <div class="form-address" style="display: none">
                                    <input type="address1" class="form-control" name="shipHc" id="address1"
                                        placeholder="địa chỉ nhận giờ hành chính">
                                </div>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input addressCheck" type="checkbox" name="addressNhc"
                                    id="defaultCheck2">
                                <label class="form-check-label" for="defaultCheck2">
                                    Giao ngoài giờ hành chính:
                                </label>
                                <div class="form-address" style="display: none">
                                    <input type="address2" class="form-control" name="shipNhc" id="address2"
                                        placeholder="địa chỉ nhận ngoài giờ hành chính">
                                </div>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input addressCheck" type="checkbox" name="addressStore"
                                    id="defaultCheck3">
                                <label class="form-check-label" for="defaultCheck1">
                                    Nhận tại cửa hàng:
                                </label>
                                <div class="form-address" style="display: none">
                                    <input disabled type="address1" class="form-control" name="shipStore" id="address3"
                                        value="nam tân, trực nội">
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <button class="btn float-right" type="submit">Đặt hàng</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    {{-- <a class="text-primary"href="{{ route('paper_byType', ['type' => "product"]) }}">Danh sách sản phẩm</a> --}}
                    <table class="table table-light">
                        <tbody>
                            <tr>
                                <td>sản phẩm</td>
                                <td>giá tiền</td>
                            </tr>
                            @foreach ($cart as $k => $item)
                                <tr>
                                    <td>{{ $item['title'] }}</td>
                                    <td>{{ number_format($item['price']) }} vnđ</td>
                                </tr>
                            @endforeach
                            @if ($cart)
                                <tr>
                                    <td>tổng tiền</td>
                                    <td>
                                        {{ number_format(array_sum(array_map(fn($i) => $i['price'] * $i['qty'], $cart))) }}
                                        vnđ
                                    </td>
                                    <td></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            @else
                <p class="">Bạn chưa có sản phẩm nào trong giỏ hàng!!
                    <a class="text-primary"href="{{ route('paper_byType', ['type' => 'product']) }}">click here to
                        shopping</a>
                </p>
            @endif
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $(".addressCheck").click(function(event) {
                let checkInbox = $(this).prop('checked');
                if (checkInbox) {
                    $($(this).siblings('.form-address')[0]).show();
                } else {
                    $($(this).siblings('.form-address')[0]).hide();
                }
            })
        })
    </script>
@endsection

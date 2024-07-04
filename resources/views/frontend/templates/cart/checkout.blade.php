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
                            <label for="name_order">Người mua:</label>
                            <input type="text" class="form-control" required name="name" id="name_order"
                                placeholder="lee thanh tu">
                        </div>

                        <div class="form-group">
                            <label for="email_order">Email:</label>
                            <input type="email" class="form-control" required name="email" id="email_order"
                                placeholder="abc@example.com">
                        </div>

                        <div class="form-group">
                            <label for="phone_order">sđt liên hệ:</label>
                            <input type="tel" class="form-control" required name="phone" id="phone_order"
                                placeholder="0702032201">
                        </div>

                        <div class="form-group">
                            <label>Lựa chọn giao hàng:</label>

                            <div class="form-check">
                                <input class="form-check-input addressCheck" type="checkbox" name="ship_hc">
                                <label class="form-check-label">
                                    Giao giờ hành chính:
                                </label>
                                <div class="form-address" style="display: none">
                                    <input type="address1" class="form-control" name="address_hc"
                                        placeholder="địa chỉ nhận giờ hành chính">
                                </div>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input addressCheck" type="checkbox" name="ship_nhc">
                                <label class="form-check-label">
                                    Giao ngoài giờ hành chính:
                                </label>
                                <div class="form-address" style="display: none">
                                    <input type="text" class="form-control" name="address_nhc"
                                        placeholder="địa chỉ nhận ngoài giờ hành chính">
                                </div>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input addressCheck" type="checkbox" name="ship_store">
                                <label class="form-check-label">
                                    Nhận tại cửa hàng:
                                </label>
                                <div class="form-address" style="display: none">
                                    <input disabled type="text" class="form-control" name="shipStore"
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

@extends('adminhtml.layouts.body_main')

@section('body_top_tab')
    @include('adminhtml.layouts.body_top_tab')
@endsection

@section('body_overview')
    @include('adminhtml.layouts.body_overview')
@endsection

@section('body_main_conten')
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                @if (session('not_page'))
                    <?php Alert::error('not page', 'Message')->autoClose(2000); ?>
                @endif
                @if (session('error'))
                    <div class="alert alert-success" id="error-message" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success" id="error-message" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <span>day la noi dung home admin</span>
            </div>
        </div>
    </div>


    <script>
        setInterval(() => {
            var error_message = $("#error-message");
            if (error_message.length) {
                $(error_message).remove()
            }
        }, 3000);
    </script>
@endsection

@extends('frontend.layouts.base')

@section('body')
{{-- @include('frontend.layouts.body') --}}
<p>123</p>
<form action="{{ route('ajax_post', ['id'=>1]) }}" method="post">
    {{ csrf_field() }}
    <button type="submit" class="btn btn-primary">post</button>
</form>

<script>
    jQuery("document").ready(function() {
        console.log('====================================');
        console.log(123);
        console.log('====================================');

        $.ajax({
            method: "POST",
            url: "{{ route('ajax_post', ['id'=>1]) }}",
            data: {
                "_token": "{{ csrf_token() }}"
            }
        }).done(function(res) {
            console.log(res);
        }).fail(function(err){
            console.log(err);
        });


        $(document).ajaxComplete(function(event, xhr, settings) {
            // console.log(settings);
            // console.log(xhr);
        });
    });

</script>
@endsection

@section('head_css')
@include('frontend.layouts.head_css')
@endsection

@section('bottom_js')
@include('frontend.layouts.bottom_js')
@endsection

@section("page_title")
trang chu
@endsection

@extends('frontend.templates.pagestr')

@section('page_title')
    login
@endsection

@section('trending_left')
    <div class="row">
        <div class="col-sm-8">
            <center><h4>Login form</h4></center>
            <form action="{{ route('login_post') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="user_email">email:</label>
                    <input id="user_email" class="form-control" type="email" name="email" required placeholder="enter your email">
                </div>

                <div class="form-group">
                    <label for="user_pass">password:</label>
                    <input id="user_pass" class="form-control" type="password" name="password" required
                        placeholder="enter your password">
                </div>

                <div class="form-group float-right">
                    <a href="" class="text-info"><label>Forgot password?</label></a>
                </div>

                <div class="form-group clear">
                    <center><button type="submit" class="genric-btn success circle">login</button></center>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('trending_right')
    <a href="{{ route('account_create') }}">
        <h3 class="text-info">Create new account</h3>
    </a>
@endsection

@section('new_post')
@endsection

@section('articles')
@endsection

@section('video_area')
@endsection


@section('weekly3_news')
@endsection

{{-- banner_last --}}
@section('banner_last')
@endsection

@section('weekly2_news')
@endsection

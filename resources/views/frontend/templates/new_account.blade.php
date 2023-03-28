@extends('frontend.templates.pagestr')

@section('page_title')
    new account
@endsection

@section('morning_post')
@endsection

@section('new_post')
@endsection

@section('weekly2_news')
@endsection

@section('articles')
@endsection

@section('video_area')
@endsection

@section('weekly3_news')
    <div class="container">
        <div class="row">
            <div class="col-sm-6 pt-10 pb-10">
                <form action="{{ route('account_post') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="user_email">email:</label>
                        <input id="user_email" class="form-control" type="email" name="user_email" required>
                    </div>

                    <div class="form-group">
                        <label for="user_pass">password:</label>
                        <input id="user_pass" class="form-control" type="password" name="pass" required>
                    </div>

                    <div class="form-group">
                        <label for="pass_confirm">password confirm:</label>
                        <input id="pass_confirm" class="form-control" type="password" name="pass_confirm" required>
                    </div>

                    <div class="form-group">
                        <center><button type="submit" class="btn btn-primary">create new account</button></center>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('banner_last')
@endsection

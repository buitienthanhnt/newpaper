
@extends('adminhtml.layouts.body_main')

@section('body_top_tab')
    @include('adminhtml.layouts.body_top_tab')
@endsection

@section('body_overview')
    @include('adminhtml.layouts.body_overview')
@endsection

@section('admin_title')
    user list
@endsection

@section('head_js_after')
    <script src={{ asset('assets/adminhtml/json-hierarchical-tree-picker/jquery.simple-tree-picker.js') }}></script>
@endsection
    <link rel="stylesheet" href={{asset('assets/adminhtml/json-hierarchical-tree-picker/jquery.simple-tree-picker.css')}}>
@section('after_css')

@endsection

@section('body_main_conten')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('admin_permission_create') }}" class="btn btn-info">create permission</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-light">
                    <thead class="thead-light">
                        <tr>
                            <th>id</th>
                            <th>name</th>
							<th>email</th>
							<th>create date</th>
							<th>update date</th>
                            <th>action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allUser as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
							<td>{{ $user->email }}</td>
							<td>{{ $user->created_at }}</td>
							<td>{{ $user->updated_at }}</td>
                            <td>
                                <a href="{{ route('admin_user_edit', ['user_id'=>$user->id]) }}" class="btn btn-info">edit</a>
                                <a href="" class="">
                                    <button class="btn btn-danger btn-flat show_confirm" data-id="{{ $user->id }}">delete</button>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row" style="float: left">
            <div class="col-md-12 mt-20 d-flex flex-row-reverse">
                {{ $allUser->links() }}
            </div>

        </div>

    </div>
@endsection
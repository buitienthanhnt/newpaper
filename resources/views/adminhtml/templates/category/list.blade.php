@extends('adminhtml.layouts.body_main')

@section('page_title')
    list category
@endsection

@section('body_top_tab')
    @include('adminhtml.layouts.body_top_tab')
@endsection

@section('body_overview')
    @include('adminhtml.layouts.body_overview')
@endsection

@section('body_main_conten')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Categories Table list</h4>
        <p class="card-description">
          Add class <code>.table-hover</code>
        </p>
        <div class="row float-right">
            <div class="col-md-8"></div>
            <div class="col-md-4 d-flex flex-row-reverse">
                <a href={{ route('category_admin_create') }}><button class="btn btn-info">create category</button></a>
            </div>
        </div>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Id</th>
                <th>name</th>
                <th>description</th>
                <th>Status</th>
                <th>parent_id</th>
                <th>url_alias</th>
                <th>action</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($all_category as $category)
                <tr>
                    <td>{{$category->id}}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->description }}</td>
                    <td>{{ $category->active ? "active" : "inActive" }}</td>
                    <td>{{ $category->parent_id }}</td>
                    <td>{{ $category->url_alias }}</td>
                    <td>
                        <a href={{ route('category_admin_edit', ['category_id'=> $category->id]) }}><button class="btn btn-info btn-sm">edit</button></a>
                        <a href={{ route('category_admin_delete', ['category_id'=> $category->id]) }} data-method="delete" ><button class="btn btn-info btn-danger">delete</button></a>
                    </td>
                    {{-- <td class="text-danger"> 28.76% <i class="ti-arrow-down"></i></td> --}}
                  </tr>
                @endforeach
            </tbody>
          </table>
        </div>
        <div class="row">
            <div class="col-md-12 mt-20 d-flex flex-row-reverse">
                {{ $all_category->links() }}
            </div>

        </div>

      </div>
    </div>
  </div>
@endsection

{{-- d-flex flex-row-reverse để float right --}}

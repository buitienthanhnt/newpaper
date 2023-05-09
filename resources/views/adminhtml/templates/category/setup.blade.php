@extends('adminhtml.layouts.body_main')

@section('body_top_tab')
    @include('adminhtml.layouts.body_top_tab')
@endsection

@section('body_overview')
    @include('adminhtml.layouts.body_overview')
@endsection

@section('admin_title')
    setup category
@endsection
@section('after_css')
    <style type="text/css">
        .select2-container--default .select2-selection--multiple .select2-selection__choice__display {
            font-size: medium
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            font-size: medium !important
        }
    </style>
@endsection


@section('body_main_conten')
    <div class="col-12 grid-margin">
        <div>
            <div class="card-body">

                @if ($list_current)
                    <h4 class="card-title"> current setup category:</h4>
                    <div class="row">
                        @foreach ($all_category as $item)
                            <div class="col-md-3 mt-3">
                                <input type="checkbox" @if (in_array($item->id, $list_current))
                                checked
                                @endif >
                                <span>{{ $item->name }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>

            <div class="card-body">
                <h4 class="card-title">choose setup categories:</h4>
                <form class="form-sample" method="POST" action={{ route('category_setup_save') }}>
                    @csrf

                    @if (session('success'))
                        <div class="alert alert-success" id="category_insert_success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">select Category:</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="category_top_use" name="setup_category[]"
                                        multiple="multiple">
                                        <?= $parent_category ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="setup_type">setup type: </label>
                                <div class="col-sm-10">
                                    <input id="setup_type" class="form-control" type="text" name="setup_type">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="offset-md-10 col-md-2">
                            <button type="submit" class="btn btn-info">save for selected</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // khong dat trong $(document).ready function(){}
        $("#category_top_use").select2({
            placeholder: 'Select an option',
            tags: true,
            tokenSeparators: [',', ' ']
        });

        $("#category_top_use").on("select2:select", function(evt) {
            var element = evt.params.data.element;
            var $element = $(element);

            $element.detach();
            $(this).append($element);
            $(this).trigger("change");
        });
    </script>
@endsection

@extends('adminhtml.layouts.body_main')

@section('body_top_tab')
    @include('adminhtml.layouts.body_top_tab')
@endsection

@section('body_overview')
    @include('adminhtml.layouts.body_overview')
@endsection

@section('admin_title')
    permission create
@endsection

@section('body_main_conten')
    <style type="text/css">
        .select2-selection--multiple {
            .select2-selection__choice {
                color: color(white);
                border: 0;
                border-radius: 3px;
                padding: 6px;
                font-size: larger !important;
                font-family: inherit;
                line-height: 1;
            }
        }
    </style>

    <div class="col-md-6">
        <form action="{{ route('admin_rule_insert') }}" method="post">
            @csrf
            @if ($message = session('success'))
                <?php alert()->success('server message', $message); ?>
            @elseif ($error = session('error'))
                <?php alert()->warning('server mesage', $error); ?>
            @endif

            <div class="form-group">
                <label for="name">permission name:</label>
                <input id="name" class="form-control" type="text" name="label" required>
            </div>

            <div class="form-group hidden">
                <label for="name">{{ isset($parent) ? $parent->label : '' }}</label>
                <select id="rules_option" class="form-control" name="rules[]" multiple="multiple">
                    @if ($rules)
                        {!! $rules !!}
                    @endif
                </select>

                {!! $ul_rules !!}
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-info btn-lg" style="width: -webkit-fill-available;">save
                    permission</button>
            </div>
        </form>
    </div>

    <script type="text/javascript">
        $("#rules_option").select2({
            placeholder: 'Select an option',
            tags: true
        });
    </script>
@endsection

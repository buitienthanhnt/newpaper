<div class="row">
    <div class="col-md-6">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Title:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="page_title" required value="@isset($title){{ $title }}@endisset" required placeholder="page title"/>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group row">
            <label for="active" class="col-sm-1">Active:</label>
            <div class="col-sm-1">
                <input id="active" class="form-check-input" type="checkbox" name="active"
                    value="@if (isset($active)){{ $active ? 'checked' : '' }} @else checked @endif"
                >
            </div>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group row">
            <label for="url-alias" class="col-sm-2">url alias:</label>
            <div class="col-sm-8">
                <input id="url-alias" class="form-control" type="text" name="alias" placeholder="use page title if this value is null" value="@isset($url_alias){{ $url_alias }}@endisset">
            </div>
        </div>
    </div>

    <div class="col-md-6 row">
        <label for="show" class="col-sm-1">show:</label>
        <div class="col-sm-1">
            <input id="show" class="form-check-input" type="checkbox" name="show" value="@if (isset($show)) {{ $show ? 'checked' : '' }} @else checked @endif">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <label for="short_conten" class="col-sm-2">short conten:</label>
        <div class="col-sm-10">
            <textarea id="short_conten" name="short_conten" class="form-control" rows="4" style="padding: 10px; height: 100%;">@isset($short_conten){{ $short_conten }}@endisset</textarea>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="auto_hide">auto hide: </label>
            <input id="auto_hide" class="form-check-input" type="checkbox" name="auto_hide" value=" @if (isset($show)){{ $show ? 'checked' : '' }} @else checked @endif">
        </div>
    </div>
</div>

<div class="row">

    {{-- <div class="col-md-6">
        <label for="category" class="col-sm-2">category:</label>
        <div class="col-sm-10">
            <div class="form-group">
                <select id="category_option" class="form-control" name="category_option[]"
                    multiple="multiple">
                    {!! $category_option !!}
                </select>
            </div>
        </div>
    </div> --}}

    <div class="col-md-6">
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">slider image lists:</label>
            <div class="col-sm-9">
                <div class="input-group">
                    <span class="input-group-btn">
                        <a id="lfm" data-input="thumbnail" data-preview="holder"
                            class="btn btn-primary">
                            <i class="fa fa-picture-o"></i> Choose images
                        </a>
                    </span>
                    <input id="thumbnail" class="form-control" type="text" name="image_paths">
                </div>
                <img id="holder" style="margin-top:15px;max-height:100px;">
            </div>
        </div>
    </div>
</div>
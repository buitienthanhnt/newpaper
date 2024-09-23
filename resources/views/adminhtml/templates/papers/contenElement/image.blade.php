<div data-type="p-picture">
    <p style="display: none">paper picture</p>
    <div class="data-content input-group">
        <span class="input-group-btn">
            <a class="btn btn-primary">
                <i class="fa fa-picture-o"></i> Choose
            </a>
        </span>
        <input class="form-control" type="text" name="{{$item->key}}" value="{{ $item->value }}">
        @isset($item->depend_value)
        <div class="input-group">
            <span class="input-group-btn">description:&nbsp;</span>
            <input class="form-control" type="text" name="{{ Str::replaceFirst('images_imagex_', 'imagex_desc_', $item->key) }}" value="{{ $item->depend_value }}">
        </div>
        @endisset
    </div>
    <img style="margin-top:15px;max-height:100px;" src="{{$item->value}}">
</div>

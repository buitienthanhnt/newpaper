<div data-type="p-picture">
    <p style="display: none">paper picture</p>
    <div class="data-content input-group">
        <span class="input-group-btn">
            <a class="btn btn-primary">
                <i class="fa fa-picture-o"></i> Choose
            </a>
        </span>
        <input class="form-control" type="text" name="{{$item->key}}" value="{{ $item->value }}">
    </div>
    <img style="margin-top:15px;max-height:100px;" src="{{$item->value}}">
</div>

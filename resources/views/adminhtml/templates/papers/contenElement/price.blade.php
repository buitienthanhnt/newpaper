<div data-type="p-price">
    <p style="display: none">paper price</p>
    <div class="data-content form-group row">
        <label for="price" class="col-sm-2">Price:</label>
        <div class="col-sm-8">
            <input type="number" name="{{ $item->key }}" placeholder="nghìn vnđ" class="form-control" min="0"
                value="@isset($item){{ $item->value }}@endisset">
        </div>
    </div>
</div>

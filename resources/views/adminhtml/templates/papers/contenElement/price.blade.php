@isset($item)
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
@else
    <div data-type="p-price">
        <p>paper price</p>
        <div class="data-content form-group row" style="display: none">
            <label for="price" class="col-sm-2">Price:</label>
            <div class="col-sm-8">
                <input type="number" name="price" id="price" placeholder="nghìn vnđ" class="form-control"
                    min="0"
                    value="@isset($price)
                   {{ $price }}
                   @endisset">
            </div>
        </div>
    </div>
@endisset

@isset($item)
    <div style="" data-type="p-html">
        <p>paper html content</p>
        <div class="data-content">
            <textarea id="conten" name="{{ $item->key }}" class="form-control" style="height: 720px;">
            {!! $item->value !!}
        </textarea>
        </div>
    </div>
@else
    <div style="" data-type="p-html">
        <div class="demo-content-source">
            <p class="text-primary" style="font-size: 16px; font-weight: 600; text-decoration: underline;">
                view demo
                frontend(pull right to add content):</p>
            @isset($conten)
                <style>
                    .demo-content-source img {
                        max-width: 30% !important;
                        width: auto !important;
                        height: auto !important;
                    }
                </style>
                {!! $conten !!}
            @else
                <p>paper html content</p>
            @endisset
        </div>
        <div class="data-content">
            <textarea id="conten" name="conten" class="form-control" style="height: 720px; display: none">
                @isset($conten){{ $conten }}@endisset
            </textarea>
        </div>
    </div>
@endisset

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
            <div style="height: 150px; display: flex; justify-content: center; align-items: center">
                {{-- https://www.flaticon.com/search?word=picture --}}
                <img src="{{ asset('/assets/adminhtml/images/content-management.png') }}" alt="" style="width: auto; height: 100%;">
            </div>
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

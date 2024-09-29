<div class="form-group row">
    <label for="active" class="col-sm-3">
        @isset($label)
            {{ $label }}
        @endisset:
    </label>
    <div class="col-sm-1">
        <input @isset($id)
                id="{{ $id }}"
            @endisset
            class="form-check-input" type="checkbox" name="{{ $name }}"
            @isset($checked)
                @if ($checked)checked @endif
            @endisset>
    </div>
</div>

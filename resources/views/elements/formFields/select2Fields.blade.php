<div class="form-group">
    @isset($label)
        <label for="category">{{ $label }}:</label>
    @endisset
    <div class="form-group">
        <select id="{{ $id }}" class="form-control" name="{{ $name}}[]" multiple="multiple">
            @isset($options)
                @if (is_string($options))
                    {!! $options !!}
                @else
                    @foreach ($options as $option)
                        <option value="{{ $option->id }}">{{ $option->name }}</option>
                    @endforeach
                @endif
            @endisset
        </select>
    </div>
</div>
<script type="text/javascript">
    $("#@php echo($id); @endphp").select2({
        tags: true,
        tokenSeparators: JSON.parse('@php echo json_encode($token_separators); @endphp'),
        placeholder: "@php echo $place_holder ?? ''; @endphp",
        maximumSelectionLength: "@php echo !isset($multiple) ? null : ($multiple ? null : 1); @endphp"
    });
</script>

{{ Form::label($name, null, []) }}
<div class="form-group custom-file">
    {{ Form::file($name, array_merge(['class' => 'custom-file-input'], $attributes)) }}
    {{ Form::label($name, $value, ['class' => 'custom-file-label']) }}
</div>
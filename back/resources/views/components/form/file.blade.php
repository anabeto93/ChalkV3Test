{{ Form::label($name, null, []) }}
<div class="form-group custom-file">
    {{ Form::file($name, ['class' => 'custom-file-input']) }}
    {{ Form::label($name, $value, ['class' => 'custom-file-label']) }}
</div>
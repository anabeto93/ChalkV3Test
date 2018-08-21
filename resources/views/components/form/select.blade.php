<div class="form-group">
    {{ Form::label($name, null, ['class' => 'control-label']) }}
    {{ Form::select($name, $value, null, ['class' => 'form-control']) }}
</div>
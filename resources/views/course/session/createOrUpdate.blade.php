@extends('layouts.form')

@section('title')
    {{ $course->title }} ~ @lang(empty($session) ? 'actions.create.session' : 'actions.update.session')
@endsection

@section('form.title')
    <a href="{{ route('course.show', $course->id) }}">
        {{ $course->title }}
    </a> ~ @lang(empty($session) ? 'actions.create.session' : 'actions.update.session')
@endsection

@section('form')
    @if(isset($session))
        {{ Form::model($session, ['route' => ['course.session.update', $course->id, $session->id]]) }}
    @else
        {{ Form::open(['route' => ['course.session.create', $course->id]]) }}
    @endif
    {{ Form::bsNumber('order', Input::old('order')) }}
    {{ Form::bsText('title', Input::old('title'), ['required' => true]) }}
    {{ Form::bsFile('content', trans('fields.file')) }}
    <small id="content" class="form-text text-muted" style="margin-bottom: 1.5em">
        @if(isset($session))
            @lang('messages.help.session.update')
        @else
            @lang('messages.help.session.create')
        @endif
    </small>
    {{ Form::bsCheckbox('progression_lock', Input::old('progression_lock')) }}
    {{ Form::bsCheckbox('enabled', Input::old('enabled')) }}
@endsection
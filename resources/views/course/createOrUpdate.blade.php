@extends('layouts.form')

@section('title', trans(empty($course) ? 'actions.create.course' : 'actions.update.course'))

@section('form.title', trans(empty($course) ? 'actions.create.course' : 'actions.update.course'))

@section('form')
    @if(isset($course))
        {{ Form::model($course, ['route' => ['course.update', $course->id]]) }}
    @else
        {{ Form::open(['route' => 'course.create']) }}
    @endif
    {{ Form::bsText('title', Input::old('title'), ['required' => true]) }}
    {{ Form::bsTextArea('description', Input::old('description')) }}
    {{ Form::bsText('teacher', Input::old('teacher'), ['required' => true]) }}
    {{ Form::bsCheckbox('enabled', Input::old('enabled')) }}
@endsection
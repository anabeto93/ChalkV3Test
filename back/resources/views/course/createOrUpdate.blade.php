@extends('layouts.form')

@section('title')
    @lang('actions.create.course')
@endsection

@section('form.title', trans('actions.create.course'))

@section('form')
    @if(isset($course))
        {{ Form::model($course, ['route' => 'course.update', $course->id]) }}
    @else
        {{ Form::open(['route' => 'course.create']) }}
    @endif
    {{ Form::bsText('name') }}
    {{ Form::bsTextArea('description') }}
    {{ Form::bsText('teacher') }}
    {{ Form::bsCheckbox('enabled') }}
@endsection
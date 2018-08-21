@extends('layouts.form')

@section('title')
    {{ $course->title }} ~ @lang(empty($folder) ? 'actions.create.folder' : 'actions.update.folder')
@endsection

@section('form.title')
    <a href="{{ route('course.show', $course->id) }}">
        {{ $course->title }}
    </a> ~ @lang(empty($folder) ? 'actions.create.folder' : 'actions.update.folder')
@endsection

@section('form')
    @if(isset($folder))
        {{ Form::model($folder, ['route' => ['course.folder.update', $course->id, $folder->id]]) }}
    @else
        {{ Form::open(['route' => ['course.folder.create', $course->id]]) }}
    @endif
    {{ Form::bsNumber('order', Input::old('order')) }}
    {{ Form::bsText('title', Input::old('title'), ['required' => true]) }}
@endsection
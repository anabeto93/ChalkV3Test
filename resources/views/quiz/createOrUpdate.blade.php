@extends('layouts.form')

@section('title', trans(empty($quiz) ? 'actions.create.quiz' : 'actions.update.quiz'))

@section('form.title', trans(empty($quiz) ? 'actions.create.quiz' : 'actions.update.quiz'))

@section('form')
    @if(isset($quiz))
        {{ Form::model($quiz, ['route' => ['quiz.update', $quiz->id]]) }}
    @else
        {{ Form::open(['route' => 'quiz.create']) }}
    @endif
    {{ Form::bsText('title', Input::old('title'), ['required' => true]) }}
    {{ Form::bsTextArea('description', Input::old('description'), ['required' => true]) }}
    {{ Form::bsCheckbox('enabled', Input::old('enabled')) }}
@endsection
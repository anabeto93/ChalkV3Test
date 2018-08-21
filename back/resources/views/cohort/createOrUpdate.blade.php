@extends('layouts.form')

@section('title', trans(empty($cohort) ? 'actions.create.cohort' : 'actions.update.cohort'))

@section('form.title', trans(empty($cohort) ? 'actions.create.cohort' : 'actions.update.cohort'))

@section('form')
    @if(isset($cohort))
        {{ Form::model($cohort, ['route' => ['cohort.update', $cohort->id]]) }}
    @else
        {{ Form::open(['route' => 'cohort.create']) }}
    @endif
    {{ Form::bsText('name', Input::old('name'), ['required' => true]) }}
@endsection
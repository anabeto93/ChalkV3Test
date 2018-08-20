@extends('layouts.form')

@section('title', trans(empty($user) ? 'actions.create.user' : 'actions.update.user'))

@section('form.title', trans(empty($user) ? 'actions.create.user' : 'actions.update.user'))

@section('form')
    @if(isset($user))
        {{ Form::model($user, ['route' => ['user.update', $user->id]]) }}
    @else
        {{ Form::open(['route' => 'user.create']) }}
    @endif
    {{ Form::bsText('first_name', Input::old('first_name')) }}
    {{ Form::bsText('last_name', Input::old('last_name')) }}
    {{ Form::bsText('phone_number', Input::old('phone_number')) }}
    {{ Form::bsText('country', Input::old('country')) }}
    {{ Form::bsText('locale', Input::old('locale')) }}
@endsection
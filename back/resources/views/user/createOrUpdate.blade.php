@extends('layouts.form')

@section('title', trans(empty($user) ? 'actions.create.user' : 'actions.update.user'))

@section('form.title', trans(empty($user) ? 'actions.create.user' : 'actions.update.user'))

@section('form')
    @if(isset($user))
        {{ Form::model($user, ['route' => ['user.update', $user->id]]) }}
    @else
        {{ Form::open(['route' => 'user.create']) }}
    @endif
    {{ Form::bsText('first_name', Input::old('first_name'), ['required' => true]) }}
    {{ Form::bsText('last_name', Input::old('last_name'), ['required' => true]) }}
    {{ Form::bsText('phone_number', Input::old('phone_number'), ['required' => true]) }}
    {{ Form::bsSelect('country', Countries::getList(app()->getLocale()), Input::old('country'), ['required' => true]) }}
    {{ Form::bsSelect('language', Config::get('constants.lang'), Input::old('language'), ['required' => true]) }}
@endsection
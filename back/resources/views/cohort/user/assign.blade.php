@extends('layouts.form')

@section('title')
    {{ $cohort->name }} ~ @lang('messages.assign.users')
@endsection

@section('form.title')
    <i width="32" height="32" data-feather="inbox"></i>
    <a href="{{ route('cohort.show', $cohort->id) }}">
        {{ $cohort->name }}
    </a> ~ @lang('messages.assign.users')
@endsection

@section('form')
    {{ Form::open(['route' => ['cohort.user.assign', $cohort->id]]) }}

    @foreach($users as $user)
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="{{ $user->id }}" {{ $cohort->users->contains
            ($user) ? 'checked' : '' }}>
            <label class="form-check-label" for="{{ $user->id }}">
                {{ $user->name }} | <b>{{ $user->phone_number }}</b>
            </label>
        </div>
    @endforeach
@endsection
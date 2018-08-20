@extends('layouts.form')

@section('title')
    {{ $course->title }} ~ @lang('messages.assign.users')
@endsection

@section('form.title')
    <i width="32" height="32" data-feather="book"></i>
    <a href="{{ route('course.show', $course->id) }}">
        {{ $course->title }}
    </a> ~ @lang('messages.assign.users')
@endsection

@section('form')
    {{ Form::open(['route' => ['course.user.assign', $course->id]]) }}

    @foreach($users as $user)
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="{{ $user->id }}" {{ $course->users->contains
            ($user) ? 'checked' : '' }}>
            <label class="form-check-label" for="{{ $user->id }}">
                {{ $user->name }} | <b>{{ $user->phone_number }}</b>
            </label>
        </div>
    @endforeach
@endsection
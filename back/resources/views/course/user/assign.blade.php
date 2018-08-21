@extends('layouts.form')

@section('title')
    {{ $course->title }} ~ @lang('actions.assign.users')
@endsection

@section('form.title')
    <i width="32" height="32" data-feather="book"></i>
    <a href="{{ route('course.show', $course->id) }}">
        {{ $course->title }}
    </a> ~ @lang('actions.assign.users')
@endsection

@section('form')
    {{ Form::open(['route' => ['course.user.assign', $course->id]]) }}

    @foreach($users as $user)
        <div class="form-group form-check">
            <input id="{{ $user->id }}" name="user_id[]" type="checkbox"
                   class="form-check-input" {{ $course->users->contains($user) ? 'checked' : '' }}>
            <label class="form-check-label" for="{{ $user->id }}">
                {{ $user->name }} | <b>{{ $user->phone_number }}</b>
            </label>
        </div>
    @endforeach
@endsection
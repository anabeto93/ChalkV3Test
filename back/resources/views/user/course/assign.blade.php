@extends('layouts.form')

@section('title')
    {{ $user->name }} ~ @lang('actions.assign.courses')
@endsection

@section('form.title')
    <i width="32" height="32" data-feather="user"></i>
    <a href="{{ route('user.show', $user->id) }}">
        {{ $user->name }}
    </a> ~ @lang('actions.assign.courses')
@endsection

@section('form')
    {{ Form::open(['route' => ['user.course.assign', $user->id]]) }}

    @foreach($courses as $course)
        <div class="form-group form-check">
            <input id="{{ $course->id }}" name="course_id[]" type="checkbox"
                   class="form-check-input" {{ $user->courses->contains($course) ? 'checked' : '' }}>
            <label class="form-check-label" for="{{ $course->id }}">
                {{ $course->title }} | <b>{{ $course->teacher }}</b>
            </label>
        </div>
    @endforeach
@endsection
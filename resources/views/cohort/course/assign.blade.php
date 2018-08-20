@extends('layouts.form')

@section('title')
    {{ $cohort->name }} ~ @lang('messages.assign.courses')
@endsection

@section('form.title')
    <i width="32" height="32" data-feather="inbox"></i>
    <a href="{{ route('cohort.show', $cohort->id) }}">
        {{ $cohort->name }}
    </a> ~ @lang('messages.assign.courses')
@endsection

@section('form')
    {{ Form::open(['route' => ['cohort.course.assign', $cohort->id]]) }}

    @foreach($courses as $course)
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="{{ $course->id }}" {{ $cohort->courses->contains
            ($course) ? 'checked' : '' }}>
            <label class="form-check-label" for="{{ $course->id }}">
                {{ $course->title }} | <b>{{ $course->teacher }}</b>
            </label>
        </div>
    @endforeach
@endsection
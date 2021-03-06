@extends('layouts.base')

@section('title')
    @yield('title')
@endsection

@section('content')
    <h1>@yield('form.title')</h1>
    <hr />

    @if(count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('form')
    {{ Form::bsSubmit(trans('actions.save')) }}
    {{ Form::close() }}
@endsection

@section('javascript')
    @yield('javascript')
@endsection
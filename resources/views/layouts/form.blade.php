@extends('layouts.base')

@section('content')
    <h1>@yield('title')</h1>
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

    {{ Form::open() }}
    @yield('form')
    {{ Form::bsSubmit(trans('actions.save')) }}
    {{ Form::close() }}

@endsection
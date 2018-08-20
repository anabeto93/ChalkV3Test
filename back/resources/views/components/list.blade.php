@extends('layouts.base')

@section('title', $title)

@section('content')
    @if(isset($array) && filled($array) && isset($formRoute))
        {{ Form::open(['route' => $formRoute]) }}
    @endif

    <div class="clearfix">
        <h1 class="float-left">
            @if(isset($headerIcon))
                <i width="32" height="32" data-feather="{{ $headerIcon }}"></i>
            @endif
            {{ $headerTitle }}
        </h1>

        <span class="float-right">
            @if(isset($array) && filled($array) && isset($formRoute) && isset($formSubmit))
                <input class="btn btn-outline-dark" type="submit" value="{{ $formSubmit }}" />
            @endif

            <a class="btn btn-success" role="button" href="{{ $headerActionRoute }}">
                {{ $headerActionTitle }}
            </a>
        </span>
    </div>

    @if(isset($array) && filled($array))
        {{ $table }}
    @else
        <hr style="margin-top: 0"/>

        <h3 class="text-center">
            <span class="badge badge-danger">
                {{ isset($none) ? $none : trans('messages.none.title') }}
            </span>
        </h3>
    @endif

    @if(isset($array) && filled($array) && isset($formRoute))
        {{ Form::close() }}
    @endif
@endsection
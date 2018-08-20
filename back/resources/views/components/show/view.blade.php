@extends('layouts.base')

@section('title', $title)

@section('content')
    <div class="clearfix">
        <span class="float-left">
            <h1>
                @if(isset($headerIcon))
                    <i data-feather="{{ $headerIcon }}" width="32" height="32"></i>
                @endif
                {{ $headerTitle }}
            </h1>
        </span>

        <div class="float-right">
            {{ $headerActions }}
        </div>
    </div>

    <hr style="margin-top: 0"/>

    {{ $rows }}
@endsection
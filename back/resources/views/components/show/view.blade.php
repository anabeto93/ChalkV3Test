@extends('layouts.base')

@section('title', $title)

@section('content')
    <div class="clearfix">
        <span class="float-left">
            <h1>
                @if(isset($headerIcon))
                    <i data-feather="{{ $headerIcon }}" width="32" height="32"></i>
                @endif
                @if(isset($headerTitle))
                    {{ $headerTitle }}
                @endif
            </h1>
        </span>

        @if(isset($headerActions))
            <div class="float-right">
                {{ $headerActions }}
            </div>
        @endif
    </div>

    <hr style="margin-top: 0"/>

    @if(isset($rows))
        {{ $rows }}
    @endif
@endsection
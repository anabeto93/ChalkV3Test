<div class="card" style="margin-bottom: 2em">
    <div class="card-header">
        @if(isset($headerIcon))
            <i data-feather="{{ $headerIcon }}" width="16" height="16"></i>&nbsp;
        @endif

        @if(isset($headerTitle))
            <b class="align-middle">{{ $headerTitle }}</b>
        @endif

        @if(isset($headerActionRoute) && isset($headerActionTitle))
            <a class="btn btn-info btn-sm float-right" href="{{ $headerActionRoute }}">
                {{ $headerActionTitle }}
            </a>
        @endif
    </div>

    @if(isset($array) && filled($array))
        <ul class="list-group list-group-flush">
            {{ $body }}
        </ul>
    @else
        <h4 class="text-center" style="padding: 0.75em">
            <span class="badge badge-danger">
                {{ isset($none) ? $none : trans('messages.none.title') }}
            </span>
        </h4>
    @endif
</div>
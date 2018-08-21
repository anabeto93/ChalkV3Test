@component('components.list', [
    'title' => $course->title . " ~ " . trans('messages.list.sessions'),
    'headerActionRoute' => route('course.session.create', $course->id),
    'headerActionTitle' => trans('actions.create.session'),
    'array' => $sessions,
    'none' => trans('messages.none.sessions')
])
    @slot('headerTitle')
        <a href="{{ route('course.show', $course->id) }}">
            {{ $course->title }}
        </a> ~ @lang('messages.list.sessions')
    @endslot

    @slot('table')
        <table class="table table-striped">
            <thead>
                <tr class="row">
                    <th class="col-1">@lang('fields.order')</th>
                    <th class="col-2">@lang('fields.title')</th>
                    <th class="col-2">@lang('fields.folder')</th>
                    <th class="col-2 text-center">@lang('fields.enabled')</th>
                    <th class="col-2 text-center">@lang('fields.progression_lock')</th>
                    <th class="col-3 text-right"><span class="text-center">@lang('fields.actions')</span></th>
                </tr>
            </thead>

            <tbody>
            @foreach($sessions as $session)
                <tr class="row">
                    <td class="col-1">{{ $session->order }}</td>
                    <td class="col-2">{{ $session->title }}</td>
                    <td class="col-2">
                        @if(filled($session->folder))
                            {{ $session->folder->name }}
                        @endif
                    </td>
                    <td class="col-2 text-center">
                        @if($session->enabled)
                            <i data-feather="check"></i>
                        @endif
                    </td>
                    <td class="col-2 text-center">
                        @if($session->progression_lock)
                            <i data-feather="check"></i>
                        @endif
                    </td>
                    <td class="col-3 text-right">
                        <a class="btn btn-primary btn-sm" role="button" href="{{ route('course.session.update', [
                        'course' => $course->id,
                        'session'=> $session->id
                        ]) }}">
                            @lang('actions.update.title')
                        </a>
                        <a class="btn btn-danger btn-sm" role="button" href="#">
                            @lang('actions.delete.title')
                        </a>
                        <a class="btn btn-info btn-sm" role="button" href="#">
                            @lang('actions.show.title')
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endslot
@endcomponent
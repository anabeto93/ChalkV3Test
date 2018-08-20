@component('components.list', [
    'title' => $user->name . " ~ " . trans('messages.list.courses'),
    'headerIcon' => 'user',
    'headerActionRoute' => route('user.course.assign', $user->id, $user->courses),
    'headerActionTitle' => trans('actions.assign.courses'),
    'array' => $user->courses,
    'none' => trans('messages.none.courses')
])
    @slot('headerTitle')
        <a href="{{ route('user.show', $user->id) }}">
            {{ $user->name }}
        </a> ~ @lang('messages.list.courses')
    @endslot

    @slot('table')
        <table class="table table-striped">
            <thead>
            <tr class="row">
                <th class="col-3">@lang('fields.title')</th>
                <th class="col-3">@lang('fields.description')</th>
                <th class="col-2">@lang('fields.teacher')</th>
                <th class="col-1 text-center">@lang('fields.enabled')</th>
                <th class="col-3 text-right"><span class="text-center">@lang('fields.actions')</span></th>
            </tr>
            </thead>

            <tbody>
            @foreach($user->courses as $course)
                <tr class="row">
                    <td class="col-3">{{ $course->title }}</td>
                    <td class="col-3">{{ $course->description }}</td>
                    <td class="col-2">{{ $course->teacher }}</td>
                    <td class="col-1 text-center">
                        @if($course->enabled)
                            <i data-feather="check"></i>
                        @endif
                    </td>
                    <td class="col-3 text-right">
                        <a class="btn btn-danger btn-sm" role="button" href="#">
                            @lang('actions.remove')
                        </a>
                        <a class="btn btn-info btn-sm" role="button" href="{{ route('course.show', $course->id) }}">
                            @lang('actions.show.title')
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endslot
@endcomponent
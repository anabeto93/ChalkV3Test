@component('components.list', [
    'title' => $course->title . " ~ " . trans('messages.list.users'),
    'formRoute' => ['course.user.list', $course->id],
    'formSubmit' => trans('actions.send.login'),
    'headerIcon' => 'book',
    'headerActionRoute' => route('course.user.assign', $course->id, $course->users),
    'headerActionTitle' => trans('actions.assign.users'),
    'array' => $course->users,
    'none' => trans('messages.none.users')
])
    @slot('headerTitle')
        <a href="{{ route('course.show', $course->id) }}">
            {{ $course->title }}
        </a> ~ @lang('messages.list.users')
    @endslot

    @slot('table')
        <table class="table table-striped">
            <thead>
            <tr class="row">
                <th class="col-1">
                    <input type="checkbox" id="select_all" />
                </th>
                <th class="col-3">@lang('fields.first_name')</th>
                <th class="col-3">@lang('fields.last_name')</th>
                <th class="col-2">@lang('fields.phone_number')</th>
                <th class="col-1">@lang('fields.token')</th>
                <th class="col-2 text-right"><span class="text-center">@lang('fields.actions')</span></th>
            </tr>
            </thead>

            <tbody>
            @foreach($course->users as $user)
                <tr class="row">
                    <td class="col-1">
                        <input type="checkbox" id="{{ $user->id }}" name="{{ $user->id }}" />
                    </td>
                    <td class="col-3">{{ $user->first_name }}</td>
                    <td class="col-3">{{ $user->last_name }}</td>
                    <td class="col-2">{{ $user->phone_number }}</td>
                    <td class="col-1">{{ $user->token }}</td>
                    <td class="col-2 text-right">
                        <a class="btn btn-danger btn-sm" role="button" href="#">
                            @lang('actions.remove')
                        </a>
                        <a class="btn btn-info btn-sm" role="button" href="{{ route('user.show', $user->id) }}">
                            @lang('actions.show.title')
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endslot
@endcomponent
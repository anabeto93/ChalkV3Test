@component('components.list', [
    'title' => trans('messages.list.users'),
    'formRoute' => 'user.list',
    'formSubmit' => trans('actions.send.login'),
    'headerTitle' => trans('messages.list.users'),
    'headerActionRoute' => route('user.create'),
    'headerActionTitle' => trans('actions.create.user'),
    'array' => $users,
    'none' => trans('messages.none.users')
])
    @slot('table')
        <table class="table table-striped">
            <thead>
                <tr class="row">
                    <th class="col-1">
                        <input type="checkbox" id="select_all" />
                    </th>
                    <th class="col-2">@lang('fields.first_name')</th>
                    <th class="col-2">@lang('fields.last_name')</th>
                    <th class="col-2">@lang('fields.phone_number')</th>
                    <th class="col-1">@lang('fields.token')</th>
                    <th class="col-2">@lang('fields.login_last_sent')</th>
                    <th class="col-2 text-right"><span class="text-center">@lang('fields.actions')</span></th>
                </tr>
            </thead>

            <tbody>
            @foreach($users as $user)
                <tr class="row">
                    <td class="col-1">
                        <input type="checkbox" id="{{ $user->id }}" name="{{ $user->id }}" />
                    </td>
                    <td class="col-2">{{ $user->first_name }}</td>
                    <td class="col-2">{{ $user->last_name }}</td>
                    <td class="col-2">{{ $user->phone_number }}</td>
                    <td class="col-1">{{ $user->token }}</td>
                    <td class="col-2">{{ $user->login_access_last_sent }}</td>
                    <td class="col-2 text-right">
                        <a class="btn btn-primary btn-sm" role="button" href="{{ route('user.update', $user->id) }}">
                            @lang('actions.update.title')
                        </a>
                        {{--<a class="btn btn-danger btn-sm" role="button" href="#">--}}
                            {{--@lang('actions.delete.title')--}}
                        {{--</a>--}}
                        <a class="btn btn-info btn-sm" role="button" href={{ route('user.show', $user->id) }}>
                            @lang('actions.show.title')
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endslot
@endcomponent
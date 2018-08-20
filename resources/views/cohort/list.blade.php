@component('components.list', [
    'title' => trans('messages.list.cohorts'),
    'headerTitle' => trans('messages.list.cohorts'),
    'headerActionRoute' => route('cohort.create'),
    'headerActionTitle' => trans('actions.create.cohort'),
    'array' => $cohorts,
    'none' => trans('messages.none.cohorts')
])
    @slot('table')
        <table class="table table-striped">
            <thead>
                <tr class="row">
                    <th class="col-4">@lang('fields.name')</th>
                    <th class="col-2">@lang('fields.users')</th>
                    <th class="col-2">@lang('fields.courses')</th>
                    <th class="col-4 text-right"><span class="text-center">@lang('fields.actions')</span></th>
                </tr>
            </thead>

            <tbody>
            @foreach($cohorts as $cohort)
                <tr class="row">
                    <td class="col-4">{{ $cohort->name }}</td>
                    <td class="col-2">
                        {{ $cohort->users->count() }}
                    </td>
                    <td class="col-2">
                        {{ $cohort->courses->count() }}
                    </td>
                    <td class="col-4 text-right">
                        <a class="btn btn-primary btn-sm" role="button" href="{{ route('cohort.update', $cohort->id) }}">
                            @lang('actions.update.title')
                        </a>
                        <a class="btn btn-danger btn-sm" role="button" href="#">
                            @lang('actions.delete.title')
                        </a>

                        <div class="btn-group" role="group">
                            <a class="btn btn-info btn-sm" role="button" href="{{ route('cohort.show', $cohort->id) }}">
                                @lang('actions.show.title')
                            </a>

                            <button type="button" class="btn btn-info btn-sm active dropdown-toggle"
                                    data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">

                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('cohort.course.list', $cohort->id) }}">
                                    <i width="16" height="16" data-feather="book"></i>
                                    <span class="align-middle">@lang('messages.title.courses')</span>
                                </a>
                                <a class="dropdown-item" href="{{ route('cohort.user.list', $cohort->id) }}">
                                    <i width="16" height="16" data-feather="users"></i>
                                    <span class="align-middle">@lang('messages.title.users')</span>
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endslot
@endcomponent
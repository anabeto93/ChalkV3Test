@component('components.list', [
    'title' => trans('messages.list.courses'),
    'headerTitle' => trans('messages.list.courses'),
    'headerActionRoute' => route('course.create'),
    'headerActionTitle' => trans('actions.create.course'),
    'array' => $courses,
    'none' => trans('messages.none.courses')
])
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
                 @foreach($courses as $course)
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
                            <a class="btn btn-primary btn-sm" role="button" href="{{ route('course.update', $course->id) }}">
                                @lang('actions.update.title')
                            </a>
                            <a class="btn btn-danger btn-sm" role="button" href="#">
                                @lang('actions.delete.title')
                            </a>
                            <div class="btn-group" role="group">
                                <a class="btn btn-info btn-sm" role="button" href="{{ route('course.show', $course->id) }}">
                                    @lang('actions.show.title')
                                </a>

                                <button type="button" class="btn btn-info btn-sm active dropdown-toggle"
                                        data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">

                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('course.folder.list', $course->id) }}">
                                        <i width="16" height="16" data-feather="folder"></i>
                                        <span class="align-middle">@lang('messages.title.folders')</span>
                                    </a>
                                    <a class="dropdown-item" href="{{ route('course.session.list', $course->id) }}">
                                        <i width="16" height="16" data-feather="file-text"></i>
                                        <span class="align-middle">@lang('messages.title.sessions')</span>
                                    </a>
                                    <a class="dropdown-item" href="{{ route('course.user.list', $course->id) }}">
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
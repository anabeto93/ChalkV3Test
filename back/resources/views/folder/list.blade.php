@component('components.list', [
    'title' => $course-> title . " ~ " . trans('messages.list.folders'),
    'headerActionRoute' => route('course.folder.create', $course->id),
    'headerActionTitle' => trans('actions.create.folder'),
    'array' => $folders,
    'none' => trans('messages.none.folders')
])
    @slot('headerTitle')
        <a href="{{ route('course.show', $course->id) }}">
            {{ $course->title }}
        </a> ~ @lang('messages.list.folders')
    @endslot

    @slot('table')
        <table class="table table-striped">
            <thead>
                <tr class="row">
                    <th class="col-1">@lang('fields.order')</th>
                    <th class="col-8">@lang('fields.title')</th>
                    <th class="col-3 text-right"><span class="text-center">@lang('fields.actions')</span></th>
                </tr>
            </thead>

            <tbody>
            @foreach($folders as $folder)
                <tr class="row">
                    <td class="col-1">{{ $folder->order }}</td>
                    <td class="col-8">{{ $folder->title }}</td>
                    <td class="col-3 text-right">
                        <a class="btn btn-primary btn-sm" role="button" href="{{ route('course.folder.update', [
                        'course' => $course->id,
                        'folder'=> $folder->id
                        ]) }}">
                            @lang('actions.update.title')
                        </a>
                        <a class="btn btn-danger btn-sm" role="button" href="#">
                            @lang('actions.delete.title')
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endslot
@endcomponent
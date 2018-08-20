@component('components.show.view', [
    'title' => trans('messages.show.course', ['course' => $course->title]),
    'headerIcon' => 'book',
    'headerTitle' => trans('messages.show.course', ['course' => $course->title]),
])
    @slot('headerActions')
        <a class="btn btn-success" role="button" href="{{ route('course.user.assign', $course->id) }}">
            @lang('actions.assign.users')
        </a>
        <a class="btn btn-dark" role="button" href="#">
            @lang('actions.assign.quiz')
        </a>
        <a class="btn btn-primary" role="button" href="{{ route('course.update', $course->id) }}">
            @lang('actions.update.title')
        </a>
        <a class="btn btn-danger" role="button" href="#">
            @lang('actions.delete.title')
        </a>
    @endslot

    @slot('rows')
        @component('components.show.row', [
            'title' => trans('fields.description'),
            'data' => $course->description
        ])
        @endcomponent


        @component('components.show.row', [
            'title' => trans('fields.teacher'),
            'data' => $course->teacher
        ])
        @endcomponent


        @component('components.show.row', [
            'title' => trans('fields.enabled'),
        ])
            @slot('data')
                @if($course->enabled)
                    <i data-feather="check"></i>
                @else
                    <i data-feather="x"></i>
                @endif
            @endslot
        @endcomponent


        @component('components.show.row', [
            'title' => trans('fields.folders'),
        ])
            @slot('data')
                <a href="{{ route('course.folder.list', $course->id) }}">
                    {{ $course->folders->count() }}
                </a>
            @endslot
        @endcomponent


        @component('components.show.row', [
            'title' => trans('fields.sessions'),
        ])
            @slot('data')
                <a href="{{ route('course.session.list', $course->id) }}">
                    {{ $course->sessions->count() }}
                </a>
            @endslot
        @endcomponent


        @component('components.show.row', [
            'title' => trans('fields.users'),
        ])
            @slot('data')
                <a href="{{ route('course.user.list', $course->id) }}">
                    {{ $course->users->count() }}
                </a>
            @endslot
        @endcomponent


        @component('components.show.row', [
            'title' => trans('fields.created_at'),
            'data' => \Carbon\Carbon::parse($course->created_at)->format('l, jS F Y')
        ])
        @endcomponent


        @component('components.show.row', [
            'title' => trans('fields.updated_at'),
            'data' => \Carbon\Carbon::parse($course->updated_at)->format('l, jS F Y')
        ])
        @endcomponent

        <hr/>

        <!-- Folders -->
        @component('components.show.card', [
            'headerIcon' => 'folder',
            'headerTitle' => trans('messages.title.folders'),
            'headerActionRoute' => route('course.folder.list', $course->id),
            'headerActionTitle' => trans('actions.show.folders'),
            'array' => $course->folders,
            'none' => trans('messages.none.folders'),
        ])
            @slot('body')
                @foreach($course->folders as $folder)
                    <li class="list-group-item">
                        {{ $folder->title }}
                    </li>
                @endforeach
            @endslot
        @endcomponent

        <!-- Sessions -->
        @component('components.show.card', [
            'headerIcon' => 'file-text',
            'headerTitle' => trans('messages.title.sessions'),
            'headerActionRoute' => route('course.session.list', $course->id),
            'headerActionTitle' => trans('actions.show.sessions'),
            'array' => $course->sessions,
            'none' => trans('messages.none.sessions'),
        ])
            @slot('body')
                @foreach($course->sessions as $session)
                    <li class="list-group-item">
                        {{ $session->title }}
                    </li>
                @endforeach
            @endslot
        @endcomponent

        <!-- Users -->
        @component('components.show.card', [
            'headerIcon' => 'users',
            'headerTitle' => trans('messages.title.users'),
            'headerActionRoute' => route('course.user.list', $course->id),
            'headerActionTitle' => trans('actions.show.users'),
            'array' => $course->users,
            'none' => trans('messages.none.users'),
        ])
            @slot('body')
                @foreach($course->users as $user)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-6">
                                {{ $user->name }}
                            </div>

                            <div class="col-3">
                                {{ $user->phone_number }}
                            </div>

                            <div class="col-3 text-right">
                                <a class="btn btn-sm btn-outline-info" role="button" href="{{ route('user.show',
                                $user->id) }}">
                                    @lang('actions.show.title')
                                </a>
                            </div>
                        </div>
                    </li>
                @endforeach
            @endslot
        @endcomponent
    @endslot
@endcomponent
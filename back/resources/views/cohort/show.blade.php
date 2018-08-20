@component('components.show.view', [
    'title' => trans('messages.show.cohort', ['cohort' => $cohort->name]),
    'headerIcon' => 'inbox',
    'headerTitle' => trans('messages.show.cohort', ['cohort' => $cohort->name]),
])
    @slot('headerActions')
        <a class="btn btn-success" role="button" href="{{ route('cohort.user.assign', $cohort->id) }}">
            @lang('actions.assign.users')
        </a>
        <a class="btn btn-dark" role="button" href="{{ route('cohort.course.assign', $cohort->id) }}">
            @lang('actions.assign.courses')
        </a>
        <a class="btn btn-primary" role="button" href="{{ route('cohort.update', $cohort->id) }}">
            @lang('actions.update.title')
        </a>
        <a class="btn btn-danger" role="button" href="#">
            @lang('actions.delete.title')
        </a>
    @endslot

    @slot('rows')
        @component('components.show.row', [
            'title' => trans('fields.name'),
            'data' => $cohort->name
        ])
        @endcomponent

        @component('components.show.row', [
            'title' => trans('fields.courses')
        ])
            @slot('data')
                <a href="{{ route('cohort.course.list', $cohort->id) }}">
                    {{ $cohort->courses->count() }}
                </a>
            @endslot
        @endcomponent


        @component('components.show.row', [
            'title' => trans('fields.users')
        ])
            @slot('data')
                <a href="{{ route('cohort.user.list', $cohort->id) }}">
                    {{ $cohort->users->count() }}
                </a>
            @endslot
        @endcomponent
        
        <hr/>

        <!-- Courses -->
        @component('components.show.card', [
            'headerIcon' => 'book',
            'headerTitle' => trans('messages.title.courses'),
            'headerActionRoute' => route('cohort.course.list', $cohort->id),
            'headerActionTitle' => trans('actions.show.courses'),
            'array' => $cohort->courses,
            'none' => trans('messages.none.courses'),
        ])
            @slot('body')
                @foreach($cohort->courses as $course)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-8">
                                {{ $course->title }}
                            </div>
                            <div class="col-4 text-right">
                                <a class="btn btn-sm btn-outline-info" role="button" href="{{ route('course.show',
                                $course->id) }}">
                                    @lang('actions.show.title')
                                </a>
                            </div>
                        </div>
                    </li>
                @endforeach
            @endslot
        @endcomponent

        <!-- Users -->
        @component('components.show.card', [
            'headerIcon' => 'users',
            'headerTitle' => trans('messages.title.users'),
            'headerActionRoute' => route('cohort.user.list', $cohort->id),
            'headerActionTitle' => trans('actions.show.users'),
            'array' => $cohort->users,
            'none' => trans('messages.none.users'),
        ])
            @slot('body')
                @foreach($cohort->users as $user)
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
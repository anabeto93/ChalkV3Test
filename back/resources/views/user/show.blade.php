@component('components.show.view', [
    'title' => trans('messages.show.user', ['user' => $user->name]),
    'headerIcon' => 'user',
    'headerTitle' => trans('messages.show.user', ['user' => $user->name]),
])
    @slot('headerActions')
        <a class="btn btn-dark" role="button" href="{{ route('user.course.assign', $user->id) }}">
            @lang('actions.assign.course')
        </a>
        <a class="btn btn-primary" role="button" href="{{ route('user.update', $user->id) }}">
            @lang('actions.update.title')
        </a>
        <a class="btn btn-danger" role="button" href="#">
            @lang('actions.delete.title')
        </a>
    @endslot

    @slot('rows')
        @component('components.show.row', [
            'title' => trans('fields.name'),
            'data' => $user->first_name . " " . $user->last_name
        ])
        @endcomponent


        @component('components.show.row', [
            'title' => trans('fields.phone_number'),
            'data' => $user->phone_number
        ])
        @endcomponent


        @component('components.show.row', [
            'title' => trans('fields.token'),
            'data' => $user->token
        ])
        @endcomponent


        @component('components.show.row', [
            'title' => trans('fields.country'),
            'data' => $user->country
        ])
        @endcomponent


        @component('components.show.row', [
            'title' => trans('fields.language'),
            'data' => $user->language
        ])
        @endcomponent


        @component('components.show.row', [
            'title' => trans('fields.login_last_sent'),
            'data' => \Carbon\Carbon::parse($user->login_access_last_sent)->format('l, jS F Y \\- H:i:s')
        ])
        @endcomponent


        @component('components.show.row', [
            'title' => trans('fields.token_last_used'),
            'data' => \Carbon\Carbon::parse($user->token_last_used)->format('l, jS F Y \\- H:i:s')
        ])
        @endcomponent


        @component('components.show.row', [
            'title' => trans('fields.courses'),
            'data' => $user->courses()->count()
        ])
        @endcomponent


        @component('components.show.row', [
            'title' => trans('fields.created_at'),
            'data' => \Carbon\Carbon::parse($user->created_at)->format('l, jS F Y')
        ])
        @endcomponent


        @component('components.show.row', [
            'title' => trans('fields.updated_at'),
            'data' => \Carbon\Carbon::parse($user->updated_at)->format('l, jS F Y')
        ])
        @endcomponent

        <hr />

        <!-- Courses -->
        @component('components.show.card', [
            'headerIcon' => 'book',
            'headerTitle' => trans('messages.title.courses'),
            'headerActionRoute' => route('user.course.list', $user->id),
            'headerActionTitle' => trans('actions.show.courses'),
            'array' => $user->courses,
            'none' => trans('messages.none.courses')
        ])
            @slot('body')
                @foreach($user->courses as $course)
                    <li class="list-group-item">
                        {{ $course->title }}

                        <span class="float-right">
                            <a class="btn btn-sm btn-outline-info" role="button" href="{{ route('course.show',
                            $course->id) }}">
                                @lang('actions.show.title')
                            </a>
                        </span>
                    </li>
                @endforeach
            @endslot
        @endcomponent

        <!-- Cohorts -->
        @component('components.show.card', [
            'headerIcon' => 'inbox',
            'headerTitle' => trans('messages.title.cohorts'),
            'array' => $user->cohorts,
            'none' => trans('messages.none.cohorts')
        ])
            @slot('body')
                @foreach($user->cohorts as $cohort)
                    <li class="list-group-item">
                        {{ $cohort->name }}

                        <span class="float-right">
                            <a class="btn btn-sm btn-outline-info" role="button" href="{{ route('cohort.show',
                            $cohort->id) }}">
                                @lang('actions.show.title')
                            </a>
                        </span>
                    </li>
                @endforeach
            @endslot
        @endcomponent
    @endslot
@endcomponent
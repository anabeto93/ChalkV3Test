@component('components.show.view', [
    'title' => trans('messages.show.quizResult', ['user' => $quizResult->user->name]),
    'headerIcon' => 'layers',
    'headerTitle' => trans('messages.show.quizResult', ['user' => $quizResult->user->name]),
])
    @slot('rows')
        @component('components.show.row', [
            'title' => trans('fields.name')
        ])
            @slot('data')
                <a href="{{ route('user.show', $quizResult->user->id) }}">
                    {{ $quizResult->user->name }}
                </a>
            @endslot
        @endcomponent



        @component('components.show.row', [
            'title' => trans('fields.total_correct'),
            'data' => $quizResult->total_correct
        ])
        @endcomponent


        @component('components.show.row', [
            'title' => trans('fields.grade')."%",
            'data' => $quizResult->gradePercent
        ])
        @endcomponent


        @component('components.show.row', [
            'title' => trans('fields.medium'),
            'data' => $quizResult->medium
        ])
        @endcomponent


        <hr/>

    @endslot
@endcomponent
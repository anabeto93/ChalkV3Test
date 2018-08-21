@component('components.show.view', [
    'title' => trans('messages.show.quiz', ['quiz' => $quiz->title]),
    'headerIcon' => 'zap',
    'headerTitle' => trans('messages.show.quiz', ['quiz' => $quiz->title]),
])
    @slot('headerActions')
        <a class="btn btn-dark" role="button" href="{{ route('quiz.question.create', $quiz->id) }}">
            @lang('actions.create.question')
        </a>
        <a class="btn btn-primary" role="button" href="{{ route('quiz.update', $quiz->id) }}">
            @lang('actions.update.title')
        </a>
        <a class="btn btn-danger" role="button" href="#">
            @lang('actions.delete.title')
        </a>
    @endslot

    @slot('rows')
        @component('components.show.row', [
            'title' => trans('fields.description'),
            'data' => $quiz->description
        ])
        @endcomponent



        @component('components.show.row', [
            'title' => trans('fields.enabled'),
        ])
            @slot('data')
                @if($quiz->enabled)
                    <i data-feather="check"></i>
                @else
                    <i data-feather="x"></i>
                @endif
            @endslot
        @endcomponent


        @component('components.show.row', [
            'title' => trans('fields.questions'),
        ])
            @slot('data')
                <a href="{{ route('quiz.question.list', $quiz->id) }}">
                    {{ $quiz->questions->count() }}
                </a>
            @endslot
        @endcomponent


        @component('components.show.row', [
            'title' => trans('fields.quizResults'),
        ])
            @slot('data')
                <a href="{{ route('quiz.result.list', $quiz->id) }}">
                    {{ $quiz->quizResults->count() }}
                </a>
            @endslot
        @endcomponent


        @component('components.show.row', [
            'title' => trans('fields.created_at'),
            'data' => \Carbon\Carbon::parse($quiz->created_at)->format('l, jS F Y')
        ])
        @endcomponent


        @component('components.show.row', [
            'title' => trans('fields.updated_at'),
            'data' => \Carbon\Carbon::parse($quiz->updated_at)->format('l, jS F Y')
        ])
        @endcomponent

        <hr/>

        <!-- Questions -->
        @component('components.show.card', [
            'headerIcon' => 'help-circle',
            'headerTitle' => trans('messages.title.questions'),
            'headerActionRoute' => route('quiz.question.list', $quiz->id),
            'headerActionTitle' => trans('actions.show.questions'),
            'array' => $quiz->questions,
            'none' => trans('messages.none.questions'),
        ])
            @slot('body')
                @foreach($quiz->questions as $question)
                    <li class="list-group-item">
                        {{ $question->title }}
                    </li>
                @endforeach
            @endslot
        @endcomponent

        <!-- QuizResults -->
        @component('components.show.card', [
            'headerIcon' => 'layers',
            'headerTitle' => trans('messages.title.quizResults'),
            'headerActionRoute' => route('quiz.result.list', $quiz->id),
            'headerActionTitle' => trans('actions.show.quizResults'),
            'array' => $quiz->quizResults,
            'none' => trans('messages.none.quizResults'),
        ])
            @slot('body')
                @foreach($quiz->quizResults as $quizResult)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-6">
                                {{ $quizResult->user->name }}
                            </div>

                            <div class="col-3">
                                {{ $quizResult->gradePercent . "%" }}
                            </div>

                            <div class="col-3 text-right">
                                <a class="btn btn-sm btn-outline-info" role="button"
                                   href="{{ route('quiz.result.show', [
                                       'quiz' => $quiz->id,
                                       '$quizResult' => $quizResult->id
                                   ]) }}">
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
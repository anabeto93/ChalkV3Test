@component('components.list', [
    'title' => $quiz-> title . " ~ " . trans('messages.list.questions'),
    'headerActionRoute' => route('quiz.question.create', $quiz->id),
    'headerActionTitle' => trans('actions.create.question'),
    'array' => $quiz->questions,
    'none' => trans('messages.none.questions')
])
    @slot('headerTitle')
        <a href="{{ route('quiz.show', $quiz->id) }}">
            {{ $quiz->title }}
        </a> ~ @lang('messages.list.questions')
    @endslot

    @slot('table')
        <table class="table table-striped">
            <thead>
                <tr class="row">
                    <th class="col-4">@lang('fields.title')</th>
                    <th class="col-2">@lang('fields.type')</th>
                    <th class="col-3">@lang('fields.feedback')</th>
                    <th class="col-1 text-center">@lang('fields.questionAnswers')</th>
                    <th class="col-2 text-right"><span class="text-center">@lang('fields.actions')</span></th>
                </tr>
            </thead>

            <tbody>
            @foreach($quiz->questions as $question)
                <tr class="row">
                    <td class="col-4">{{ $question->title }}</td>
                    <td class="col-2">{{ Config::get('constants.quiz_type.'.$question->type) }}</td>
                    <td class="col-3">{{ $question->feedback }}</td>
                    <td class="col-1 text-center">{{ $question->questionAnswers->count() }}</td>
                    <td class="col-2 text-right">
                        <a class="btn btn-primary btn-sm" role="button" href="{{ route('quiz.question.update', [
                        'quiz' => $quiz->id,
                        'question'=> $question->id
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
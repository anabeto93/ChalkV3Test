@component('components.list', [
    'title' => $quiz-> title . " ~ " . trans('messages.list.quizResults'),
    'headerIcon' => 'zap',
    'array' => $quiz->quizResults,
    'none' => trans('messages.none.quizResults')
])
    @slot('headerTitle')
        <a href="{{ route('quiz.show', $quiz->id) }}">
            {{ $quiz->title }}
        </a> ~ @lang('messages.list.quizResults')
    @endslot

    @slot('table')
        <table class="table table-striped">
            <thead>
                <tr class="row">
                    <th class="col-4">@lang('fields.name')</th>
                    <th class="col-2">@lang('fields.total_correct')</th>
                    <th class="col-2">@lang('fields.medium')</th>
                    <th class="col-2">@lang('fields.grade')</th>
                    <th class="col-2 text-right"><span class="text-center">@lang('fields.actions')</span></th>
                </tr>
            </thead>

            <tbody>
            @foreach($quiz->quizResults as $quizResult)
                <tr class="row">
                    <td class="col-4">{{ $quizResult->user->name }}</td>
                    <td class="col-2">{{ $quizResult->total_correct }}</td>
                    <td class="col-2">{{ $quizResult->medium }}</td>
                    <td class="col-2">{{ round((float)$quizResult->grade * 100) }}</td>
                    <td class="col-2 text-center">

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endslot
@endcomponent
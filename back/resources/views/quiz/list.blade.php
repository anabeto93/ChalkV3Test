@component('components.list', [
    'title' => trans('messages.list.quizzes'),
    'headerTitle' => trans('messages.list.quizzes'),
    'headerActionRoute' => route('quiz.create'),
    'headerActionTitle' => trans('actions.create.quiz'),
    'array' => $quizzes,
    'none' => trans('messages.none.quizzes')
])
    @slot('table')
        <table class="table table-striped">
            <thead>
                <tr class="row">
                    <th class="col-3">@lang('fields.title')</th>
                    <th class="col-3">@lang('fields.description')</th>
                    <th class="col-2 text-center">@lang('fields.questions')</th>
                    <th class="col-1 text-center">@lang('fields.enabled')</th>
                    <th class="col-3 text-right"><span class="text-center">@lang('fields.actions')</span></th>
                </tr>
            </thead>

            <tbody>
                 @foreach($quizzes as $quiz)
                    <tr class="row">
                        <td class="col-3">{{ $quiz->title }}</td>
                        <td class="col-3">{{ $quiz->description }}</td>
                        <td class="col-2 text-center">{{ $quiz->questions->count() }}</td>
                        <td class="col-1 text-center">
                            @if($quiz->enabled)
                                <i data-feather="check"></i>
                            @endif
                        </td>
                        <td class="col-3 text-right">
                            <a class="btn btn-primary btn-sm" role="button" href="{{ route('quiz.update', $quiz->id) }}">
                                @lang('actions.update.title')
                            </a>
                            <a class="btn btn-danger btn-sm" role="button" href="#">
                                @lang('actions.delete.title')
                            </a>
                            <div class="btn-group" role="group">
                                <a class="btn btn-info btn-sm" role="button" href="{{ route('quiz.show', $quiz->id) }}">
                                    @lang('actions.show.title')
                                </a>

                                <button type="button" class="btn btn-info btn-sm active dropdown-toggle"
                                        data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">

                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('quiz.question.list', $quiz->id) }}">
                                        <i width="16" height="16" data-feather="help-circle"></i>
                                        <span class="align-middle">@lang('messages.title.questions')</span>
                                    </a>
                                    <a class="dropdown-item" href="{{ route('quiz.result.list', $quiz->id) }}">
                                        <i width="16" height="16" data-feather="layers"></i>
                                        <span class="align-middle">@lang('messages.title.quizResults')</span>
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
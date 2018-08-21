@extends('layouts.form')

@section('title')
    {{ $quiz->title }} ~ @lang(empty($question) ? 'actions.create.question' : 'actions.update.question')
@endsection

@section('form.title')
    <a href="{{ route('quiz.show', $quiz->id) }}">
        {{ $quiz->title }}
    </a> ~ @lang(empty($question) ? 'actions.create.question' : 'actions.update.question')
@endsection

@section('form')
    @if(isset($question))
        {{ Form::model($question, ['route' => ['quiz.question.update', $quiz->id, $question->id]]) }}
    @else
        {{ Form::open(['route' => ['quiz.question.create', $quiz->id]]) }}
    @endif
    {{ Form::bsText('title', Input::old('title'), ['required' => true]) }}
    {{ Form::bsSelect('type', ['multiple_choice' => 'Multiple Choice', 'short_exact' => 'Short Exact Answer'],
    Input::old('type'), ['required' => true]) }}
    {{ Form::bsTextArea('feedback', Input::old('feedback')) }}

    {{-- Answers --}}
    <div class="clearfix">
        <h1 class="float-left">@lang('messages.title.questionAnswers')</h1>
        <button type="button" id="add-answer" name="add-answer" class="btn btn-dark float-right">
            @lang('actions.create.questionAnswer')
        </button>
    </div>

    <div id="dynamic-field">
        @if(isset($question->questionAnswers) && filled($question->questionAnswers))
            @for($i = 0; $i < $question->questionAnswers->count(); $i++)
                <div id="{{ 'row'.$i }}" class="row form-inline form-group {{ $i > 0 ? 'dynamic-added' : '' }}">
                    <div class="col-8">
                        <div class="input-group">

                        <input required type="text" name="questionAnswerTitle[]" class="form-control"
                               value="{{ old('questionAnswer.'.$i.'.title') ? old('questionAnswer.'.$i.'.title') :
                               $question->questionAnswers[$i]->title }}" />

                        @if($i > 0)
                            <div class="input-group-append">
                                <button id="{{ $i }}" name="remove" class="input-group btn btn-danger btn-remove"
                                        type="button">
                                    X
                                </button>
                            </div>
                        @endif
                        </div>
                    </div>

                    <div class="col-4 text-center">
                        <div class="form-check">
                            <input type="checkbox" id="{{ 'checkbox'.$i }}" name="questionAnswerCorrect[]"
                                   class="form-check-input"
                                    {{ $question->questionAnswers[$i]->correct ? 'checked' : '' }} />
                            <label class="form-check-label" for="{{ 'checkbox'.$i }}">
                                @lang('fields.correct')
                            </label>
                        </div>
                    </div>
                </div>
            @endfor
        @else
            <div class="row form-inline form-group">
                <div class="col-8">
                    <div class="input-group">
                        <input required type="text" name="questionAnswerTitle[]" class="form-control" />
                    </div>
                </div>

                <div class="col-4 text-center">
                    <div class="form-check">
                        <input id="checkbox" type="checkbox" name="questionAnswerCorrect[]" class="form-check-input" />
                        <label class="form-check-label" for="checkbox">
                            @lang('fields.correct')
                        </label>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection